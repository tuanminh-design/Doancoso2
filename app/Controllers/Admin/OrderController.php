<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Helpers\Auth;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Middleware\AdminMiddleware;
use App\Models\Order;

class OrderController extends Controller
{
    private Order $orderModel;

    public function __construct()
    {
        parent::__construct();
        AdminMiddleware::handle();
        $this->orderModel = new Order();
    }

    public function index(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $filters = [
            'status'         => !empty($_GET['status']) ? trim($_GET['status']) : null,
            'payment_status' => !empty($_GET['payment_status']) ? trim($_GET['payment_status']) : null,
            'search'         => !empty($_GET['q']) ? trim($_GET['q']) : null,
        ];

        $orders = $this->orderModel->getAll($filters, $limit, $offset);
        $totalOrders = $this->orderModel->countAll($filters);
        $totalPages = max(1, (int)ceil($totalOrders / $limit));

        $this->render('admin.orders.index', [
            'pageTitle'   => 'Quản Lý Đơn Hàng - DaNang PC Admin',
            'orders'      => $orders,
            'totalOrders' => $totalOrders,
            'page'        => $page,
            'totalPages'  => $totalPages,
            'filters'     => $filters,
        ], 'admin');
    }

    public function detail(string $id): void
    {
        $order = $this->orderModel->findById((int)$id);
        if (!$order) {
            Session::setFlash('error', 'Đơn hàng không tồn tại.');
            Url::redirect('admin/orders');
            return;
        }

        $items = $this->orderModel->getItems($order['id']);
        $history = $this->orderModel->getHistory($order['id']);

        $this->render('admin.orders.detail', [
            'pageTitle' => 'Chi Tiết Đơn Hàng #' . $order['order_code'],
            'order'     => $order,
            'items'     => $items,
            'history'   => $history,
        ], 'admin');
    }

    public function updateStatus(string $id): void
    {
        $this->validateCsrf();
        $orderId = (int)$id;

        $newStatus = trim($_POST['status'] ?? '');
        $note = !empty($_POST['note']) ? trim($_POST['note']) : 'Cập nhật bởi Quản trị viên ' . Auth::name();

        if (!in_array($newStatus, ['pending', 'confirmed', 'shipping', 'completed', 'cancelled'])) {
            Session::setFlash('error', 'Trạng thái đơn hàng không hợp lệ.');
            Url::redirect('admin/orders/' . $orderId);
            return;
        }

        if ($newStatus === 'cancelled') {
            // Cancel through dedicated inventory restore workflow
            $ok = $this->orderModel->cancelOrder($orderId, $note, Auth::name());
            if ($ok) {
                Session::setFlash('success', 'Đã hủy đơn hàng và hoàn trả số lượng vào kho thành công.');
            } else {
                Session::setFlash('error', 'Không thể hủy đơn hàng này.');
            }
        } else {
            $ok = $this->orderModel->updateStatus($orderId, $newStatus, $note, Auth::name());
            if ($ok) {
                Session::setFlash('success', 'Đã cập nhật trạng thái đơn hàng thành công.');
            } else {
                Session::setFlash('error', 'Không thể cập nhật trạng thái đơn hàng.');
            }
        }

        Url::redirect('admin/orders/' . $orderId);
    }

    public function updatePayment(string $id): void
    {
        $this->validateCsrf();
        $orderId = (int)$id;

        $paymentStatus = trim($_POST['payment_status'] ?? '');
        if (!in_array($paymentStatus, ['unpaid', 'paid'])) {
            Session::setFlash('error', 'Trạng thái thanh toán không hợp lệ.');
            Url::redirect('admin/orders/' . $orderId);
            return;
        }

        $this->orderModel->updatePaymentStatus($orderId, $paymentStatus);
        Session::setFlash('success', 'Đã cập nhật trạng thái thanh toán thành công.');
        Url::redirect('admin/orders/' . $orderId);
    }

    public function cancel(string $id): void
    {
        $this->validateCsrf();
        $orderId = (int)$id;

        $note = !empty($_POST['note']) ? trim($_POST['note']) : 'Quản trị viên hủy đơn hàng';
        $ok = $this->orderModel->cancelOrder($orderId, $note, Auth::name());

        if ($ok) {
            Session::setFlash('success', 'Đã hủy đơn hàng và hoàn trả lại số lượng tồn kho thành công.');
        } else {
            Session::setFlash('error', 'Đơn hàng không thể hủy hoặc đã được hoàn thành trước đó.');
        }

        Url::redirect('admin/orders/' . $orderId);
    }
}
