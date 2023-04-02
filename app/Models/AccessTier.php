<?php

namespace App\Models;

use Brackets\AdminAuth\Models\AdminUser;
use Illuminate\Database\Eloquent\Model;

class AccessTier extends Model
{
    protected $fillable = [
        'tier_id',
        'user_id',
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
        return url('app/access-tiers/'.$this->getKey());
    }

    public function user() {
        return $this->belongsTo(AdminUser::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}
