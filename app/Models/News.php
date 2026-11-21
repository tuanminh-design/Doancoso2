<?php
namespace App\Models;

class News extends Model
{
    private string $baseSelect = 'SELECT n.*, nc.name as category_name, nc.slug as category_slug, u.name as user_name 
                                  FROM news n 
                                  LEFT JOIN news_categories nc ON n.category_id = nc.id 
                                  LEFT JOIN users u ON n.user_id = u.id ';

    public function findById(int $id): ?array
    {
        return $this->fetchOne($this->baseSelect . ' WHERE n.id = ?', [$id]);
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->fetchOne($this->baseSelect . ' WHERE n.slug = ? AND n.is_published = 1', [$slug]);
    }

    public function getLatest(int $limit = 4): array
    {
        $sql = $this->baseSelect . ' WHERE n.is_published = 1 ORDER BY n.published_at DESC LIMIT ' . (int)$limit;
        return $this->fetchAll($sql);
    }

    public function getFiltered(array $filters = [], int $limit = 9, int $offset = 0): array
    {
        [$whereSql, $params] = $this->buildFilterConditions($filters);
        $sql = $this->baseSelect . $whereSql . ' ORDER BY n.published_at DESC LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;
        return $this->fetchAll($sql, $params);
    }

    public function countFiltered(array $filters = []): int
    {
        [$whereSql, $params] = $this->buildFilterConditions($filters);
        $sql = 'SELECT COUNT(*) FROM news n LEFT JOIN news_categories nc ON n.category_id = nc.id ' . $whereSql;
        return (int)$this->fetchColumn($sql, $params);
    }

    private function buildFilterConditions(array $filters): array
    {
        $conditions = [];
        $params = [];

        if (!isset($filters['include_drafts']) || !$filters['include_drafts']) {
            $conditions[] = 'n.is_published = 1';
        }

        if (!empty($filters['category_id'])) {
            $conditions[] = 'n.category_id = ?';
            $params[] = (int)$filters['category_id'];
        } elseif (!empty($filters['category_slug'])) {
            $conditions[] = 'nc.slug = ?';
            $params[] = $filters['category_slug'];
        }

        if (!empty($filters['search'])) {
            $conditions[] = '(n.title LIKE ? OR n.summary LIKE ?)';
            $term = '%' . trim($filters['search']) . '%';
            $params[] = $term;
            $params[] = $term;
        }

        $whereSql = !empty($conditions) ? ' WHERE ' . implode(' AND ', $conditions) : '';
        return [$whereSql, $params];
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO news (category_id, user_id, title, slug, thumbnail, summary, content, author_name, is_published) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $this->execute($sql, [
            !empty($data['category_id']) ? (int)$data['category_id'] : null,
            !empty($data['user_id']) ? (int)$data['user_id'] : null,
            $data['title'],
            $data['slug'],
            $data['thumbnail'] ?? null,
            $data['summary'],
            $data['content'],
            $data['author_name'] ?? 'Ban Biên Tập DaNang PC',
            isset($data['is_published']) ? 1 : 0
        ]);
        return (int)$this->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE news SET category_id = ?, title = ?, slug = ?, thumbnail = ?, summary = ?, 
                                content = ?, author_name = ?, is_published = ? 
                WHERE id = ?';
        return $this->execute($sql, [
            !empty($data['category_id']) ? (int)$data['category_id'] : null,
            $data['title'],
            $data['slug'],
            $data['thumbnail'] ?? null,
            $data['summary'],
            $data['content'],
            $data['author_name'] ?? 'Ban Biên Tập DaNang PC',
            isset($data['is_published']) ? 1 : 0,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->execute('DELETE FROM news WHERE id = ?', [$id]);
    }

    public function incrementView(int $id): void
    {
        $this->execute('UPDATE news SET view_count = view_count + 1 WHERE id = ?', [$id]);
    }

    // Categories
    public function getCategories(): array
    {
        return $this->fetchAll('SELECT nc.*, (SELECT COUNT(*) FROM news n WHERE n.category_id = nc.id AND n.is_published = 1) as news_count 
                                FROM news_categories nc ORDER BY nc.name ASC');
    }

    public function getCategoryBySlug(string $slug): ?array
    {
        return $this->fetchOne('SELECT * FROM news_categories WHERE slug = ?', [$slug]);
    }
}
