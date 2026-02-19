<?php

namespace App\Domain\Services\Interfaces;

use Illuminate\Support\Collection;

interface IOrganizationService
{
    public function createOrganization(array $organization);

    public function updateOrganization(int $id, array $organization);

    public function getAllOrganizations() : Collection | array;
}
