<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DotLinkEmbedChart extends Model
{
    protected $table = 'dot_link_embed_charts';

    protected $fillable = [
        'bagan_id',
        'label',
        'from',
        'to',
        'rootId',
        'template',
    ];
}
