<?php

namespace App\Domain\Rules;

use App\Domain\Repositories\Interfaces\ITransactionRepository;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Cache;

class CheckIFrameDataInCache implements  InvokableRule
{


    public function __invoke($attribute, $value, $fail): void
    {
        $transaction = resolve(ITransactionRepository::class)->firstOrFail(conditions: ['transaction_id' => request('transaction_reference')]);
        if (! Cache::has($value) || $transaction->statuses->last()->key != 'processing') {
            $fail(__('Link is expired.'));
        }
    }
}
