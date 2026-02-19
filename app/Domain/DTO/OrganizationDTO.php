<?php

declare(strict_types=1);

namespace App\Domain\DTO;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrganizationDTO extends DataTransferObject
{

    public ?string $name;

    public ?string $phone;

    public ?string $tax_number;

    public ?string $address;

    public ?string $email;

    public ?string $status;

    public ?string $logo;

    public static function fromRequest(array $request): self
    {
        return new self([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'tax_number' => $request['tax_number'],
            'address' => $request['address'],
            'email' => $request['email'],
            'status' => $request['status'],
            'logo' => optional($request)['logo'] ?  self::prepareLogo($request['logo']) : null
        ]);
    }

    private static function prepareLogo($logo): string|null
    {
        if (self::is_base64($logo)){
            $img = preg_replace('/^data:image\/\w+;base64,/', '', $logo);
            $type = explode('/', explode(';', $logo)[0])[1];
            $logoName = Str::random(10).'.'.$type;
            $logoPath = 'organizations/'. $logoName;
            Storage::disk('public')->put($logoPath,  base64_decode($img));
            return $logoPath;
        }
        return null;

    }

    private static function is_base64($s)
    {
        return (bool) preg_match("/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).base64,.*/", $s);
    }
}
