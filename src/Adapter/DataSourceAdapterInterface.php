<?php declare(strict_types=1);

namespace App\Adapter;

use App\Entity\User;

interface DataSourceAdapterInterface
{
    public function get(int $id): User;
    public function getAll(): array;
    public function add(array $payload): User;
    public function update(array $payload): User;
    public function delete(array $payload): bool;

}