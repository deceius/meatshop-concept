<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
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


    public function getTraderNamesAttribute(){
        $traderIds = explode(',', str_replace(array('[',']'),'',$this->agent_ids));
        $traders = Trader::whereIn('id', $traderIds)->get();
        return collect($traders)->pluck('trader_name')->toArray();
    }

}
