<?php
namespace App\Models;

class Product extends Model
{
    private string $baseSelect = 'SELECT p.*, c.name as category_name, c.slug as category_slug, 
                                         b.name as brand_name, b.slug as brand_slug 
                                  FROM products p 
                                  LEFT JOIN categories c ON p.category_id = c.id 
                                  LEFT JOIN brands b ON p.brand_id = b.id ';

    public function findById(int $id): ?array
    {
        return $this->fetchOne($this->baseSelect . ' WHERE p.id = ?', [$id]);
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->fetchOne($this->baseSelect . ' WHERE p.slug = ? AND p.is_active = 1', [$slug]);
    }

    public function getFeatured(int $limit = 8): array
    {
        $sql = $this->baseSelect . ' WHERE p.is_featured = 1 AND p.is_active = 1 
                                     ORDER BY p.id DESC LIMIT ' . (int)$limit;
        return $this->fetchAll($sql);
    }

    public function getNewArrivals(int $limit = 8): array
    {
        $sql = $this->baseSelect . ' WHERE p.is_active = 1 
                                     ORDER BY p.created_at DESC LIMIT ' . (int)$limit;
        return $this->fetchAll($sql);
    }

    public function getBestSellers(int $limit = 8): array
    {
        $sql = $this->baseSelect . ' WHERE p.is_active = 1 
                                     ORDER BY p.view_count DESC, p.id DESC LIMIT ' . (int)$limit;
        return $this->fetchAll($sql);
    }

    public function getOnSale(int $limit = 8): array
    {
        $sql = $this->baseSelect . ' WHERE p.is_active = 1 AND p.sale_price IS NOT NULL 
                                           AND p.sale_price > 0 AND p.sale_price < p.price 
                                     ORDER BY (p.price - p.sale_price) DESC LIMIT ' . (int)$limit;
        return $this->fetchAll($sql);
    }

    public function getRelated(int $productId, int $categoryId, int $limit = 4): array
    {
        $sql = $this->baseSelect . ' WHERE p.category_id = ? AND p.id != ? AND p.is_active = 1 
                                     ORDER BY RAND() LIMIT ' . (int)$limit;
        return $this->fetchAll($sql, [$categoryId, $productId]);
    }

    public function getSpecs(int $productId): array
    {
        return $this->fetchAll('SELECT * FROM product_specs WHERE product_id = ? ORDER BY sort_order ASC, id ASC', [$productId]);
    }

    public function saveSpecs(int $productId, array $specs): void
    {
        $this->execute('DELETE FROM product_specs WHERE product_id = ?', [$productId]);
        $order = 1;
        foreach ($specs as $spec) {
            $name = trim($spec['name'] ?? '');
            $value = trim($spec['value'] ?? '');
            if ($name !== '' && $value !== '') {
                $this->execute(
                    'INSERT INTO product_specs (product_id, spec_name, spec_value, sort_order) VALUES (?, ?, ?, ?)',
                    [$productId, $name, $value, $order++]
                );
            }
        }
    }

    public function getFilteredProducts(array $filters = [], int $limit = 12, int $offset = 0): array
    {
        [$whereSql, $params] = $this->buildFilterConditions($filters);

        $sortSql = match ($filters['sort'] ?? '') {
            'price_asc'  => ' ORDER BY COALESCE(NULLIF(p.sale_price, 0), p.price) ASC',
            'price_desc' => ' ORDER BY COALESCE(NULLIF(p.sale_price, 0), p.price) DESC',
            'name_asc'   => ' ORDER BY p.name ASC',
            'newest'     => ' ORDER BY p.created_at DESC',
            default      => ' ORDER BY p.id DESC',
        };

        $sql = $this->baseSelect . $whereSql . $sortSql . ' LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;
        return $this->fetchAll($sql, $params);
    }

    public function countFilteredProducts(array $filters = []): int
    {
        [$whereSql, $params] = $this->buildFilterConditions($filters);
        $sql = 'SELECT COUNT(*) FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN brands b ON p.brand_id = b.id ' . $whereSql;
        return (int)$this->fetchColumn($sql, $params);
    }

    private function buildFilterConditions(array $filters): array
    {
        $conditions = [];
        $params = [];

        // For public catalog, only active products unless explicitly requested by admin
        if (!isset($filters['include_inactive']) || !$filters['include_inactive']) {
            $conditions[] = 'p.is_active = 1';
        }

        if (!empty($filters['category_id'])) {
            $conditions[] = 'p.category_id = ?';
            $params[] = (int)$filters['category_id'];
        } elseif (!empty($filters['category_slug'])) {
            $conditions[] = 'c.slug = ?';
            $params[] = $filters['category_slug'];
        }

        if (!empty($filters['brand_id'])) {
            $conditions[] = 'p.brand_id = ?';
            $params[] = (int)$filters['brand_id'];
        } elseif (!empty($filters['brand_slug'])) {
            $conditions[] = 'b.slug = ?';
            $params[] = $filters['brand_slug'];
        }

        if (isset($filters['in_stock']) && $filters['in_stock'] !== '') {
            if ($filters['in_stock'] == 1) {
                $conditions[] = 'p.stock_quantity > 0';
            } else {
                $conditions[] = 'p.stock_quantity <= 0';
            }
        }

        if (isset($filters['min_price']) && is_numeric($filters['min_price']) && $filters['min_price'] > 0) {
            $conditions[] = 'COALESCE(NULLIF(p.sale_price, 0), p.price) >= ?';
            $params[] = (float)$filters['min_price'];
        }

        if (isset($filters['max_price']) && is_numeric($filters['max_price']) && $filters['max_price'] > 0) {
            $conditions[] = 'COALESCE(NULLIF(p.sale_price, 0), p.price) <= ?';
            $params[] = (float)$filters['max_price'];
        }

        if (!empty($filters['search'])) {
            $conditions[] = '(p.name LIKE ? OR p.sku LIKE ? OR p.short_description LIKE ?)';
            $term = '%' . trim($filters['search']) . '%';
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $whereSql = !empty($conditions) ? ' WHERE ' . implode(' AND ', $conditions) : '';
        return [$whereSql, $params];
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO products (category_id, brand_id, name, sku, slug, price, sale_price, 
                                      stock_quantity, thumbnail, short_description, description, 
                                      is_featured, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $this->execute($sql, [
            (int)$data['category_id'],
            !empty($data['brand_id']) ? (int)$data['brand_id'] : null,
            $data['name'],
            $data['sku'],
            $data['slug'],
            (float)$data['price'],
            !empty($data['sale_price']) ? (float)$data['sale_price'] : null,
            (int)($data['stock_quantity'] ?? 0),
            $data['thumbnail'] ?? null,
            $data['short_description'] ?? null,
            $data['description'] ?? null,
            isset($data['is_featured']) ? 1 : 0,
            isset($data['is_active']) ? 1 : 0,
        ]);
        return (int)$this->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE products SET category_id = ?, brand_id = ?, name = ?, sku = ?, slug = ?, 
                                    price = ?, sale_price = ?, stock_quantity = ?, 
                                    thumbnail = ?, short_description = ?, description = ?, 
                                    is_featured = ?, is_active = ? 
                WHERE id = ?';
        return $this->execute($sql, [
            (int)$data['category_id'],
            !empty($data['brand_id']) ? (int)$data['brand_id'] : null,
            $data['name'],
            $data['sku'],
            $data['slug'],
            (float)$data['price'],
            !empty($data['sale_price']) ? (float)$data['sale_price'] : null,
            (int)($data['stock_quantity'] ?? 0),
            $data['thumbnail'] ?? null,
            $data['short_description'] ?? null,
            $data['description'] ?? null,
            isset($data['is_featured']) ? 1 : 0,
            isset($data['is_active']) ? 1 : 0,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        // Safe delete: will fail if foreign key constraint restricts it
        return $this->execute('DELETE FROM products WHERE id = ?', [$id]);
    }

    public function incrementView(int $id): void
    {
        $this->execute('UPDATE products SET view_count = view_count + 1 WHERE id = ?', [$id]);
    }

    public function decrementStock(int $id, int $quantity): bool
    {
        // Only decrement if stock_quantity >= quantity to prevent negative stock
        $sql = 'UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ? AND stock_quantity >= ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$quantity, $id, $quantity]);
        return $stmt->rowCount() > 0;
    }

    public function incrementStock(int $id, int $quantity): bool
    {
        $sql = 'UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?';
        return $this->execute($sql, [$quantity, $id]);
    }
}
