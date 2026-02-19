<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'key', 'payment_type_id'];

    public $timestamps = false;

    public function paymentMethods(){
        return $this->hasMany(PaymentMethod::class);
    }
}
