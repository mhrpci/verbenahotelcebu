<?php

namespace Botble\Hotel\Providers;

use Botble\Hotel\Repositories\Interfaces\BookingInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Throwable;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @throws Throwable
     */
    public function boot()
    {
         add_filter(BASE_FILTER_TOP_HEADER_LAYOUT, [$this, 'registerTopHeaderNotification'], 130);
         add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'countPendingBookings'], 130, 2);
    }

    /**
     * @param string $options
     * @return string
     *
     * @throws Throwable
     */
    public function registerTopHeaderNotification($options)
    {
        if (Auth::user()->hasPermission('booking.edit')) {
            $bookings = $this->app->make(BookingInterface::class)
                ->getPendingBookings(['id', 'created_at'], ['address']);

            if ($bookings->count() == 0) {
                return null;
            }

            return $options . view('plugins/hotel::notification', compact('bookings'))->render();
        }

        return null;
    }

    /**
     * @param int $number
     * @param string $menuId
     * @return string
     * @throws BindingResolutionException
     */
    public function countPendingBookings($number, $menuId)
    {
        if ($menuId == 'cms-plugins-booking') {
            $unread = $this->app->make(BookingInterface::class)->countPendingBookings();
            if ($unread > 0) {
                return '<span class="badge badge-success">' . $unread . '</span>';
            }
        }

        return $number;
    }
}
