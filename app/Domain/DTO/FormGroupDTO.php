<?php

declare(strict_types=1);

namespace App\Domain\DTO;

class FormGroupDTO extends DataTransferObject
{
    public string $name;

    public string $description;

    public int $level;

    /**
     * @param $request
     * @return FormGroupDTO
     */
    public static function fromRequest($request) : self
    {
        return new self([
            'name' => $request->get('name'),
            'description' => empty($request->get('description')) ? '' : $request->get('description'),
            'level' => (int) $request->get('level'),
        ]);
    }
}
