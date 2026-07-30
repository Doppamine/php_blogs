<?php

declare(strict_types=1);

namespace App\Core;

use Smarty\Exception;
use Smarty\Smarty;

class View
{
    private Smarty $smarty;

    public function __construct()
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir(__DIR__.'/../../templates');
        $this->smarty->setCompileDir(__DIR__.'/../../var/templates_c');
        $this->smarty->setCacheDir(__DIR__.'/../../var/cache');
        $this->smarty->setEscapeHtml(true);
    }

    /**
     * @throws Exception
     */
    public function render(string $template, array $data = []): string
    {
        $this->smarty->assign($data);
        return $this->smarty->fetch($template);
    }
}