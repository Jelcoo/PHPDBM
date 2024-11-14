<?php

namespace App\Application;

class PageLoader
{
    private string $layout = 'main';
    private string $page;

    public function setLayout(string $layoutName): self
    {
        $this->layout = $layoutName;

        return $this;
    }

    public function setPage(string $pageName): self
    {
        $this->page = $pageName;

        return $this;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function render(array $parameters = []): string
    {
        $layout = $this->loadView('layouts/' . $this->layout, $parameters);
        $page = $this->loadView('pages/' . $this->page, $parameters);

        return str_replace('{{content}}', $page, $layout);
    }

    /**
     * @param array<string, mixed> $parameters
     */
    private function loadView(string $pageName, array $parameters): string
    {
        foreach ($parameters as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once __DIR__ . "/../../resources/views/{$pageName}.php";

        return ob_get_clean();
    }
}
