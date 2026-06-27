<?php

namespace KaayDem\Controllers;

use KaayDem\Core\Controller;
use KaayDem\Models\Repositories\TripRepository;
use KaayDem\Models\Repositories\ReservationRepository;
use KaayDem\Models\Repositories\UserRepository;

class DashboardController extends Controller
{
    private TripRepository $tripRepository;
    private ReservationRepository $reservationRepository;
    private UserRepository $userRepository;
    
    public function __construct()
    {
        parent::__construct();
        $this->tripRepository = new TripRepository();
        $this->reservationRepository = new ReservationRepository();
        $this->userRepository = new UserRepository();
    }
    
    public function index(): void
    {
        $this->requireAuth();
        
        $userId = $this->getUserId();
        
        $totalTrips = $this->tripRepository->count(['driver_id' => $userId]);
        $totalActive = $this->tripRepository->count(['driver_id' => $userId, 'status' => 'active']);
        
        $data = [
            'totalTrips' => $totalTrips,
            'totalActive' => $totalActive
        ];
        
        $this->render('dashboard/index', $data);
    }
    
    public function reservations(): void
    {
        $this->requireAuth();
        
        $userId = $this->getUserId();
        $trips = $this->tripRepository->findAll(['driver_id' => $userId]);
        $tripIds = array_map(function($trip) {
            return $trip->getId();
        }, $trips);
        
        $reservations = [];
        
        if (!empty($tripIds)) {
            foreach ($tripIds as $tripId) {
                $tripReservations = $this->reservationRepository->findByTrip($tripId);
                foreach ($tripReservations as $res) {
                    $passenger = $this->userRepository->find($res->getPassengerId());
                    $trip = $this->tripRepository->find($res->getTripId());
                    
                    $reservations[] = [
                        'id' => $res->getId(),
                        'passenger_name' => $passenger ? $passenger->getFullName() : 'Inconnu',
                        'seats' => $res->getSeats(),
                        'status' => $res->getStatus()->value,
                        'departure' => $trip ? $trip->getDepartureCity() : '',
                        'arrival' => $trip ? $trip->getArrivalCity() : '',
                        'created_at' => $res->getCreatedAt()->format('d/m/Y H:i')
                    ];
                }
            }
        }
        
        $this->render('dashboard/reservations', ['reservations' => $reservations]);
    }
}