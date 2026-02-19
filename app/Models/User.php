<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'returning_url',
        'group_id',
        'organization_id',
        'organization_supplier_id',
        'signature_key',
        'system_configuration'
    ];

    protected $hidden = [
        'password',
        'signature_key',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'system_configuration' => 'array'
    ];

    public function group() : BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function organization() : BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function generateToken()
    {
        return $this->createToken('oauth-client-test')->accessToken;
    }

    public function paymentTypes() {
        return $this->belongsToMany(PaymentType::class, 'user_payment_types');
    }

    public function supplierSettings() {
        return $this->belongsTo(OrganizationSupplier::class, 'organization_supplier_id')->select('*');
    }

    public function paymentMethod() {
        return $this->belongsToMany(PaymentMethod::class, 'user_payment_methods');
    }

    /**
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }
}
