<?php

namespace KaayDem\Models\Entities;

use KaayDem\Models\Enums\ReservationStatus;

class Reservation extends AbstractEntity
{
    private int $seats;
    private float $totalPrice;
    private ReservationStatus $status;
    private int $tripId;
    private int $passengerId;
    private array $statusHistory = [];
    
    public function getSeats(): int { return $this->seats; }
    public function getTotalPrice(): float { return $this->totalPrice; }
    public function getStatus(): ReservationStatus { return $this->status; }
    public function getTripId(): int { return $this->tripId; }
    public function getPassengerId(): int { return $this->passengerId; }
    public function getStatusHistory(): array { return $this->statusHistory; }
    
    public function setSeats(int $seats): self { $this->seats = $seats; return $this; }
    public function setTotalPrice(float $price): self { $this->totalPrice = $price; return $this; }
    public function setStatus(ReservationStatus $status): self { $this->status = $status; return $this; }
    public function setTripId(int $tripId): self { $this->tripId = $tripId; return $this; }
    public function setPassengerId(int $passengerId): self { $this->passengerId = $passengerId; return $this; }
    
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
    
    public function confirm(): void
    {
        if ($this->status === ReservationStatus::PENDING) {
            $this->setStatus(ReservationStatus::CONFIRMED);
        }
    }
    
    public function cancel(): void
    {
        if ($this->status !== ReservationStatus::COMPLETED) {
            $this->setStatus(ReservationStatus::CANCELLED);
        }
    }
    
    public function complete(): void
    {
        if ($this->status === ReservationStatus::CONFIRMED) {
            $this->setStatus(ReservationStatus::COMPLETED);
        }
    }
    
    public function canBeConfirmed(): bool
    {
        return $this->status === ReservationStatus::PENDING;
    }
}