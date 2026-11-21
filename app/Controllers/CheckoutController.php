<?php
namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Helpers\Validator;
use App\Models\Order;
use Exception;

class CheckoutController extends Controller
{
    private Order $orderModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new Order();
    }

    public function index(): void
    {
        $cart = $this->cartService->getCart();

        if (empty($cart['items'])) {
            Session::setFlash('info', 'Giỏ hàng của bạn đang trống. Vui lòng chọn sản phẩm trước khi thanh toán.');
            Url::redirect('products');
            return;
        }

        // Check if any product is out of stock in cart
        foreach ($cart['items'] as $item) {
            if ($item['is_out_of_stock']) {
                Session::setFlash('error', "Sản phẩm \"{$item['name']}\" đã hết hàng. Vui lòng cập nhật lại giỏ hàng.");
                Url::redirect('cart');
                return;
            }
        }

        $user = Auth::user();

        $this->render('checkout.index', [
            'pageTitle' => 'Thanh Toán Đơn Hàng - DaNang PC',
            'cart'      => $cart,
            'user'      => $user,
        ]);
    }

    public function process(): void
    {
        $this->validateCsrf();

        // Prevent rapid duplicate clicks
        if (Session::has('order_submitting')) {
            Url::redirect('checkout');
            return;
        }
        Session::set('order_submitting', true);

        $cart = $this->cartService->getCart();
        if (empty($cart['items'])) {
            Session::remove('order_submitting');
            Session::setFlash('error', 'Giỏ hàng của bạn đang trống.');
            Url::redirect('cart');
            return;
        }

        $validator = Validator::make($_POST);
        $validator->required('customer_name', 'Họ tên người nhận không được để trống.')
                  ->maxLength('customer_name', 100)
                  ->required('customer_phone', 'Số điện thoại không được để trống.')
                  ->phone('customer_phone', 'Số điện thoại không đúng định dạng.')
                  ->required('customer_email', 'Email không được để trống.')
                  ->email('customer_email', 'Địa chỉ email không hợp lệ.')
                  ->required('shipping_address', 'Địa chỉ giao hàng không được để trống.')
                  ->maxLength('shipping_address', 255);

        if ($validator->fails()) {
            Session::remove('order_submitting');
            Session::setFlash('error', implode('<br>', $validator->errors()));
            Url::redirect('checkout');
            return;
        }

        $paymentMethod = in_array($_POST['payment_method'] ?? '', ['cod', 'bank_transfer']) 
            ? $_POST['payment_method'] 
            : 'cod';

        $orderData = [
            'user_id'          => Auth::id(),
            'customer_name'    => trim($_POST['customer_name']),
            'customer_email'   => trim($_POST['customer_email']),
            'customer_phone'   => trim($_POST['customer_phone']),
            'shipping_address' => trim($_POST['shipping_address']),
            'notes'            => !empty($_POST['notes']) ? trim($_POST['notes']) : null,
            'payment_method'   => $paymentMethod,
        ];

        try {
            // Create order and update stock within an atomic transaction
            $orderResult = $this->orderModel->createOrderWithItems($orderData, $cart['items']);

            // Clear shopping cart
            $this->cartService->clear();
            Session::remove('order_submitting');

            // Save order code in session for success page
            Session::set('last_order_code', $orderResult['order_code']);

            Url::redirect('checkout/success?code=' . urlencode($orderResult['order_code']));
        } catch (Exception $e) {
            Session::remove('order_submitting');
            Session::setFlash('error', 'Đặt hàng không thành công: ' . $e->getMessage());
            Url::redirect('cart');
        }
    }

    public function success(): void
    {
        $code = $_GET['code'] ?? Session::get('last_order_code');

        if (empty($code)) {
            Url::redirect('/');
            return;
        }

        $order = $this->orderModel->findByCode($code);
        if (!$order) {
            Url::redirect('/');
            return;
        }

        $items = $this->orderModel->getItems($order['id']);
        $settings = $this->settingModel->getAll();

        $this->render('checkout.success', [
            'pageTitle' => 'Đặt Hàng Thành Công - ' . $order['order_code'],
            'order'     => $order,
            'items'     => $items,
            'settings'  => $settings,
        ]);
    }
}
