<?php

declare(strict_types=1);

namespace App\Core;

class Paginator
{
    private int $lastPage;
    private int $currentPage;
    private int $articlesPerPage;

    public function __construct(int $totalArticles, int $articlesPerPage, int $currentPage)
    {
        $this->articlesPerPage = $articlesPerPage;
        $this->lastPage = max(1, (int)ceil($totalArticles / $articlesPerPage));
        $this->currentPage = min(max(1, $currentPage), $this->lastPage);
    }

    public function offset(): int
    {
        return ($this->currentPage - 1) * $this->articlesPerPage;
    }

    public function toArray(): array
    {
        return [
            'current_page' => $this->currentPage,
            'last_page' => $this->lastPage,
            'pages' => range(1, $this->lastPage),
            'previous_page' => $this->currentPage > 1 ? $this->currentPage - 1 : null,
            'next_page' => $this->currentPage < $this->lastPage ? $this->currentPage + 1 : null,
        ];
    }
}