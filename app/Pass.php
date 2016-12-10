<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pass extends Model
{
    protected $table = 'Pass';

    protected $primaryKey = 'PassID';
    public $timestamps = false;

    protected $hidden = ['TypePassID'];
    protected $guarded = ['PassID'];
    protected $fillable = ['TypePassID', 'Date', 'StartDate', 'ComfortClass'];

    protected $appends = ['TypePass'];

    public function getTypePassAttribute()
    {
        return TypePass::find($this->TypePassID);
    }
}
