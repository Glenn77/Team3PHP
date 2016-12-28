<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    protected $table = 'Line';

    protected $primaryKey = 'LineID';
    public $timestamps = false;
    public $incrementing = false;

    protected $hidden = ['RouteID'];
    protected $guarded = ['LineID'];
    protected $fillable = ['RouteID', 'TrainType', 'LastUpdated'];

    protected $appends = ['Route'];

    public function getRouteAttribute()
    {
        return Route::find($this->RouteID);
    }
}
