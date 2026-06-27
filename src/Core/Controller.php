<?php

namespace KaayDem\Core;

abstract class Controller
{
    protected View $view;
    
    public function __construct()
    {
        $this->view = new View();
    }
    
    protected function render(string $view, array $data = []): void
    {
        $this->view->render($view, $data);
    }
    
    protected function redirect(string $url): void
    {
        header("Location: " . $url);
        exit;
    }
    
    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }
    
    protected function getUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }
    
    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/kaay_dem/login');
        }
    }
    
    protected function requireRole(string $role): void
    {
        $this->requireAuth();
        if ($_SESSION['user_role'] !== $role && $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/kaay_dem');
        }
    }
}