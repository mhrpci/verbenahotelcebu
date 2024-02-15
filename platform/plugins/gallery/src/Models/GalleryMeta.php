<?php

namespace Botble\Gallery\Models;

use Botble\Base\Models\BaseModel;

class GalleryMeta extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gallery_meta';

    /**
     * The date fields for the model.clear
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @param string $value
     * @return array
     *
     */
    public function getImagesAttribute($value)
    {
        return json_decode($value, true);
    }
}
