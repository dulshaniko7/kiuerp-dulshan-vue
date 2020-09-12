<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        "country_name", "country_code", "country_code_alt", "calling_code", "currency_code", "citizenship"
    ];

    protected $primaryKey = "country_id";

    public $timestamps = false;
}
