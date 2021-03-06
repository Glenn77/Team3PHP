<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'Discount';

    protected $primaryKey = 'DiscountID';
    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = ['DiscountID'];
    protected $fillable = ['Name', 'Amount', 'LastUpdated'];
}
