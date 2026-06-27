<?php

namespace KaayDem\Core;

class View
{
    public function render(string $view, array $data = []): void
    {
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("Vue introuvable : $view");
        }
        
        extract($data);
        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        
        $layoutPath = __DIR__ . '/../Views/layouts/main.php';
        if (file_exists($layoutPath)) {
            include $layoutPath;
        } else {
            echo $content;
        }
    }
}