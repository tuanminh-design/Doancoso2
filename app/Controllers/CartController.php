<?php
namespace App\Controllers;

use App\Helpers\Session;
use App\Helpers\Url;
use Exception;

class CartController extends Controller
{
    public function index(): void
    {
        $cart = $this->cartService->getCart();

        $this->render('cart.index', [
            'pageTitle' => 'Giỏ Hàng - DaNang PC',
            'cart'      => $cart,
        ]);
    }

    public function add(): void
    {
        $this->validateCsrf();

        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity  = max(1, (int)($_POST['quantity'] ?? 1));

        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
               || (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json'));

        try {
            $result = $this->cartService->add($productId, $quantity);

            if ($isAjax) {
                $this->json($result);
            }

            Session::setFlash('success', $result['message']);
            Url::redirect('cart');
        } catch (Exception $e) {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => $e->getMessage()], 400);
            }

            Session::setFlash('error', $e->getMessage());
            Url::back();
        }
    }

    public function update(): void
    {
        $this->validateCsrf();

        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity  = (int)($_POST['quantity'] ?? 1);

        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
               || (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json'));

        try {
            $result = $this->cartService->update($productId, $quantity);

            if ($isAjax) {
                $this->json($result);
            }

            Session::setFlash('success', $result['message']);
            Url::redirect('cart');
        } catch (Exception $e) {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => $e->getMessage()], 400);
            }

            Session::setFlash('error', $e->getMessage());
            Url::redirect('cart');
        }
    }

    public function remove(): void
    {
        $this->validateCsrf();

        $productId = (int)($_POST['product_id'] ?? 0);
        $this->cartService->remove($productId);

        Session::setFlash('info', 'Đã xóa sản phẩm khỏi giỏ hàng.');
        Url::redirect('cart');
    }

    public function clear(): void
    {
        $this->validateCsrf();

        $this->cartService->clear();
        Session::setFlash('info', 'Đã xóa sạch giỏ hàng.');
        Url::redirect('cart');
    }
}
