<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CLinkChartEmbed extends Model
{
    protected $table = 'c_link_chart_embeds';

    protected $fillable = [
        'bagan_id',
        'label',
        'from',
        'to',
        'template',
    ];
}
