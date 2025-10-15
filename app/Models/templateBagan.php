<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class templateBagan extends Model
{
    protected $table = 'template_bagans';
    protected $fillable = [
        'name',
        'template',
    ];
}
