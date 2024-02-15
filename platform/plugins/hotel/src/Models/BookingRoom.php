<?php

namespace Botble\Hotel\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Hotel\Enums\BookingStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingRoom extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ht_booking_rooms';

    /**
     * @var array
     */
    protected $fillable = [
        'booking_id',
        'room_id',
        'price',
        'currency_id',
        'number_of_rooms',
        'start_date',
        'end_date',
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
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id')->withDefault();
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query
            ->join('ht_bookings', 'ht_bookings.id', '=', $this->table . '.booking_id')
            ->whereNotIn('ht_bookings.status', [BookingStatusEnum::CANCELLED]);
    }

    /**
     * @param Builder $query
     * @param string $startDate
     * @param string $endDate
     */
    public function scopeInRange($query, $startDate, $endDate)
    {
        return $query
            ->where('ht_booking_rooms.start_date', '<=', $endDate)
            ->where('ht_booking_rooms.end_date', '>', $startDate);
    }
}
