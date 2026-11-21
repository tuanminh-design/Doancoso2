<?php
namespace App\Services;

use App\Helpers\Session;
use App\Models\Product;
use Exception;

class CartService
{
    private Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    /**
     * Get detailed cart with live product data re-verified from database
     */
    public function getCart(): array
    {
        $rawCart = Session::get('cart', []);
        $items = [];
        $subtotal = 0.0;
        $totalQuantity = 0;

        foreach ($rawCart as $productId => $qty) {
            $productId = (int)$productId;
            $qty = (int)$qty;
            if ($qty <= 0) continue;

            $product = $this->productModel->findById($productId);
            if (!$product || $product['is_active'] != 1) {
                // Product was deleted or deactivated
                continue;
            }

            // Actual price from DB (never client-supplied)
            $unitPrice = ($product['sale_price'] > 0 && $product['sale_price'] < $product['price'])
                ? (float)$product['sale_price']
                : (float)$product['price'];

            // Cap requested quantity at max available stock
            $maxStock = (int)$product['stock_quantity'];
            $isOutOfStock = ($maxStock <= 0);
            $effectiveQty = $isOutOfStock ? 0 : min($qty, $maxStock);

            $itemSubtotal = $unitPrice * $effectiveQty;
            $subtotal += $itemSubtotal;
            $totalQuantity += $effectiveQty;

            $items[$productId] = [
                'product_id'     => $product['id'],
                'name'           => $product['name'],
                'sku'            => $product['sku'],
                'slug'           => $product['slug'],
                'thumbnail'      => $product['thumbnail'],
                'category_name'  => $product['category_name'],
                'price'          => (float)$product['price'],
                'sale_price'     => $product['sale_price'] ? (float)$product['sale_price'] : null,
                'unit_price'     => $unitPrice,
                'quantity'       => $effectiveQty,
                'max_stock'      => $maxStock,
                'is_out_of_stock'=> $isOutOfStock,
                'subtotal'       => $itemSubtotal,
            ];
        }

        return [
            'items'          => $items,
            'subtotal'       => $subtotal,
            'shipping_fee'   => 0.0, // Free shipping
            'total'          => $subtotal,
            'total_quantity' => $totalQuantity,
        ];
    }

    public function add(int $productId, int $quantity = 1): array
    {
        if ($quantity <= 0) {
            throw new Exception("Số lượng phải lớn hơn 0.");
        }

        $product = $this->productModel->findById($productId);
        if (!$product || $product['is_active'] != 1) {
            throw new Exception("Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.");
        }

        if ($product['stock_quantity'] <= 0) {
            throw new Exception("Sản phẩm \"{$product['name']}\" hiện đã hết hàng.");
        }

        $cart = Session::get('cart', []);
        $currentQty = (int)($cart[$productId] ?? 0);
        $newQty = $currentQty + $quantity;

        if ($newQty > $product['stock_quantity']) {
            throw new Exception("Số lượng yêu cầu ({$newQty}) vượt quá tồn kho hiện có ({$product['stock_quantity']}).");
        }

        $cart[$productId] = $newQty;
        Session::set('cart', $cart);

        return [
            'success'     => true,
            'cart_count'  => $this->count(),
            'message'     => "Đã thêm \"{$product['name']}\" vào giỏ hàng thành công!"
        ];
    }

    public function update(int $productId, int $quantity): array
    {
        $cart = Session::get('cart', []);

        if ($quantity <= 0) {
            unset($cart[$productId]);
            Session::set('cart', $cart);
            return [
                'success'    => true,
                'cart_count' => $this->count(),
                'message'    => "Đã xóa sản phẩm khỏi giỏ hàng."
            ];
        }

        $product = $this->productModel->findById($productId);
        if (!$product || $product['is_active'] != 1) {
            unset($cart[$productId]);
            Session::set('cart', $cart);
            throw new Exception("Sản phẩm không khả dụng.");
        }

        if ($quantity > $product['stock_quantity']) {
            throw new Exception("Kho chỉ còn {$product['stock_quantity']} sản phẩm.");
        }

        $cart[$productId] = $quantity;
        Session::set('cart', $cart);

        return [
            'success'    => true,
            'cart_count' => $this->count(),
            'message'    => "Đã cập nhật số lượng thành công."
        ];
    }

    public function remove(int $productId): void
    {
        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::set('cart', $cart);
    }

    public function clear(): void
    {
        Session::remove('cart');
    }

    public function count(): int
    {
        $cart = Session::get('cart', []);
        return array_sum($cart);
    }
}
