<?php

namespace App\Domain\Services\Classes;

use App\Domain\DTO\OrganizationDTO;
use App\Domain\Repositories\Interfaces\IOrganizationRepository;
use App\Domain\Services\Interfaces\IOrganizationService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrganizationService implements IOrganizationService
{
    public function __construct(private readonly IOrganizationRepository $organizationRepository)
    {
    }

    public function createOrganization(array $organization){
        DB::transaction(function () use ($organization) {
            $organizationObject = $this->organizationRepository->create(array_filter((array)OrganizationDTO::fromRequest($organization['organization'])));
            $organizationObject->paymentTypes()->sync($organization['payment_type']);
            $organizationObject->paymentMethod()->sync($organization['methods']);
            foreach ($organization['supplier_settings'] as $supplierSetting){
                $organizationObject->suppliers()->attach($supplierSetting['supplier_id'], ['settings' => json_encode($supplierSetting)]);
            }
        });
    }

    public function updateOrganization(int $id, array $organization){
        DB::transaction(function () use ($id, $organization) {
            $organizationObject = $this->organizationRepository->first(['id' => $id]);
            $organizationObject->fill(array_filter((array)OrganizationDTO::fromRequest($organization['organization'])));
            $organizationObject->save();
            $organizationObject->suppliers()->detach();
            $organizationObject->paymentTypes()->sync($organization['payment_type']);
            $organizationObject->paymentMethod()->sync($organization['methods']);
            foreach ($organization['supplier_settings'] as $supplierSetting){
                $organizationObject->suppliers()->attach($supplierSetting['supplier_id'], ['settings' => json_encode($supplierSetting)]);
            }
        });
    }

    public function getAllOrganizations() : Collection | array
    {
        return  $this->organizationRepository->listAllBy();
    }
}
