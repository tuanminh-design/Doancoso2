<?php
namespace App\Models;

class Category extends Model
{
    public function getAllActive(): array
    {
        return $this->fetchAll('SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC, name ASC');
    }

    public function getAll(): array
    {
        return $this->fetchAll('SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) as product_count 
                                FROM categories c ORDER BY c.sort_order ASC, c.id ASC');
    }

    public function findById(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM categories WHERE id = ?', [$id]);
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->fetchOne('SELECT * FROM categories WHERE slug = ?', [$slug]);
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO categories (name, slug, description, icon, sort_order, is_active) 
                VALUES (?, ?, ?, ?, ?, ?)';
        $this->execute($sql, [
            $data['name'],
            $data['slug'],
            $data['description'] ?? null,
            $data['icon'] ?? 'fa-microchip',
            (int)($data['sort_order'] ?? 0),
            isset($data['is_active']) ? 1 : 0
        ]);
        return (int)$this->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE categories SET name = ?, slug = ?, description = ?, icon = ?, sort_order = ?, is_active = ? 
                WHERE id = ?';
        return $this->execute($sql, [
            $data['name'],
            $data['slug'],
            $data['description'] ?? null,
            $data['icon'] ?? 'fa-microchip',
            (int)($data['sort_order'] ?? 0),
            isset($data['is_active']) ? 1 : 0,
            $id
        ]);
    }

    public function hasProducts(int $id): bool
    {
        $count = (int)$this->fetchColumn('SELECT COUNT(*) FROM products WHERE category_id = ?', [$id]);
        return $count > 0;
    }

    public function delete(int $id): bool
    {
        if ($this->hasProducts($id)) {
            return false;
        }
        return $this->execute('DELETE FROM categories WHERE id = ?', [$id]);
    }
}
