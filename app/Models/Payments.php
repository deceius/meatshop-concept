<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $fillable = [
        'transaction_header_id',
        'payment_amount',
        'payment_date',
        'type',
        'account_name',
        'reference_number'

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url', 'amount'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/app/payments/'.$this->getKey());
    }

    public function getAmountAttribute(){
        return number_format($this->payment_amount, 2);
    }
}
