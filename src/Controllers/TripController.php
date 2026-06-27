<?php

namespace KaayDem\Controllers;

use KaayDem\Core\Controller;
use KaayDem\Models\Repositories\TripRepository;
use KaayDem\Models\Entities\Trip;
use KaayDem\Models\Enums\TripStatus;

class TripController extends Controller
{
    private TripRepository $tripRepository;
    
    public function __construct()
    {
        parent::__construct();
        $this->tripRepository = new TripRepository();
    }
    
    public function index(): void
    {
        $filters = ['status' => TripStatus::ACTIVE->value];
        
        if (isset($_GET['departure']) && !empty($_GET['departure'])) {
            $filters['departure'] = $_GET['departure'];
        }
        if (isset($_GET['arrival']) && !empty($_GET['arrival'])) {
            $filters['arrival'] = $_GET['arrival'];
        }
        
        $trips = $this->tripRepository->findAll($filters);
        $this->render('trips/index', ['trips' => $trips]);
    }
    
    public function show(int $id): void
    {
        $trip = $this->tripRepository->find($id);
        if (!$trip) {
            $_SESSION['flash_message'] = 'Trajet non trouvé';
            $_SESSION['flash_type'] = 'error';
            $this->redirect('/kaay_dem/trips');
        }
        $this->render('trips/show', ['trip' => $trip]);
    }
    
    public function create(): void
    {
        $this->requireRole('driver');
        $this->render('trips/create');
    }
    
    public function store(): void
    {
        $this->requireRole('driver');
        
        $trip = new Trip();
        $trip->setDriverId($this->getUserId());
        $trip->setDepartureCity($_POST['departure_city']);
        $trip->setArrivalCity($_POST['arrival_city']);
        $trip->setDepartureTime(new \DateTime($_POST['departure_time']));
        $trip->setAvailableSeats((int)$_POST['available_seats']);
        $trip->setPricePerSeat((float)$_POST['price_per_seat']);
        $trip->setStatus(TripStatus::ACTIVE);
        $trip->setStopPoints(explode(',', $_POST['stop_points'] ?? ''));
        $trip->setTimestamps();
        
        if ($this->tripRepository->save($trip)) {
            $_SESSION['flash_message'] = 'Trajet publié avec succès !';
            $_SESSION['flash_type'] = 'success';
        }
        $this->redirect('/kaay_dem/trips');
    }
}