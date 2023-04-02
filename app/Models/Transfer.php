<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = [
        'pullout_transaction_id',
        'delivery_transaction_id',
        'delivery_branch_id',
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
        return url('app/transfers/'.$this->getKey());
    }
    public function deliveryBranch() {
        return $this->belongsTo(Branch::class);
    }
    public function pulloutTransaction() {
        return $this->belongsTo(TransactionHeader::class);
    }

    public function deliveryTransaction() {
        return $this->belongsTo(TransactionHeader::class);
    }
}
