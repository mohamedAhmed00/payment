<?php

namespace App\Domain\Rules;

use App\Exceptions\ApiCustomException;

class ValidatedInvoicePayment extends  Validator
{
    protected function applyRules($builder){
        if (request('payment_method') == 'invoice'){
            $price = 0;
            $request = request()->all();
            foreach ($request['invoice']['line_items'] as $key => $item){
                $unitCost = $item['unit_cost'] * $item['quantity'];
                if ($unitCost != $item['net_total']){
                    throw new ApiCustomException(__('Item :ID : Cost x Quantity does not match net total', ['ID' => $key + 1 ]));
                }
                if (($unitCost - $item['discount_amount'] + $item['tax_total']) != $item['total']){
                    throw new ApiCustomException(__('Item :ID : Total does not match items - discount + tax', ['ID' => $key + 1 ]));
                }
                $price += $unitCost;
            }
            if ($price != $request['amount']){
                throw new ApiCustomException(__('Invoice total does not match total'));
            }
        }
        return $builder;
    }

}
