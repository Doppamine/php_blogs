<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Repositories\CategoryRepository;
use DateMalformedStringException;
use Smarty\Exception;

class HomeController
{
    private CategoryRepository $categoryRepository;
    private View $view;
    private int $latestPerCategory;

    public function __construct(CategoryRepository $categoryRepository, View $view, int $latestPerCategory)
    {
        $this->categoryRepository = $categoryRepository;
        $this->view = $view;
        $this->latestPerCategory = $latestPerCategory;
    }

    /**
     * @throws DateMalformedStringException
     * @throws Exception
     */
    public function index(): string
    {
        return $this->view->render(
            'home.tpl',
            ['categories' => $this->categoryRepository->findLatestPerCategory($this->latestPerCategory),]
        );
    }
}