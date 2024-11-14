<?php

namespace App\Application;

class PageLoader {
    private string $layout = 'main';
    private string $page;

    /**
     * Set layout rendered on the webpage
     * @param string $layoutName
     */
    public function setLayout(string $layoutName): self {
        $this->layout = $layoutName;
        return $this;
    }
    /**
     * Set view rendered on the webpage
     * @param string $pageName
     */
    public function setPage(string $pageName): self {
        $this->page = $pageName;
        return $this;
    }

    /**
     * Return the rendered webpage
     * @param array $parameters
     * @return string
     */
    public function render(array $parameters = []): string {
        $layout = $this->loadView('layouts/'.$this->layout, $parameters);
        $page = $this->loadView('pages/'.$this->page, $parameters);

        return str_replace('{{content}}', $page, $layout);
    }

    /**
     * Load a view file and return its content
     * @param string $pageName
     */
    private function loadView(string $pageName, array $parameters): string {
        foreach ($parameters as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once __DIR__ . "/../../resources/views/{$pageName}.php";
        return ob_get_clean();
    }
}
