<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlinkChartEmbed extends Model
{
    protected $table = 'slink_chart_embeds';

    protected $fillable = [
        'bagan_id',
        'label',
        'from',
        'to',
        'template',
    ];
}
