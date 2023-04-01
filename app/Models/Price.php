<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'item_id',
        'box_amount',
        'cut_amount',
        'branch_id',
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
        return url('/app/prices/'.$this->getKey());
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}
