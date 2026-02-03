<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualCardProviderCurrency extends Model
{
    use HasFactory;

    protected $guarded = [''];

    protected $casts = [
        'id'                        => 'integer',
        'provider_id'               => 'integer',
        'name'                      => 'string',
        'currency_code'             => 'string',
        'currency_symbol'           => 'string',
        'image'                     => 'string',
        'fees'                      => 'object',
        'min_limit'                 => 'decimal:16',
        'max_limit'                 => 'decimal:16',
        'percent_charge'            => 'decimal:16',
        'fixed_charge'              => 'decimal:16',
        'rate'                      => 'decimal:16',
        'status'                    => 'integer',
        'created_at'                => 'date:Y-m-d',
        'updated_at'                => 'date:Y-m-d',
    ];


    public function active($query)
    {
        return $query->where('status',1);
    }

}
