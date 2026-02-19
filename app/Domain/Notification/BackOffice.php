<?php

namespace App\Domain\Notification;

use App\Exceptions\PaymentException;

class BackOffice extends INotification
{
    public function prepareSystemConfiguration($request)
    {
        return [
            'auth_type' => $request->get('auth_type'),
            'login_url' => $request->get('login_url'),
            'notification_url' => $request->get('notification_url'),
            'agent' => $request->get('agent')
        ];
    }

    /**
     * @return array
     * @throws PaymentException
     */
    public function buildHeader(): array
    {
        $this->header['agent'] = $this->generateToken();
        return $this->header;
    }

    /**
     * @return string
     * @throws PaymentException
     */
    public function generateToken(): string
    {
        return md5($this->system_configuration['agent']);
    }
}
