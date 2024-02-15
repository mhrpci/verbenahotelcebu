<?php

use Botble\Hotel\Models\Place;
use Botble\Hotel\Models\Room;

Route::group(['namespace' => 'Botble\Hotel\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix() . '/hotel', 'middleware' => 'auth'], function () {

        Route::get('settings', [
            'as'   => 'hotel.settings',
            'uses' => 'HotelController@getSettings',
        ]);

        Route::post('settings', [
            'as'   => 'hotel.settings',
            'uses' => 'HotelController@postSettings',
        ]);

        Route::group(['prefix' => 'rooms', 'as' => 'room.'], function () {
            Route::resource('', 'RoomController')->parameters(['' => 'room']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'RoomController@deletes',
                'permission' => 'room.destroy',
            ]);

            Route::get('room-availability/{id}', [
                'as'         => 'availability',
                'uses'       => 'RoomController@getRoomAvailability',
                'permission' => 'room.edit',
            ]);

            Route::post('room-availability/{id}', [
                'as'         => 'availability',
                'uses'       => 'RoomController@storeRoomAvailability',
                'permission' => 'room.edit',
            ]);
        });

        Route::group(['prefix' => 'amenities', 'as' => 'amenity.'], function () {
            Route::resource('', 'AmenityController')->parameters(['' => 'amenity']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'AmenityController@deletes',
                'permission' => 'amenity.destroy',
            ]);
        });

        Route::group(['prefix' => 'foods', 'as' => 'food.'], function () {
            Route::resource('', 'FoodController')->parameters(['' => 'food']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'FoodController@deletes',
                'permission' => 'food.destroy',
            ]);
        });

        Route::group(['prefix' => 'food-types', 'as' => 'food-type.'], function () {
            Route::resource('', 'FoodTypeController')->parameters(['' => 'food-type']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'FoodTypeController@deletes',
                'permission' => 'food-type.destroy',
            ]);
        });

        Route::group(['prefix' => 'bookings', 'as' => 'booking.'], function () {
            Route::resource('', 'BookingController')->parameters(['' => 'booking'])->except(['create', 'store']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'BookingController@deletes',
                'permission' => 'booking.destroy',
            ]);
        });

        Route::group(['prefix' => 'customers', 'as' => 'customer.'], function () {
            Route::resource('', 'CustomerController')->parameters(['' => 'customer']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CustomerController@deletes',
                'permission' => 'customer.destroy',
            ]);
        });

        Route::group(['prefix' => 'room-categories', 'as' => 'room-category.'], function () {
            Route::resource('', 'RoomCategoryController')->parameters(['' => 'room-category']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'RoomCategoryController@deletes',
                'permission' => 'room-category.destroy',
            ]);
        });

        Route::group(['prefix' => 'features', 'as' => 'feature.'], function () {
            Route::resource('', 'FeatureController')->parameters(['' => 'feature']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'FeatureController@deletes',
                'permission' => 'feature.destroy',
            ]);
        });

        Route::group(['prefix' => 'services', 'as' => 'service.'], function () {
            Route::resource('', 'ServiceController')->parameters(['' => 'service']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'ServiceController@deletes',
                'permission' => 'service.destroy',
            ]);
        });

        Route::group(['prefix' => 'places', 'as' => 'place.'], function () {
            Route::resource('', 'PlaceController')->parameters(['' => 'place']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'PlaceController@deletes',
                'permission' => 'place.destroy',
            ]);
        });

        Route::group(['prefix' => 'taxes', 'as' => 'tax.'], function () {

            Route::resource('', 'TaxController')->parameters(['' => 'tax']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'TaxController@deletes',
                'permission' => 'tax.destroy',
            ]);
        });

    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
            Route::get(SlugHelper::getPrefix(Room::class, 'rooms'), 'PublicController@getRooms')->name('public.rooms');

            Route::get(SlugHelper::getPrefix(Room::class, 'rooms') . '/{slug}', 'PublicController@getRoom');

            Route::get(SlugHelper::getPrefix(Place::class, 'places') . '/{slug}', 'PublicController@getPlace');

            Route::post('booking', 'PublicController@postBooking')->name('public.booking');
            Route::get('booking/{token}', 'PublicController@getBooking')->name('public.booking.form');

            Route::post('checkout', 'PublicController@postCheckout')->name('public.booking.checkout');

            Route::get('checkout/{transactionId}', 'PublicController@checkoutSuccess')
                ->name('public.booking.information');

            Route::get('ajax/calculate-amount', 'PublicController@ajaxCalculateBookingAmount')
                ->name('public.booking.ajax.calculate-amount');

            Route::get('payment/status', 'PublicController@getPayPalStatus')->name('public.payment.paypal.status');
        });
    }

});
