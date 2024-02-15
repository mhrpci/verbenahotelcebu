<?php

namespace Botble\Hotel\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomCategory extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ht_room_categories';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * @return HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
