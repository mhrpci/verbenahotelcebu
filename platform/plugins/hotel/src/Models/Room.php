<?php

namespace Botble\Hotel\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;

class Room extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ht_rooms';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'content',
        'is_featured',
        'images',
        'price',
        'currency_id',
        'number_of_rooms',
        'number_of_beds',
        'size',
        'max_adults',
        'max_children',
        'room_category_id',
        'tax_id',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * @param string $value
     * @return array
     */
    public function getImagesAttribute($value)
    {
        try {
            if ($value === '[null]') {
                return [];
            }

            $images = json_decode((string)$value, true);

            if (is_array($images)) {
                $images = array_filter($images);
            }

            return $images ?: [];
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * @return string|null
     */
    public function getImageAttribute(): ?string
    {
        return Arr::first($this->images) ?? null;
    }

    /**
     * @return BelongsToMany
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'ht_rooms_amenities', 'room_id', 'amenity_id');
    }

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
    public function category(): BelongsTo
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id')->withDefault();
    }

    /**
     * @param array $filters
     * @return array|\Illuminate\Support\Collection
     */
    public function getRoomsAvailability(array $filters = [])
    {
        $rooms = $this->get();
        $results = [];

        foreach ($rooms as $room) {
            if ($room->isAvailableAt($filters)) {
                $results[] = $room;
            }
        }

        return $results;
    }

    /**
     * @param array $filters
     * @return bool
     */
    public function isAvailableAt(array $filters = [])
    {
        if (empty($filters['start_date']) || empty($filters['end_date'])) {
            return true;
        }

        $roomDates = $this->getDatesInRange($filters['start_date'], $filters['end_date']);
        $allDates = [];
        $tmpNight = 0;
        for ($index = strtotime($filters['start_date']); $index < strtotime($filters['end_date']); $index += 60 * 60 * 24) {
            $allDates[date('Y-m-d', $index)] = [
                'number' => $this->number_of_rooms,
                'price'  => $this->price,
            ];
            $tmpNight++;
        }

        if (!empty($roomDates)) {
            foreach ($roomDates as $row) {
                if (!$row->active || !$row->number_of_rooms || !$row->price) {
                    return false;
                }

                if (!array_key_exists(date('Y-m-d', strtotime($row->start_date)), $allDates)) {
                    continue;
                }

                $allDates[date('Y-m-d', strtotime($row->start_date))] = [
                    'number' => $row->number_of_rooms,
                    'price'  => $row->price,
                ];
            }
        }

        $roomBookings = $this->getBookingsInRange($filters['start_date'], $filters['end_date']);

        if (!empty($roomBookings)) {
            foreach ($roomBookings as $roomBooking) {
                for ($index = strtotime($roomBooking->start_date); $index <= strtotime($roomBooking->end_date); $index += 60 * 60 * 24) {
                    if (!array_key_exists(date('Y-m-d', $index), $allDates)) {
                        continue;
                    }

                    $allDates[date('Y-m-d', $index)]['number'] -= $roomBooking->number_of_rooms;

                    if ($allDates[date('Y-m-d', $index)]['number'] <= 0) {
                        return false;
                    }
                }
            }
        }

        $allDates = array_column($allDates, 'number');

        $maxNumberPerDay = 0;
        if ($allDates) {
            $maxNumberPerDay = (int)min($allDates);
        }

        if (empty($maxNumberPerDay)) {
            return false;
        }

        if (!empty($filters['adults']) && $this->max_adults * $maxNumberPerDay < $filters['adults']) {
            return false;
        }

        if (!empty($filters['children']) && $this->max_children * $maxNumberPerDay < $filters['children']) {
            return false;
        }

        return true;
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @return mixed
     */
    public function getDatesInRange($startDate, $endDate)
    {
        return RoomDate::where('room_id', $this->id)
            ->where('start_date', '>=', date('Y-m-d H:i:s', strtotime($startDate)))
            ->where('end_date', '<=', date('Y-m-d H:i:s', strtotime($endDate)))
            ->take(40)
            ->get();
    }

    /**
     * @param string $from
     * @param string $to
     * @return mixed
     */
    public function getBookingsInRange($from, $to)
    {
        return BookingRoom::where('room_id', $this->id)
            ->active()
            ->inRange($from, $to)
            ->get(['ht_booking_rooms.*']);
    }

    /**
     * @return BelongsTo
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id')->withDefault();
    }
}
