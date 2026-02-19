<?php

namespace App\Http\Controllers;

use App\Domain\Repositories\Interfaces\IOrganizationRepository;
use App\Domain\Repositories\Interfaces\IPaymentTypeRepository;
use App\Domain\Repositories\Interfaces\ISupplierRepository;
use App\Domain\Services\Interfaces\IOrganizationService;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\Organization;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{

    public function __construct(
        private readonly IOrganizationRepository $organizationRepository,
        private readonly IOrganizationService    $organizationService,
        private readonly IPaymentTypeRepository  $paymentTypeRepository,
        private readonly ISupplierRepository     $supplierRepository
    )
    {
    }

    public function index()
    {
        $this->authorize('viewAny', [Organization::class]);
        $condition = [];
        if (!empty(auth()->user()->organization_id)) {
            $condition = ['id' => auth()->user()->organization_id];
        }
        return view('dashboard.organization.index', ['organizations' => $this->organizationRepository->listAllPaginate($condition)]);
    }

    public function create()
    {
        return view('dashboard.organization.create', [
            'suppliers' => $this->supplierRepository->listAllBy(),
            'paymentTypes' => $this->paymentTypeRepository->listAllBy(relations: ['suppliers', 'suppliers.paymentMethods'])
        ]);
    }

    public function show(Organization $organization)
    {
        $this->authorize('view', [$organization]);
        return view('dashboard.organization.show', ['organization' => $organization]);
    }

    public function store(StoreOrganizationRequest $request)
    {
        $this->organizationService->createOrganization($request->validated());
        return response()->json(['message' => __('Created successfully'), 'data' => []], 201);
    }

    public function edit(Organization $organization)
    {
        if ($organization?->logo){
            $organization->logo = Storage::disk('public')->url($organization?->logo);
        }
        $this->authorize('update', [$organization]);
        return view('dashboard.organization.edit', [
            'suppliers' => $this->supplierRepository->listAllBy(),
            'organization' => $organization,
            'paymentTypes' => $this->paymentTypeRepository->listAllBy(relations: ['suppliers', 'suppliers.paymentMethods'])
        ]);
    }

    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        $this->authorize('update', [$organization]);

        $this->organizationService->updateOrganization($organization->id, $request->validated());
    }

    /**
     * @throws AuthorizationException
     */
    public function listOrganizationTransactions(Organization $organization): Factory|View|Application
    {
        $this->authorize('view', [$organization]);
        return view('dashboard.organization.list_transactions', [
            'transactions' => $organization->transactions()->with([
                'paymentType',
                'paymentMethod',
                'user',
                'statuses' => fn($query) => $query->orderBy('id', 'DESC')
            ])->paginate(),
            'organization_id' => $organization->id
        ]);
    }
}
