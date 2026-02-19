<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrganizationSupplier extends Pivot
{
    protected $fillable = ['organization_id', 'supplier_id', 'settings'];

    protected $table = 'organization_suppliers';
}
