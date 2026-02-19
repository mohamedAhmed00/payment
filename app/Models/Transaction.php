<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'currency', 'rate', 'action', 'services', 'customer', 'client_key', 'invoice',
        'payment_type_id', 'payment_method_id', 'user_id', 'transaction_reference', 'transaction_id', 'organization_id'];

    public function statuses(): BelongsToMany
    {
        return $this->belongsToMany(Status::class, 'transaction_status');
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
