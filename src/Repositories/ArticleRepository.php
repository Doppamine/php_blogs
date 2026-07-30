<?php

declare(strict_types=1);

namespace App\Repositories;

use DateMalformedStringException;
use DateTimeImmutable;
use PDO;

class ArticleRepository
{
    private PDO $pdo;

    private const array AVAILABLE_SORTS = [
        'date' => 'a.published_at DESC, a.id DESC',
        'views' => 'a.views_count DESC, a.id DESC',
    ];


    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function normalizeSort(string $sort): string
    {
        return isset(self::AVAILABLE_SORTS[$sort]) ? $sort : 'date';
    }

    public function findArticleById(int $id): ?array
    {
        $sql = 'SELECT * FROM articles WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch() ?: null;
    }

    /**
     * @throws DateMalformedStringException
     */
    public function articlesByCategoryId(int $categoryId, string $sort, int $limit, int $offset): array
    {
        $orderBy = self::AVAILABLE_SORTS[$this->normalizeSort($sort)];

        $sql = <<<SQL
            SELECT a.id,
                   a.title,
                   a.description,
                   a.image,
                   a.views_count,
                   a.published_at
            FROM articles a
            JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset
            SQL;
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        $articles = [];
        foreach ($statement as $row) {
            $articles[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'description' => $row['description'],
                'image' => $row['image'],
                'views_count' => $row['views_count'],
                'date' => (new DateTimeImmutable($row['published_at']))->format('F j, Y'),
            ];
        }
        return $articles;
    }

    public function countArticlesByCategoryId(int $categoryId): int
    {
        $sql = 'SELECT COUNT(*) FROM article_category WHERE category_id = :category_id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $statement->execute();
        return (int)$statement->fetchColumn();
    }
}