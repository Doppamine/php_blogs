<?php

namespace App\Controllers;

use App\Core\NotFoundException;
use App\Core\View;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;

class ArticleController
{
    private ArticleRepository $articleRepository;
    private CategoryRepository $categoryRepository;
    private View $view;


    public function __construct(
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        View $view
    ) {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->view = $view;
    }

    public function show(int $id): string
    {
        $article = $this->articleRepository->findArticleById($id);
        if (!$article) {
            throw new NotFoundException('Article '.$id.' not found');
        }

        $categories = $this->categoryRepository->findByArticleId($id);


        return $this->view->render('article.tpl', [
            'article' => $article,
            'categories' => $categories,
        ]);
    }
}