<?php

namespace KaayDem\Controllers;

use KaayDem\Core\Controller;
use KaayDem\Models\Repositories\UserRepository;
use KaayDem\Models\Repositories\TripRepository;

class AdminController extends Controller
{
    private UserRepository $userRepository;
    private TripRepository $tripRepository;
    
    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->tripRepository = new TripRepository();
    }
    
    public function drivers(): void
    {
        $this->requireRole('admin');
        
        $drivers = $this->userRepository->findDrivers();
        
        // Convertir les objets User en tableaux
        $driversArray = [];
        foreach ($drivers as $driver) {
            $driversArray[] = [
                'id' => $driver->getId(),
                'first_name' => $driver->getFirstName(),
                'last_name' => $driver->getLastName(),
                'email' => $driver->getEmail(),
                'is_driver_verified' => $driver->isDriverVerified(),
                'vehicle_model' => 'Non renseigné',
                'vehicle_plate' => 'Non renseigné'
            ];
        }
        
        $this->render('admin/drivers', ['drivers' => $driversArray]);
    }
    
    public function reports(): void
    {
        $this->requireRole('admin');
        
        $reports = [];
        
        $this->render('admin/reports', ['reports' => $reports]);
    }
    
    public function statistics(): void
    {
        $this->requireRole('admin');
        
        $data = [
            'totalUsers' => $this->userRepository->count(),
            'totalTrips' => $this->tripRepository->count(),
            'totalReservations' => 0,
            'occupancyRate' => 0,
            'topDrivers' => []
        ];
        
        $this->render('admin/statistics', $data);
    }
    
    public function validateDriver(int $id): void
    {
        $this->requireRole('admin');
        
        $user = $this->userRepository->find($id);
        if ($user) {
            $user->setIsDriverVerified(true);
            $user->updateTimestamps();
            $this->userRepository->save($user);
            $_SESSION['flash_message'] = 'Conducteur validé avec succès !';
            $_SESSION['flash_type'] = 'success';
        }
        
        $this->redirect('/kaay_dem/admin/drivers');
    }
    
    public function suspendDriver(int $id): void
    {
        $this->requireRole('admin');
        
        $user = $this->userRepository->find($id);
        if ($user) {
            $user->setIsDriverVerified(false);
            $user->updateTimestamps();
            $this->userRepository->save($user);
            $_SESSION['flash_message'] = 'Conducteur suspendu !';
            $_SESSION['flash_type'] = 'warning';
        }
        
        $this->redirect('/kaay_dem/admin/drivers');
    }
    
    public function resolveReport(int $id): void
    {
        $this->requireRole('admin');
        $_SESSION['flash_message'] = 'Signalement résolu !';
        $_SESSION['flash_type'] = 'success';
        $this->redirect('/kaay_dem/admin/reports');
    }
    
    public function dismissReport(int $id): void
    {
        $this->requireRole('admin');
        $_SESSION['flash_message'] = 'Signalement rejeté !';
        $_SESSION['flash_type'] = 'info';
        $this->redirect('/kaay_dem/admin/reports');
    }
}