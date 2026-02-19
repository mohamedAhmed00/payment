<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'url',
        'route_name',
        'method',
        'ip',
        'agent',
        'user_id',
        'organization_id',
    ];

    /**
     * @var string[]
     */
    protected $with = ['user', 'organization'];

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function organization() : BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
