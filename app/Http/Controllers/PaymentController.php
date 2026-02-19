<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\DTO\TransactionDTO;
use App\Domain\Services\Interfaces\IPaymentService;
use App\Domain\Services\Interfaces\ITelegramService;
use App\Http\Requests\CallbackRequest;
use App\Http\Requests\PayRequest;
use App\Http\Requests\RefundRequest;
use App\Http\Requests\RenderIFrameRequest;
use App\Http\Requests\ReturningRequest;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\RefundResource;

class PaymentController extends Controller
{

    public function __construct(private readonly IPaymentService $paymentService)
    {
    }

    public function pay(PayRequest $request): PaymentResource
    {
        return new PaymentResource($this->paymentService->pay(TransactionDTO::fromRequest($request->all())));
    }

    public function refund(RefundRequest $request): RefundResource
    {
        return new RefundResource($this->paymentService->refund($request->validated()));
    }

    public function returning(ReturningRequest $request){
        resolve(ITelegramService::class)->sendMessage('returning response:  ' . json_encode($request->all()));
        return $this->paymentService->returning($request->all());
    }

    public function callback(CallbackRequest $request): void
    {
        resolve(ITelegramService::class)->sendMessage('callback response:  ' . json_encode($request->all()));
        $this->paymentService->callback($request->all());
    }

    public function renderIframe(RenderIFrameRequest $request)
    {
        return $this->paymentService->renderIframe($request->validated());
    }
}
