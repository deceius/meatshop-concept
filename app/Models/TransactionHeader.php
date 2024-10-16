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
        'is_paid',
        // 'payment_account_name',
        // 'payment_ref_no',
        'invoice_no',
        'sale_type'
    ];

    protected $dates = [
        'transaction_date',
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url', 'total_payments', 'balance', 'payment_data', 'balance_raw', 'total_cost'];

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

    public function getTotalPaymentsAttribute() {
        $payments = Payments::where('transaction_header_id', $this->getKey())->get();
        return number_format($payments ? $payments->sum('payment_amount') : 0, 2);
    }

    public function getTotalCostAttribute() {
        $amount = TransactionDetail::where('transaction_header_id', $this->getKey())->get();
        return number_format($amount->sum('selling_price'), 2);
    }

    public function getBalanceAttribute() {
        $amount = TransactionDetail::where('transaction_header_id', $this->getKey())->get();
        $payments = Payments::where('transaction_header_id', $this->getKey())->get();
        $balance = $amount->sum('selling_price') - ($payments ? $payments->sum('payment_amount') : 0);
        if ($balance >= 0) {
            return number_format($balance, 2);
        }
        else {
            return number_format(0, 2) . ' ('. number_format($balance, 2) . ')';
        }

    }

    public function getBalanceRawAttribute() {
        $amount = TransactionDetail::where('transaction_header_id', $this->getKey())->get();
        $payments = Payments::where('transaction_header_id', $this->getKey())->get();
        $balance = $amount->sum('selling_price') - ($payments ? $payments->sum('payment_amount') : 0);
        return round($balance > 0 ? $balance : 0, 2);
    }

    public function getPaymentDataAttribute() {
        $payments = Payments::where('transaction_header_id', $this->getKey())->get();
        return $payments;

    }

}
