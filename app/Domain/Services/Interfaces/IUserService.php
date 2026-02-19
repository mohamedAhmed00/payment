<?php

declare(strict_types=1);

namespace App\Domain\Services\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface IUserService
{

    public function getUsersWithPagination(int|null $pageNumber): LengthAwarePaginator|null;

    public function storeUser(array $userData): array;

    public function updateProfile(int $id, array $data): array;

    public function deleteUser(int $id): array;

    public function getUserData(int $id): array;

    public function savePaymentSettings(int $userId, array $paymentSettings);
}
