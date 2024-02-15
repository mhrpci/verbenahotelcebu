<?php

namespace Botble\Hotel\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingAddress extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ht_booking_addresses';

    /**
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'country',
        'state',
        'city',
        'address',
        'zip',
        'booking_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class)->withDefault();
    }
}
