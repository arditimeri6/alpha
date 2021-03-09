<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'bill_number', 'date_of_invoice', 'object', 'amount', 'owner'
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
