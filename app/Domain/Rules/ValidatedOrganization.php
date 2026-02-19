<?php

namespace App\Domain\Rules;

use App\Exceptions\ApiCustomException;

class ValidatedOrganization extends  Validator
{
    protected function applyRules($builder){
        if (empty(auth()->user()->organization_id)){
            throw new ApiCustomException(__('This user doesnt have organization'));
        }
        if (auth()->user()->organization->status != 1){
            throw new ApiCustomException(__('This organization is de-active, activate it first'));
        }
        return $builder;
    }

}
