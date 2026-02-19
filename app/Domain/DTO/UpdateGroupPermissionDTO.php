<?php

declare(strict_types=1);

namespace App\Domain\DTO;

/**
 * Class UpdateGroupPermissionDTO.
 *
 */
class UpdateGroupPermissionDTO extends DataTransferObject
{
    /**
     * @var array
     */
    public array $permissions;

    /**
     * @param $request
     * @return static
     */
    public static function fromRequest($request) : self
    {
        return new self([
            'permissions' => $request->get('permissions'),
        ]);
    }
}
