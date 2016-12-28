<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $table = 'Route';

    protected $primaryKey = 'RouteID';
    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = ['RouteID'];
    protected $fillable = ['DepartureStationID', 'ArrivalStationID', 'LastUpdated'];

}
