<?php

namespace App\Domain\Notification;

use App\Exceptions\PaymentException;

class Token extends INotification
{

    public function prepareSystemConfiguration($request)
    {
        return [
            'auth_type' => $request->get('auth_type'),
            'login_url' => $request->get('login_url'),
            'notification_url' => $request->get('notification_url'),
            'password' => $request->get('password'),
            'username' => $request->get('username'),
            'origin' => $request->get('origin')
        ];
    }

    /**
     * @return array
     * @throws PaymentException
     */
    public function buildHeader(): array
    {
        $this->header['Authorization'] = $this->generateToken();
        return $this->header;
    }


    /**
     * @return string
     * @throws PaymentException
     */
    private function generateToken(): string
    {
        $this->setCredentials();
        $this->setLoginUrl();
        $this->setHeader();
        $authResponse =  $this->authUserWithHisOrganization();
        return 'Bearer '. $authResponse->json()['data']['access_token'];
    }

    /**
     */
    private function setCredentials()
    {
        $this->credentials =  [
            'username' => $this->user->system_configuration['username'],
            'password' => $this->user->system_configuration['password'],
        ];
    }

    /**
     * @return void
     */
    private function setHeader(): void
    {
        $this->header = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Origin' => $this->user->system_configuration['origin']
        ];
    }
}
