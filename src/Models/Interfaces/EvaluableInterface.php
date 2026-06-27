<?php

namespace KaayDem\Models\Interfaces;

interface EvaluableInterface
{
    public function getAverageRating(): float;
    public function getTotalRatings(): int;
    public function addRating(object $rating): void;
    public function getRatingDistribution(): array;
}