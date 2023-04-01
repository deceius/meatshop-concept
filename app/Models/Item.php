<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'brand_id',
        'type_id',
        'created_by',
        'updated_by',

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/app/items/'.$this->getKey());
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function type() {
        return $this->belongsTo(Type::class);
    }
}
