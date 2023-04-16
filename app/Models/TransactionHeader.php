<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionHeader extends Model
{
    protected $fillable = [
        'ref_no',
        'transaction_type_id',
        'branch_id',
        'transaction_date',
        'received_by',
        'delivered_by',
        'remarks',
        'customer_id',
        'customer_category',
        'payment_id',
        'created_by',
        'updated_by',
        'status',
        'payment_account_name',
        'payment_ref_no'
    ];

    protected $dates = [
        'transaction_date',
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/app/transaction-headers/'.$this->getKey());
    }


    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function transaction_details() {
        return $this->hasMany(TransactionDetail::class);
    }


}
