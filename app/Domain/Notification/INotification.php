<?php

namespace App\Domain\Notification;

use App\Exceptions\PaymentException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

abstract class INotification
{
    private string $loginUrl;

    protected array $header = [];

    protected Model $user;

    protected array $credentials = [];

    protected array $system_configuration = [];

    abstract public function buildHeader(): array;

    abstract public function prepareSystemConfiguration($request);

    public function setUser(Model $user){
        $this->user = $user;
        $this->system_configuration = $this->user->system_configuration;
    }

    public function getNotificationUrl()
    {
        return $this->user->system_configuration['notification_url'];
    }

    protected function authUserWithHisOrganization(): PromiseInterface|Response
    {
        try {
            return Http::withHeaders(array_filter($this->header))->post($this->loginUrl, array_filter($this->credentials));
        } catch (Throwable $exception) {
            throw new PaymentException($exception->getMessage(), $exception->getCode());
        }
    }

    protected function setLoginUrl(): void
    {
        $this->loginUrl = $this->user->system_configuration['login_url'];
    }
}
