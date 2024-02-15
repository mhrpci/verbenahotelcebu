<?php

namespace Botble\Widget\Models;

use Botble\Base\Models\BaseModel;

class Widget extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'widgets';

    /**
     * @var array
     */
    protected $fillable = [
        'widget_id',
        'sidebar_id',
        'theme',
        'position',
        'data',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'data' => 'json',
    ];
}
