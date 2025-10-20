<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBaganList extends Model
{
    protected $table = 'user_bagan_lists';
    protected $fillable = [
        'nama',
        'jabatan',
        'departemen',
        'image_path',
        'telephone',
        'email',
        'tanggal_lahir',
    ];
}
