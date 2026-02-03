<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VirtualCardProviderCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VirtualCardApi extends Model
{
    use HasFactory;

    const TYPE_UNIVERSAL = 'universal';
    const TYPE_PLATINUM = 'platinum';
    protected $guarded = ['id'];

    protected $casts = [
        'admin_id' => 'integer',
        'provider_title' => 'string',
        'provider_image' => 'string',
        'provider_image' => 'string',
        'supported_currencies' => 'object',
        'config' => 'object',
        'card_details' => 'string',
        'card_limit' => 'integer',
        'status' => 'integer',
    ];


    public function currencies(){
        return $this->hasMany(VirtualCardProviderCurrency::class,'provider_id','id');
    }
}
