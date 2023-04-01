<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trader extends Model
{
    protected $fillable = [
        'trader_name',
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
        return url('/admin/traders/'.$this->getKey());
    }
}
