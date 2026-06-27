<?php

namespace KaayDem\Models\Entities;

use KaayDem\Models\Enums\UserRole;
use KaayDem\Models\Interfaces\EvaluableInterface;

class User extends AbstractEntity implements EvaluableInterface
{
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $passwordHash;
    private UserRole $role;
    private bool $isDriverVerified = false;
    private ?string $studentId = null;
    private float $averageRating = 0;
    private int $totalRatings = 0;
    private array $ratings = [];
    
    // Getters
    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string { return $this->lastName; }
    public function getEmail(): string { return $this->email; }
    public function getPasswordHash(): string { return $this->passwordHash; }
    public function getRole(): UserRole { return $this->role; }
    public function isDriverVerified(): bool { return $this->isDriverVerified; }
    public function getStudentId(): ?string { return $this->studentId; }
    
    // Setters
    public function setFirstName(string $firstName): self { $this->firstName = $firstName; return $this; }
    public function setLastName(string $lastName): self { $this->lastName = $lastName; return $this; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function setPasswordHash(string $passwordHash): self { $this->passwordHash = $passwordHash; return $this; }
    public function setRole(UserRole $role): self { $this->role = $role; return $this; }
    public function setIsDriverVerified(bool $verified): self { $this->isDriverVerified = $verified; return $this; }
    public function setStudentId(?string $studentId): self { $this->studentId = $studentId; return $this; }
    
    // ===== AJOUT : Méthodes pour les timestamps =====
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    // ===== FIN AJOUT =====
    
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
    
    public function canPublishTrip(): bool
    {
        return $this->role->canPublish() && $this->isDriverVerified;
    }
    
    public function verifyDriver(): void
    {
        $this->isDriverVerified = true;
        $this->updateTimestamps();
    }
    
    // EvaluableInterface
    public function getAverageRating(): float { return $this->averageRating; }
    public function getTotalRatings(): int { return $this->totalRatings; }
    
    public function addRating(object $rating): void
    {
        $this->ratings[] = $rating;
        $this->totalRatings++;
        $this->updateAverageRating();
        $this->updateTimestamps();
    }
    
    private function updateAverageRating(): void
    {
        if ($this->totalRatings === 0) {
            $this->averageRating = 0;
            return;
        }
        $sum = array_reduce($this->ratings, fn($carry, $r) => $carry + $r->getStars(), 0);
        $this->averageRating = round($sum / $this->totalRatings, 1);
    }
    
    public function getRatingDistribution(): array
    {
        $distribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        foreach ($this->ratings as $rating) {
            $stars = $rating->getStars();
            if (isset($distribution[$stars])) {
                $distribution[$stars]++;
            }
        }
        return $distribution;
    }
}