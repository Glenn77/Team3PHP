<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RailCard extends Model
{
    protected $table = 'RailCard';

    protected $primaryKey = 'CardID';
    public $timestamps = false;
    public $incrementing = false;


    protected $guarded = ['CardID'];
    protected $fillable = ['LastUpdated'];
    protected $appends = ['Subscriptions'];

    public function getSubscriptionsAttribute()
    {
        return Subscription::where('RailCardID', '=', $this->CardID)->get();
    }

}
