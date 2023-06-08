<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'address',
        'tin',
        'agent_ids',
        'created_by',
        'updated_by',

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url', 'trader_names'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('app/customers/'.$this->getKey());
    }

    public function transaction_headers() {
        return $this->hasMany(TransactionHeader::class);
    }
    public function getTraderNamesAttribute(){
        $traderIds = explode(',', str_replace(array('[',']'),'',$this->agent_ids));
        $traders = Trader::whereIn('id', $traderIds)->get();
        return implode (", ", collect($traders)->pluck('trader_name')->toArray());
        
    }

}
