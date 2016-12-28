<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RouteWithStation extends Model
{
    protected $table = 'Route';

    protected $primaryKey = 'RouteID';
    public $timestamps = false;
    public $incrementing = false;

    protected $hidden = ['DepartureStationID', 'ArrivalStationID'];
    protected $guarded = ['RouteID'];
    protected $fillable = ['DepartureStationID', 'ArrivalStationID', 'LastUpdated'];

    protected $appends = ['DepartureStation', 'ArrivalStation'];

    public function getDepartureStationAttribute()
    {
        return Station::find($this->DepartureStationID);
    }

    public function getArrivalStationAttribute()
    {
        return Station::find($this->ArrivalStationID);
    }
}
