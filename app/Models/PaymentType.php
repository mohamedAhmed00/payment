<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'key'];

    public $timestamps = false;

    public function suppliers(){
        return $this->hasMany(Supplier::class);
    }
}
