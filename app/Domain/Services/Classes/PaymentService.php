<?php

declare(strict_types=1);

namespace App\Domain\Services\Classes;

use App\Domain\DTO\TransactionDTO;
use App\Domain\Factory\IPaymentSupplierFactory;
use App\Domain\Factory\IPaymentTypeFactory;
use App\Domain\Repositories\Interfaces\ITransactionRepository;
use App\Domain\Rules\ValidatedCurrencyPayment;
use App\Domain\Rules\ValidatedOnlinePayment;
use App\Domain\Rules\ValidatedOrganization;
use App\Domain\Rules\ValidatedPaymentTypeSettings;
use App\Domain\Rules\ValidatedRefundPayment;
use App\Domain\Rules\ValidatedUser;
use App\Domain\Services\Interfaces\IPaymentService;
use App\Jobs\OrganizationNotifyWithTransactionUpdatesJob;
use Illuminate\Routing\Pipeline;

class PaymentService implements IPaymentService
{
    public function __construct(
        private readonly IPaymentTypeFactory $paymentTypeFactory,
        private readonly IPaymentSupplierFactory $paymentSupplierFactory,
        private readonly ITransactionRepository $transactionRepository
    )
    {
    }

    public function pay(TransactionDTO $transaction)
    {
        app(Pipeline::class)
            ->send(auth()->user())
            ->through([
                ValidatedOrganization::class,
                ValidatedPaymentTypeSettings::class,
                ValidatedOnlinePayment::class,
                ValidatedCurrencyPayment::class,
            ])
            ->thenReturn()
            ->get();
        $paymentType = $this->paymentTypeFactory->getPaymentTypeObject(request('payment_type'));
        $response = $paymentType->pay($transaction);
        $response['signature'] = makeDigitalSignature($response, auth()->user()->signature_key);
        return $response;
    }

    public function refund(array $data)
    {
        $transaction = $this->transactionRepository->first(
            conditions:['transaction_id' => $data['transaction_id']],
            relations: ['paymentType', 'paymentMethod']
        );
        app(Pipeline::class)
            ->send($transaction)
            ->through([
                ValidatedOrganization::class,
                ValidatedUser::class,
                ValidatedRefundPayment::class
            ])
            ->thenReturn()
            ->get();

        $paymentType = $this->paymentTypeFactory->getPaymentTypeObject($transaction->paymentType->key);
        $response = $paymentType->refund($transaction, $data);
        $response['signature'] = makeDigitalSignature($response, auth()->user()->signature_key);
        return $response;
    }

    public function returning(array $data)
    {
        $supplier = $this->paymentSupplierFactory->getSupplierObject($data['supplier']);
        return $supplier->returning()->execute($data);
    }

    public function callback(array $data): void
    {
        $supplier = $this->paymentSupplierFactory->getSupplierObject($data['supplier']);
        $payload = $supplier->callback()->execute($data);
        if (!empty($payload['user']->system_configuration)){
            OrganizationNotifyWithTransactionUpdatesJob::dispatch($payload);
        }
    }

    public function renderIframe(array $data)
    {
        $supplier = resolve(IPaymentSupplierFactory::class)->getSupplierObject($data['supplier']);
        return $supplier->renderIFrame()->execute($data);
    }
}
