<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use League\Uri\UriTemplate\Template;

class LinkChartEmbed extends Model
{
    protected $table = 'link_chart_embeds';

    protected $fillable = [
        'bagan_list_id',
        'chart_id',
        'chart_pid',
        'chart_ppid',
        'chart_stpid',
        'template_bagan_id',
        'sub_level_id',
        'node_type',
        'type',
        'user_id',
        'label',
        'name',
        'nik',
        'team',
        'img',
    ];

    public function user(){
        return $this->belongsTo(UserBaganList::class, 'user_id', 'id');
    }

    public function getTemplateBagan(){
        return $this->belongsTo(TemplateBagan::class, 'template_bagan_id', 'id');
    }

    public function getSubLevel(){
        return $this->belongsTo(SubLevels::class, 'sub_level_id', 'id');
    }

    public function getNodeType(){
        return $this->belongsTo(TemplateBagan::class, 'node_type', 'id');
    }
}
