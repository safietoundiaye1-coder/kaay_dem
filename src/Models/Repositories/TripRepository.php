<?php

namespace KaayDem\Models\Repositories;

use KaayDem\Models\Interfaces\RepositoryInterface;
use KaayDem\Models\Entities\Trip;
use KaayDem\Models\Enums\TripStatus;
use KaayDem\Core\Database;
use PDO;

class TripRepository implements RepositoryInterface
{
    private PDO $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function find(int $id): ?Trip
    {
        $stmt = $this->db->prepare("SELECT * FROM trips WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;
        return $this->hydrate($data);
    }
    
    public function findAll(array $filters = []): array
    {
        $sql = "SELECT * FROM trips";
        $conditions = [];
        $params = [];
        
        if (!empty($filters['status'])) {
            $conditions[] = "status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($filters['departure'])) {
            $conditions[] = "departure_city LIKE :departure";
            $params['departure'] = '%' . $filters['departure'] . '%';
        }
        
        if (!empty($filters['arrival'])) {
            $conditions[] = "arrival_city LIKE :arrival";
            $params['arrival'] = '%' . $filters['arrival'] . '%';
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $sql .= " ORDER BY departure_time ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'hydrate'], $results);
    }
    
    public function save(object $entity): bool
    {
        if (!$entity instanceof Trip) {
            throw new \InvalidArgumentException('Entity must be Trip instance');
        }
        
        if ($entity->getId()) {
            return $this->update($entity);
        }
        return $this->insert($entity);
    }
    
    private function insert(Trip $trip): bool
    {
        $sql = "INSERT INTO trips (driver_id, departure_city, arrival_city, departure_time, available_seats, price_per_seat, stop_points, status, created_at, updated_at) 
                VALUES (:driver_id, :departure_city, :arrival_city, :departure_time, :available_seats, :price_per_seat, :stop_points, :status, :created_at, :updated_at)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'driver_id' => $trip->getDriverId(),
            'departure_city' => $trip->getDepartureCity(),
            'arrival_city' => $trip->getArrivalCity(),
            'departure_time' => $trip->getDepartureTime()->format('Y-m-d H:i:s'),
            'available_seats' => $trip->getAvailableSeats(),
            'price_per_seat' => $trip->getPricePerSeat(),
            'stop_points' => json_encode($trip->getStopPoints()),
            'status' => $trip->getStatus()->value,
            'created_at' => $trip->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $trip->getUpdatedAt()->format('Y-m-d H:i:s')
        ]);
    }
    
    private function update(Trip $trip): bool
    {
        $sql = "UPDATE trips SET 
                departure_city = :departure_city, 
                arrival_city = :arrival_city, 
                departure_time = :departure_time, 
                available_seats = :available_seats, 
                price_per_seat = :price_per_seat, 
                stop_points = :stop_points, 
                status = :status, 
                updated_at = :updated_at 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $trip->getId(),
            'departure_city' => $trip->getDepartureCity(),
            'arrival_city' => $trip->getArrivalCity(),
            'departure_time' => $trip->getDepartureTime()->format('Y-m-d H:i:s'),
            'available_seats' => $trip->getAvailableSeats(),
            'price_per_seat' => $trip->getPricePerSeat(),
            'stop_points' => json_encode($trip->getStopPoints()),
            'status' => $trip->getStatus()->value,
            'updated_at' => $trip->getUpdatedAt()->format('Y-m-d H:i:s')
        ]);
    }
    
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM trips WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    public function findOneBy(array $criteria): ?Trip
    {
        $sql = "SELECT * FROM trips WHERE ";
        $conditions = [];
        $params = [];
        
        foreach ($criteria as $key => $value) {
            $conditions[] = "$key = :$key";
            $params[$key] = $value;
        }
        
        $sql .= implode(" AND ", $conditions);
        $sql .= " LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;
        return $this->hydrate($data);
    }
    
    public function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) as count FROM trips";
        $conditions = [];
        $params = [];
        
        if (!empty($filters['status'])) {
            $conditions[] = "status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['count'];
    }
    
    private function hydrate(array $data): Trip
    {
        $trip = new Trip();
        $trip->setId((int) $data['id']);
        $trip->setDriverId((int) $data['driver_id']);
        $trip->setDepartureCity($data['departure_city']);
        $trip->setArrivalCity($data['arrival_city']);
        $trip->setDepartureTime(new \DateTime($data['departure_time']));
        $trip->setAvailableSeats((int) $data['available_seats']);
        $trip->setPricePerSeat((float) $data['price_per_seat']);
        $trip->setStopPoints(json_decode($data['stop_points'] ?? '[]', true));
        $trip->setStatus(TripStatus::from($data['status']));
        $trip->setCreatedAt(new \DateTime($data['created_at']));
        $trip->setUpdatedAt(new \DateTime($data['updated_at']));
        return $trip;
    }
}