<?php

namespace KaayDem\Models\Repositories;

use KaayDem\Models\Interfaces\RepositoryInterface;
use KaayDem\Models\Entities\Reservation;
use KaayDem\Models\Enums\ReservationStatus;
use KaayDem\Core\Database;
use PDO;

class ReservationRepository implements RepositoryInterface
{
    private PDO $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function find(int $id): ?Reservation
    {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }
        
        return $this->hydrate($data);
    }
    
    public function findAll(array $filters = []): array
    {
        $sql = "SELECT * FROM reservations";
        $conditions = [];
        $params = [];
        
        if (!empty($filters['status'])) {
            $conditions[] = "status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($filters['passenger_id'])) {
            $conditions[] = "passenger_id = :passenger_id";
            $params['passenger_id'] = $filters['passenger_id'];
        }
        
        if (!empty($filters['trip_id'])) {
            $conditions[] = "trip_id = :trip_id";
            $params['trip_id'] = $filters['trip_id'];
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'hydrate'], $results);
    }
    
    public function save(object $entity): bool
    {
        if (!$entity instanceof Reservation) {
            throw new \InvalidArgumentException('Entity must be Reservation instance');
        }
        
        if ($entity->getId()) {
            return $this->update($entity);
        }
        
        return $this->insert($entity);
    }
    
    private function insert(Reservation $reservation): bool
    {
        $sql = "INSERT INTO reservations (trip_id, passenger_id, seats, total_price, status, created_at, updated_at) 
                VALUES (:trip_id, :passenger_id, :seats, :total_price, :status, :created_at, :updated_at)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'trip_id' => $reservation->getTripId(),
            'passenger_id' => $reservation->getPassengerId(),
            'seats' => $reservation->getSeats(),
            'total_price' => $reservation->getTotalPrice(),
            'status' => $reservation->getStatus()->value,
            'created_at' => $reservation->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $reservation->getUpdatedAt()->format('Y-m-d H:i:s')
        ]);
    }
    
    private function update(Reservation $reservation): bool
    {
        $sql = "UPDATE reservations SET 
                seats = :seats, 
                total_price = :total_price, 
                status = :status, 
                updated_at = :updated_at 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $reservation->getId(),
            'seats' => $reservation->getSeats(),
            'total_price' => $reservation->getTotalPrice(),
            'status' => $reservation->getStatus()->value,
            'updated_at' => $reservation->getUpdatedAt()->format('Y-m-d H:i:s')
        ]);
    }
    
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM reservations WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    public function findOneBy(array $criteria): ?Reservation
    {
        $sql = "SELECT * FROM reservations WHERE ";
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
        
        if (!$data) {
            return null;
        }
        
        return $this->hydrate($data);
    }
    
    public function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) as count FROM reservations";
        $conditions = [];
        $params = [];
        
        if (!empty($filters['status'])) {
            $conditions[] = "status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($filters['passenger_id'])) {
            $conditions[] = "passenger_id = :passenger_id";
            $params['passenger_id'] = $filters['passenger_id'];
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int) $result['count'];
    }
    
    public function findByPassenger(int $passengerId): array
    {
        return $this->findAll(['passenger_id' => $passengerId]);
    }
    
    public function findByTrip(int $tripId): array
    {
        return $this->findAll(['trip_id' => $tripId]);
    }
    
    public function getStats(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
                FROM reservations";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    private function hydrate(array $data): Reservation
    {
        $reservation = new Reservation();
        $reservation->setId((int) $data['id']);
        $reservation->setTripId((int) $data['trip_id']);
        $reservation->setPassengerId((int) $data['passenger_id']);
        $reservation->setSeats((int) $data['seats']);
        $reservation->setTotalPrice((float) $data['total_price']);
        $reservation->setStatus(ReservationStatus::from($data['status']));
        $reservation->setCreatedAt(new \DateTime($data['created_at']));
        $reservation->setUpdatedAt(new \DateTime($data['updated_at']));
        
        return $reservation;
    }
}