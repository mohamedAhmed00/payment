<?php

namespace App\Domain\Rules;

use App\Exceptions\ApiCustomException;

class ValidatedUser extends  Validator
{
    protected function applyRules($builder){
        if (auth()->user()->id != $builder->user_id){
            throw new ApiCustomException(__('This user doesnt have permission to refund'));
        }
        return $builder;
    }

}
