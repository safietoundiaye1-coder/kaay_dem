<?php

namespace KaayDem\Models\Entities;

use KaayDem\Models\Enums\TripStatus;

class Trip extends AbstractEntity
{
    private string $departureCity;
    private string $arrivalCity;
    private \DateTime $departureTime;
    private int $availableSeats;
    private float $pricePerSeat;
    private array $stopPoints = [];
    private TripStatus $status;
    private int $driverId;
    private array $reservations = [];
    
    // Getters
    public function getDepartureCity(): string { return $this->departureCity; }
    public function getArrivalCity(): string { return $this->arrivalCity; }
    public function getDepartureTime(): \DateTime { return $this->departureTime; }
    public function getAvailableSeats(): int { return $this->availableSeats; }
    public function getPricePerSeat(): float { return $this->pricePerSeat; }
    public function getStopPoints(): array { return $this->stopPoints; }
    public function getStatus(): TripStatus { return $this->status; }
    public function getDriverId(): int { return $this->driverId; }
    public function getReservations(): array { return $this->reservations; }
    
    // Setters
    public function setDepartureCity(string $city): self { $this->departureCity = $city; return $this; }
    public function setArrivalCity(string $city): self { $this->arrivalCity = $city; return $this; }
    public function setDepartureTime(\DateTime $time): self { $this->departureTime = $time; return $this; }
    public function setAvailableSeats(int $seats): self { $this->availableSeats = $seats; return $this; }
    public function setPricePerSeat(float $price): self { $this->pricePerSeat = $price; return $this; }
    public function setStopPoints(array $points): self { $this->stopPoints = $points; return $this; }
    public function setStatus(TripStatus $status): self { $this->status = $status; return $this; }
    public function setDriverId(int $driverId): self { $this->driverId = $driverId; return $this; }
    
    // AJOUT : Méthodes pour les timestamps
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
    
    public function canBeModified(): bool
    {
        return $this->status === TripStatus::ACTIVE && empty($this->reservations);
    }
    
    public function reserveSeat(int $seats): bool
    {
        if ($this->availableSeats >= $seats && $this->status === TripStatus::ACTIVE) {
            $this->availableSeats -= $seats;
            $this->updateTimestamps();
            return true;
        }
        return false;
    }
    
    public function cancelTrip(): void
    {
        $this->status = TripStatus::CANCELLED;
        $this->updateTimestamps();
    }
    
    public function completeTrip(): void
    {
        $this->status = TripStatus::COMPLETED;
        $this->updateTimestamps();
    }
    
    public function getOccupancyRate(): float
    {
        $totalSeats = $this->availableSeats + $this->getReservedSeats();
        if ($totalSeats === 0) return 0;
        return round(($this->getReservedSeats() / $totalSeats) * 100, 2);
    }
    
    private function getReservedSeats(): int
    {
        $total = 0;
        foreach ($this->reservations as $reservation) {
            $total += $reservation->getSeats();
        }
        return $total;
    }
    
    public function isFull(): bool
    {
        return $this->availableSeats <= 0;
    }
}