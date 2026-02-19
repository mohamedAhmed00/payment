<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'tax_number', 'address', 'email', 'logo', 'status'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function paymentTypes()
    {
        return $this->belongsToMany(PaymentType::class, 'organization_payment_types');
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'organization_suppliers')->select('*');
    }

    public function paymentMethod()
    {
        return $this->belongsToMany(PaymentMethod::class, 'organization_payment_methods');
    }

    /**
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'organization_id');
    }
}
