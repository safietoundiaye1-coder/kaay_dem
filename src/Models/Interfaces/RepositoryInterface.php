<?php

namespace KaayDem\Models\Interfaces;

interface RepositoryInterface
{
    public function find(int $id): ?object;
    public function findAll(array $filters = []): array;
    public function save(object $entity): bool;
    public function delete(int $id): bool;
    public function findOneBy(array $criteria): ?object;
    public function count(array $filters = []): int;
}