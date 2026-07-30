<?php

namespace App\Controllers;

use App\Core\NotFoundException;
use App\Core\View;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use DateMalformedStringException;
use Smarty\Exception;

class ArticleController
{
    private ArticleRepository $articleRepository;
    private CategoryRepository $categoryRepository;
    private View $view;
    private int $similarArticles;

    public function __construct(
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        View $view,
        int $similarArticles
    ) {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->view = $view;
        $this->similarArticles = $similarArticles;
    }

    /**
     * @throws DateMalformedStringException
     * @throws Exception
     */
    public function show(int $id): string
    {
        $article = $this->articleRepository->findArticleById($id);
        if (!$article) {
            throw new NotFoundException('Article '.$id.' not found');
        };

        $this->articleRepository->incrementViews($id);
        // Increment view count for view/template as well, so that the user sees the updated count immediately
        $article['views_count']++;
        $categories = $this->categoryRepository->findByArticleId($id);
        $similarArticles = $this->articleRepository->findSimilarArticles($id, $this->similarArticles);

        return $this->view->render('article.tpl', [
            'article' => $article,
            'categories' => $categories,
            'similar' => $similarArticles,
        ]);
    }
}