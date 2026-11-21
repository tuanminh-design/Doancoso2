<?php
namespace App\Models;

use Exception;

class Order extends Model
{
    /**
     * Create order with items in an atomic database transaction.
     * Re-validates stock and fetches current prices from database to ensure data integrity.
     */
    public function createOrderWithItems(array $orderData, array $cartItems): array
    {
        $this->beginTransaction();

        try {
            // 1. Validate stock and calculate exact server-side subtotal
            $subtotal = 0.0;
            $verifiedItems = [];

            foreach ($cartItems as $item) {
                $productId = (int)$item['product_id'];
                $qty = (int)$item['quantity'];

                if ($qty <= 0) {
                    throw new Exception("Số lượng sản phẩm không hợp lệ.");
                }

                // Fetch latest product info with lock for update
                $product = $this->fetchOne(
                    'SELECT id, name, sku, price, sale_price, stock_quantity, is_active FROM products WHERE id = ? FOR UPDATE',
                    [$productId]
                );

                if (!$product || $product['is_active'] != 1) {
                    throw new Exception("Sản phẩm \"{$item['name']}\" hiện không còn kinh doanh.");
                }

                if ($product['stock_quantity'] < $qty) {
                    throw new Exception(
                        "Sản phẩm \"{$product['name']}\" chỉ còn {$product['stock_quantity']} món trong kho, không đủ đáp ứng số lượng {$qty} bạn yêu cầu."
                    );
                }

                // Use sale price if valid, otherwise regular price
                $unitPrice = ($product['sale_price'] > 0 && $product['sale_price'] < $product['price'])
                    ? (float)$product['sale_price']
                    : (float)$product['price'];

                $itemTotal = $unitPrice * $qty;
                $subtotal += $itemTotal;

                $verifiedItems[] = [
                    'product_id'   => $product['id'],
                    'product_name' => $product['name'],
                    'product_sku'  => $product['sku'],
                    'price'        => $unitPrice,
                    'quantity'     => $qty,
                    'total'        => $itemTotal,
                ];

                // Decrement inventory
                $decremented = $this->execute(
                    'UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ? AND stock_quantity >= ?',
                    [$qty, $product['id'], $qty]
                );

                if (!$decremented) {
                    throw new Exception("Không thể cập nhật tồn kho cho sản phẩm \"{$product['name']}\".");
                }
            }

            // 2. Insert Order
            $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
            $shippingFee = 0.0; // Free shipping in Da Nang promotion
            $totalAmount = $subtotal + $shippingFee;

            $sqlOrder = 'INSERT INTO orders (order_code, user_id, customer_name, customer_email, customer_phone, 
                                            shipping_address, notes, payment_method, payment_status, status, 
                                            subtotal, shipping_fee, total_amount) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

            $this->execute($sqlOrder, [
                $orderCode,
                $orderData['user_id'] ?? null,
                $orderData['customer_name'],
                $orderData['customer_email'],
                $orderData['customer_phone'],
                $orderData['shipping_address'],
                $orderData['notes'] ?? null,
                $orderData['payment_method'] ?? 'cod',
                'unpaid',
                'pending',
                $subtotal,
                $shippingFee,
                $totalAmount
            ]);

            $orderId = (int)$this->lastInsertId();

            // 3. Insert Order Items
            $sqlItem = 'INSERT INTO order_items (order_id, product_id, product_name, product_sku, price, quantity, total) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)';

            foreach ($verifiedItems as $vItem) {
                $this->execute($sqlItem, [
                    $orderId,
                    $vItem['product_id'],
                    $vItem['product_name'],
                    $vItem['product_sku'],
                    $vItem['price'],
                    $vItem['quantity'],
                    $vItem['total']
                ]);
            }

            // 4. Insert Initial Status History
            $this->execute(
                'INSERT INTO order_status_history (order_id, status, note, created_by) VALUES (?, ?, ?, ?)',
                [$orderId, 'pending', 'Khách hàng hoàn tất đặt hàng trực tuyến', $orderData['customer_name']]
            );

            $this->commit();

            return [
                'id'         => $orderId,
                'order_code' => $orderCode,
                'total'      => $totalAmount
            ];
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    public function findById(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM orders WHERE id = ?', [$id]);
    }

    public function findByCode(string $code): ?array
    {
        return $this->fetchOne('SELECT * FROM orders WHERE order_code = ?', [$code]);
    }

    public function getItems(int $orderId): array
    {
        return $this->fetchAll(
            'SELECT oi.*, p.thumbnail, p.slug FROM order_items oi 
             LEFT JOIN products p ON oi.product_id = p.id 
             WHERE oi.order_id = ?',
            [$orderId]
        );
    }

    public function getHistory(int $orderId): array
    {
        return $this->fetchAll(
            'SELECT * FROM order_status_history WHERE order_id = ? ORDER BY created_at ASC',
            [$orderId]
        );
    }

    public function getByUser(int $userId, int $limit = 20, int $offset = 0): array
    {
        return $this->fetchAll(
            'SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset,
            [$userId]
        );
    }

    public function countByUser(int $userId): int
    {
        return (int)$this->fetchColumn('SELECT COUNT(*) FROM orders WHERE user_id = ?', [$userId]);
    }

    public function getAll(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        [$whereSql, $params] = $this->buildFilterConditions($filters);
        $sql = 'SELECT * FROM orders ' . $whereSql . ' ORDER BY id DESC LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;
        return $this->fetchAll($sql, $params);
    }

    public function countAll(array $filters = []): int
    {
        [$whereSql, $params] = $this->buildFilterConditions($filters);
        $sql = 'SELECT COUNT(*) FROM orders ' . $whereSql;
        return (int)$this->fetchColumn($sql, $params);
    }

    private function buildFilterConditions(array $filters): array
    {
        $conditions = [];
        $params = [];

        if (!empty($filters['status'])) {
            $conditions[] = 'status = ?';
            $params[] = $filters['status'];
        }

        if (!empty($filters['payment_status'])) {
            $conditions[] = 'payment_status = ?';
            $params[] = $filters['payment_status'];
        }

        if (!empty($filters['search'])) {
            $conditions[] = '(order_code LIKE ? OR customer_name LIKE ? OR customer_phone LIKE ? OR customer_email LIKE ?)';
            $term = '%' . trim($filters['search']) . '%';
            $params = array_merge($params, [$term, $term, $term, $term]);
        }

        $whereSql = !empty($conditions) ? ' WHERE ' . implode(' AND ', $conditions) : '';
        return [$whereSql, $params];
    }

    public function updateStatus(int $orderId, string $status, ?string $note = null, ?string $by = null): bool
    {
        $order = $this->findById($orderId);
        if (!$order) return false;

        $this->beginTransaction();
        try {
            $this->execute('UPDATE orders SET status = ? WHERE id = ?', [$status, $orderId]);
            $this->execute(
                'INSERT INTO order_status_history (order_id, status, note, created_by) VALUES (?, ?, ?, ?)',
                [$orderId, $status, $note ?? "Cập nhật trạng thái sang {$status}", $by ?? 'Hệ Thống']
            );
            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    public function updatePaymentStatus(int $orderId, string $paymentStatus): bool
    {
        return $this->execute('UPDATE orders SET payment_status = ? WHERE id = ?', [$paymentStatus, $orderId]);
    }

    public function cancelOrder(int $orderId, ?string $note = null, ?string $by = null): bool
    {
        $order = $this->findById($orderId);
        if (!$order || $order['status'] === 'cancelled' || $order['status'] === 'completed') {
            return false;
        }

        $this->beginTransaction();
        try {
            // Restore inventory for each item
            $items = $this->getItems($orderId);
            foreach ($items as $item) {
                if (!empty($item['product_id'])) {
                    $this->execute(
                        'UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?',
                        [$item['quantity'], $item['product_id']]
                    );
                }
            }

            // Update order status
            $this->execute('UPDATE orders SET status = "cancelled" WHERE id = ?', [$orderId]);
            $this->execute(
                'INSERT INTO order_status_history (order_id, status, note, created_by) VALUES (?, ?, ?, ?)',
                [$orderId, 'cancelled', $note ?? 'Đơn hàng đã bị hủy và hoàn trả tồn kho', $by ?? 'Hệ Thống']
            );

            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    // Dashboard analytics
    public function getTotalRevenue(): float
    {
        $rev = $this->fetchColumn("SELECT SUM(total_amount) FROM orders WHERE status = 'completed' OR payment_status = 'paid'");
        return (float)($rev ?: 0);
    }

    public function getPendingCount(): int
    {
        return (int)$this->fetchColumn("SELECT COUNT(*) FROM orders WHERE status = 'pending'");
    }

    public function getRecentOrders(int $limit = 5): array
    {
        return $this->fetchAll('SELECT * FROM orders ORDER BY created_at DESC LIMIT ' . (int)$limit);
    }
}
