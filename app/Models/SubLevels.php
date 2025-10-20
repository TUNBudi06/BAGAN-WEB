<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubLevels extends Model
{
    protected $fillable = [
        'bagan_list_id',
        'name',
        'value',
    ];
}
