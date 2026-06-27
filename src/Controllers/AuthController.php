<?php

namespace KaayDem\Controllers;

use KaayDem\Core\Controller;
use KaayDem\Models\Repositories\UserRepository;
use KaayDem\Models\Entities\User;
use KaayDem\Models\Enums\UserRole;

class AuthController extends Controller
{
    private UserRepository $userRepository;
    
    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }
    
    public function showLogin(): void
    {
        $this->render('auth/login');
    }
    
    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $user = $this->userRepository->findByEmail($email);
        
        if ($user && password_verify($password, $user->getPasswordHash())) {
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_name'] = $user->getFullName();
            $_SESSION['user_role'] = $user->getRole()->value;
            $this->redirect('/kaay_dem/dashboard');
        } else {
            $_SESSION['flash_message'] = 'Email ou mot de passe incorrect';
            $_SESSION['flash_type'] = 'error';
            $this->redirect('/kaay_dem/login');
        }
    }
    
    public function showRegister(): void
    {
        $this->render('auth/register');
    }
    
    public function register(): void
    {
        $user = new User();
        $user->setFirstName($_POST['first_name']);
        $user->setLastName($_POST['last_name']);
        $user->setEmail($_POST['email']);
        $user->setPasswordHash(password_hash($_POST['password'], PASSWORD_DEFAULT));
        $user->setRole(UserRole::from($_POST['role']));
        $user->setStudentId($_POST['student_id'] ?? null);
        $user->setTimestamps();
        
        if ($this->userRepository->save($user)) {
            $_SESSION['flash_message'] = 'Inscription réussie ! Connectez-vous.';
            $_SESSION['flash_type'] = 'success';
            $this->redirect('/kaay_dem/login');
        } else {
            $_SESSION['flash_message'] = 'Erreur lors de l\'inscription';
            $_SESSION['flash_type'] = 'error';
            $this->redirect('/kaay_dem/register');
        }
    }
    
    public function logout(): void
    {
        session_destroy();
        $this->redirect('/kaay_dem');
    }
}