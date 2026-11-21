<?php
namespace App\Models;

class Brand extends Model
{
    public function getAll(): array
    {
        return $this->fetchAll('SELECT b.*, (SELECT COUNT(*) FROM products p WHERE p.brand_id = b.id) as product_count 
                                FROM brands b ORDER BY b.name ASC');
    }

    public function findById(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM brands WHERE id = ?', [$id]);
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->fetchOne('SELECT * FROM brands WHERE slug = ?', [$slug]);
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO brands (name, slug, logo) VALUES (?, ?, ?)';
        $this->execute($sql, [
            $data['name'],
            $data['slug'],
            $data['logo'] ?? null
        ]);
        return (int)$this->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE brands SET name = ?, slug = ?, logo = ? WHERE id = ?';
        return $this->execute($sql, [
            $data['name'],
            $data['slug'],
            $data['logo'] ?? null,
            $id
        ]);
    }

    public function hasProducts(int $id): bool
    {
        $count = (int)$this->fetchColumn('SELECT COUNT(*) FROM products WHERE brand_id = ?', [$id]);
        return $count > 0;
    }

    public function delete(int $id): bool
    {
        if ($this->hasProducts($id)) {
            return false;
        }
        return $this->execute('DELETE FROM brands WHERE id = ?', [$id]);
    }
}
