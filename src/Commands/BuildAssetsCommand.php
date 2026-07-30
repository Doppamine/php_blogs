<?php

declare(strict_types=1);

namespace App\Commands;

use Exception;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Exception\SassException;
use ScssPhp\ScssPhp\OutputStyle;

class BuildAssetsCommand
{
    private string $entry;
    private string $output;

    public function __construct(string $entry, string $output)
    {
        $this->entry = $entry;
        $this->output = $output;
    }

    /**
     * @throws Exception
     * @throws SassException
     */
    public function run(): int
    {
        $source = file_get_contents($this->entry);
        if ($source === false) {
            throw new Exception("Failed to read SCSS entry file: {$this->entry}");
        }
        $compiler = new Compiler();
        $compiler->setImportPaths(dirname($this->entry));
        $compiler->setOutputStyle(OutputStyle::EXPANDED);
        $css = $compiler->compileString($source)->getCss();
        file_put_contents($this->output, $css);
        printf("Build assets \"%s\"\n", $this->entry);
        return 0;
    }
}