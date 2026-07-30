<?php

declare(strict_types=1);

namespace App\Repositories;

use DateMalformedStringException;
use DateTimeImmutable;
use PDO;

class CategoryRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @throws DateMalformedStringException
     */
    public function findLatestPerCategory(int $limit): array
    {
        // Group articles by category and order them by published_at in descending order, then limit the number of articles per category to $limit (3)
        $sql = <<<'SQL'
            SELECT c.id                AS category_id,
                   c.name              AS category_name,
                   ranked.id           AS article_id,
                   ranked.title,
                   ranked.description,
                   ranked.image,
                   ranked.published_at
            FROM (
                SELECT ac.category_id,
                       a.id,
                       a.title,
                       a.description,
                       a.image,
                       a.published_at,
                       ROW_NUMBER() OVER (
                           PARTITION BY ac.category_id
                           ORDER BY a.published_at DESC, a.id DESC
                       ) AS rn
                FROM article_category ac
                JOIN articles a ON a.id = ac.article_id
            ) AS ranked
            JOIN categories c ON c.id = ranked.category_id
            WHERE ranked.rn <= :limit
            ORDER BY c.id, ranked.rn
            SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        $categories = [];
        foreach ($statement as $row) {
            $categoryId = $row['category_id'];
            $categories[$categoryId] ??= [
                'id' => $categoryId,
                'name' => $row['category_name'],
                'articles' => []
            ];
            $categories[$categoryId]['articles'][] = [
                'id' => $row['article_id'],
                'title' => $row['title'],
                'description' => $row['description'],
                'image' => $row['image'],
                'date' => (new DateTimeImmutable($row['published_at']))->format('F j, Y'),
            ];
        }
        return array_values($categories);
    }

    public function findCategoryById(int $id): ?array
    {
        $sql = 'SELECT id, name, description FROM categories WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch() ?: null;
    }
}