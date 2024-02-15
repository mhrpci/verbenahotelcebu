<?php

namespace Botble\Hotel\Models;

use Botble\Base\Models\BaseModel;

class RoomDate extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ht_room_dates';

    /**
     * @var array
     */
    protected $fillable = [
        'room_id',
        'start_date',
        'end_date',
        'price',
        'max_guests',
        'active',
        'note_to_customer',
        'note_to_admin',
        'number_of_rooms',
    ];
}
