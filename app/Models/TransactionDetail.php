<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'ref_no',
        'transaction_header_id',
        'item_id',
        'qr_code',
        'quantity',
        'selling_price',
        'amount',
        'sale_type',
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
        return url('/app/transaction-details/'.$this->getKey());
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function transactionHeader() {
        return $this->belongsTo(TransactionHeader::class);
    }
}
