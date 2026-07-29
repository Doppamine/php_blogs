<?php
declare(strict_types=1);
namespace App\Commands;
use Faker\Generator;
use Faker\Factory;
use PDO;
use Throwable;

class SeedCommand
{
    private const int ARTICLES_COUNT = 100;
    private const array CATEGORIES = [
        ['Fashion', 'Bags, shoes, clothing.'],
        ['Technology', 'Gadgets, software, and the latest tech news.'],
        ['Food', 'Recipes, restaurants, and culinary adventures.'],
        ['Travel', 'Cities, roads, landscapes.'],
        ['Interiors', 'Rooms, textures and the art of arranging a space.'],
        ['Politics', 'Current events, policies, and political analysis.']
    ];

    private const array IMAGES = [
        'Fashion' => ['fashion-01.jpg', 'fashion-02.jpg', 'fashion-03.jpg'],
        'Technology' => ['tech-01.jpg', 'tech-02.jpg', 'tech-03.jpg'],
        'Food' => ['food-01.jpg', 'food-02.jpg', 'food-03.jpg'],
        'Travel' => ['travel-01.jpg', 'travel-02.jpg', 'travel-03.jpg'],
        'Interiors' => ['interior-01.jpg', 'interior-02.jpg', 'interior-03.jpg'],
    ];

    private readonly Generator $faker;
    private readonly PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->faker = Factory::create();
        $this->faker->seed(1234);
        $this->pdo = $pdo;
    }

    /**
     * @throws Throwable
     */
    public function run(): int
    {
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
        foreach (['article_category', 'articles', 'categories'] as $table) {
            $this->pdo->exec("TRUNCATE TABLE $table");
        }
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS=1;');

        $categoryIds = $this->seedCategories();
        $this->seedArticles(array_slice($categoryIds, 0, -1));
        printf("Seeding %d articles.\n", self::ARTICLES_COUNT);
        printf("Seeding %d categories.\n", count(self::CATEGORIES));
        return 0;
    }

    private function seedCategories(): array
    {
        $insert = $this->pdo->prepare('INSERT INTO categories (name, description) VALUES (:name, :description)');
        $ids = [];
        foreach (self::CATEGORIES as [$name, $description]) {
            $insert->execute(['name' => $name, 'description' => $description]);
            $ids[$name] = (int) $this->pdo->lastInsertId();
        }
        return $ids;

    }

    /**
     * @throws Throwable
     */
    private function seedArticles(array $categoryIds): void{
        $insertArticle = $this->pdo->prepare(
            'INSERT INTO articles (title, description, content, image, views_count, published_at)
             VALUES (:title, :description, :content, :image, :views_count, :published_at)'
        );
        $insertLink = $this->pdo->prepare(
            'INSERT INTO article_category (article_id, category_id) VALUES (:article_id, :category_id)'
        );
        $names = array_keys($categoryIds);
        $this->pdo->beginTransaction();
        try {
            for ($i = 0; $i < self::ARTICLES_COUNT; $i++) {
                $picked = $this->faker->randomElements($names, $this->faker->numberBetween(1, 3));
                $insertArticle->execute([
                    'title' => rtrim($this->faker->sentence(5), '.'),
                    'description' => $this->faker->paragraph(),
                    'content' => implode("\n\n", $this->faker->paragraphs(8)),
                    'image' => $this->faker->randomElement(self::IMAGES[$picked[0]]),
                    'views_count' => $this->faker->numberBetween(0, 1000),
                    'published_at' => $this->faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
                ]);


                $articleId = (int) $this->pdo->lastInsertId();
                foreach ($picked as $name) {
                    $insertLink->execute(['article_id' => $articleId, 'category_id' => $categoryIds[$name]]);
                }
            }
            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

}