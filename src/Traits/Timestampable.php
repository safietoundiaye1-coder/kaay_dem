<?php

namespace KaayDem\Traits;

trait Timestampable
{
    protected \DateTime $createdAt;
    protected \DateTime $updatedAt;
    
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
    
    public function setTimestamps(): void
    {
        $now = new \DateTime();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }
    
    public function updateTimestamps(): void
    {
        $this->updatedAt = new \DateTime();
    }
}