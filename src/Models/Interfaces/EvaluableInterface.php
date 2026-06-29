<?php

namespace KaayDem\Models\Interfaces;

interface EvaluableInterface
{
    public function getAverageRating(): float;
    public function getTotalRatinf(): int;
    public function addRatings(): void;
    public function getRatingDistribution(): array;
}