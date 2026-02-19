<?php

declare(strict_types=1);

namespace App\Domain\DTO;

use App\Domain\Factory\INotificationFactory;
use Illuminate\Support\Facades\Hash;

class StoreUserDTO extends DataTransferObject
{
    public string $name;

    public string $email;

    public int $group_id;

    public int|null $organization_id;

    public string|null $password;

    public string|null $signature_key;

    public string|null $returning_url;

    public array|null $system_configuration;

    public static function fromRequest($request) : self
    {
        $authType = null;
        if (!empty($request->get('auth_type'))){
            $notification = resolve(INotificationFactory::class)->getSystemNotificationObject($request->get('auth_type'));
            $authType = $notification->prepareSystemConfiguration($request);
        }
        return new self([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'returning_url' => $request->get('returning_url'),
            'group_id' => (int) $request->get('group_id'),
            'signature_key' => $request->get('signature_key'),
            'organization_id' => empty(auth()->user()->organization) ? (int) $request->get('organization_id') : auth()->user()->organization->id,
            'password' => ! empty($request->get('new_password')) ? Hash::make($request->get('new_password')) : $request->user()->password,
            'system_configuration' => $authType
        ]);
    }
}
