<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Services\Interfaces\ITransactionService;
use App\Domain\Services\Interfaces\IUserPaymentSettingsService;
use App\Http\Resources\UserPaymentSettingResource;
use App\Http\Resources\UserTransactionsResource;

class PaymentSettingsController extends Controller
{

    public function __construct(
        private readonly IUserPaymentSettingsService $paymentSettingsService,
        private readonly ITransactionService $transactionService
    )
    {
    }

    public function userPaymentSettings(){
        return UserPaymentSettingResource::collection($this->paymentSettingsService->getUserPaymentSettingsForDashboard());
    }

    public function userTransactions($clientKey){
        return UserTransactionsResource::collection($this->transactionService->getTransactions($clientKey));
    }
}
