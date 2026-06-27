<?php

namespace KaayDem\Models\Repositories;

use KaayDem\Models\Interfaces\RepositoryInterface;
use KaayDem\Models\Entities\User;
use KaayDem\Models\Enums\UserRole;
use KaayDem\Core\Database;
use PDO;

class UserRepository implements RepositoryInterface
{
    private PDO $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function find(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;
        return $this->hydrate($data);
    }
    
    public function findAll(array $filters = []): array
    {
        $sql = "SELECT * FROM users";
        $conditions = [];
        $params = [];
        
        if (!empty($filters['role'])) {
            $conditions[] = "role = :role";
            $params['role'] = $filters['role'];
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'hydrate'], $results);
    }
    
    public function save(object $entity): bool
    {
        if (!$entity instanceof User) {
            throw new \InvalidArgumentException('Entity must be User instance');
        }
        
        if ($entity->getId()) {
            return $this->update($entity);
        }
        return $this->insert($entity);
    }
    
    private function insert(User $user): bool
    {
        $sql = "INSERT INTO users (first_name, last_name, email, password_hash, role, is_driver_verified, student_id, created_at, updated_at) 
                VALUES (:first_name, :last_name, :email, :password_hash, :role, :is_driver_verified, :student_id, :created_at, :updated_at)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password_hash' => $user->getPasswordHash(),
            'role' => $user->getRole()->value,
            'is_driver_verified' => $user->isDriverVerified() ? 1 : 0,
            'student_id' => $user->getStudentId(),
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s')
        ]);
    }
    
    private function update(User $user): bool
    {
        $sql = "UPDATE users SET 
                first_name = :first_name, 
                last_name = :last_name, 
                email = :email, 
                password_hash = :password_hash, 
                role = :role, 
                is_driver_verified = :is_driver_verified, 
                student_id = :student_id, 
                updated_at = :updated_at 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $user->getId(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password_hash' => $user->getPasswordHash(),
            'role' => $user->getRole()->value,
            'is_driver_verified' => $user->isDriverVerified() ? 1 : 0,
            'student_id' => $user->getStudentId(),
            'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s')
        ]);
    }
    
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    public function findOneBy(array $criteria): ?User
    {
        $sql = "SELECT * FROM users WHERE ";
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
        $sql = "SELECT COUNT(*) as count FROM users";
        $conditions = [];
        $params = [];
        
        if (!empty($filters['role'])) {
            $conditions[] = "role = :role";
            $params['role'] = $filters['role'];
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['count'];
    }
    
    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }
    
    // ===== NOUVELLE MÉTHODE AJOUTÉE =====
    public function findDrivers(): array
    {
        $sql = "SELECT * FROM users WHERE role IN ('driver', 'both')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'hydrate'], $results);
    }
    // ===== FIN NOUVELLE MÉTHODE =====
    
    private function hydrate(array $data): User
    {
        $user = new User();
        $user->setId((int) $data['id']);
        $user->setFirstName($data['first_name']);
        $user->setLastName($data['last_name']);
        $user->setEmail($data['email']);
        $user->setPasswordHash($data['password_hash']);
        $user->setRole(UserRole::from($data['role']));
        $user->setIsDriverVerified((bool) $data['is_driver_verified']);
        $user->setStudentId($data['student_id'] ?? null);
        $user->setCreatedAt(new \DateTime($data['created_at']));
        $user->setUpdatedAt(new \DateTime($data['updated_at']));
        return $user;
    }
}