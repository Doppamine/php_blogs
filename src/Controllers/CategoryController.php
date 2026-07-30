<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\NotFoundException;
use App\Core\Paginator;
use App\Core\View;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use DateMalformedStringException;
use Smarty\Exception;

class CategoryController
{
    private CategoryRepository $categoryRepository;
    private ArticleRepository $articleRepository;
    private View $view;
    private int $articlesPerPage;

    public function __construct(
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        View $view,
        int $articlesPerPage
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->articleRepository = $articleRepository;
        $this->view = $view;
        $this->articlesPerPage = $articlesPerPage;
    }

    /**
     * @throws DateMalformedStringException
     * @throws Exception
     */
    public function show(int $id): string
    {
        $category = $this->categoryRepository->findCategoryById($id);
        if (!$category) {
            throw new NotFoundException('Category'.$id.' not found');
        }

        $sort = $this->articleRepository->normalizeSort((string)($_GET['sort'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));

        $totalArticles = $this->articleRepository->countArticlesByCategoryId($id);
        $paginator = new Paginator($totalArticles, $this->articlesPerPage, $page);


        return $this->view->render('category.tpl', [
            'category' => $category,
            'articles' => $this->articleRepository->articlesByCategoryId(
                $id,
                $sort,
                $this->articlesPerPage,
                $paginator->offset()
            ),
            'paginator' => $paginator->toArray(),
            'sort' => $sort,
        ]);
    }
}