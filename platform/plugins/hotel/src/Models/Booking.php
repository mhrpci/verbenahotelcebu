<?php

namespace Botble\Hotel\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Hotel\Enums\BookingStatusEnum;
use Botble\Payment\Models\Payment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ht_bookings';

    /**
     * @var array
     */
    protected $fillable = [
        'status',
        'amount',
        'currency_id',
        'requests',
        'arrival_time',
        'payment_id',
        'transaction_id',
        'tax_amount',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BookingStatusEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

    /**
     * @return BelongsToMany
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'ht_booking_services', 'booking_id', 'service_id');
    }

    /**
     * @return HasOne
     */
    public function address(): HasOne
    {
        return $this->hasOne(BookingAddress::class, 'booking_id')->withDefault();
    }

    /**
     * @return HasOne
     */
    public function room(): HasOne
    {
        return $this->hasOne(BookingRoom::class, 'booking_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id')->withDefault();
    }
}
