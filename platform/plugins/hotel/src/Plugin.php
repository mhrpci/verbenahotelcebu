<?php

namespace Botble\Hotel;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('ht_currencies');
        Schema::dropIfExists('ht_room_categories');
        Schema::dropIfExists('ht_rooms');
        Schema::dropIfExists('ht_room_dates');
        Schema::dropIfExists('ht_amenities');
        Schema::dropIfExists('ht_rooms_amenities');
        Schema::dropIfExists('ht_food_types');
        Schema::dropIfExists('ht_foods');
        Schema::dropIfExists('ht_bookings');
        Schema::dropIfExists('ht_booking_addresses');
        Schema::dropIfExists('ht_booking_services');
        Schema::dropIfExists('ht_booking_rooms');
        Schema::dropIfExists('ht_customers');
        Schema::dropIfExists('ht_features');
        Schema::dropIfExists('ht_services');
        Schema::dropIfExists('ht_places');
        Schema::dropIfExists('ht_taxes');
    }
}
