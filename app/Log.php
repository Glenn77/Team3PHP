<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'Log';

    protected $primaryKey = 'LogID';
    public $timestamps = false;

    protected $guarded = ['LogID'];
    protected $fillable = ['CreatedAT', 'LogOrigin', 'LogMessage'];
}
