<?php

namespace Botble\Hotel\Providers;

use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Hotel\Models\Tax;
use Botble\Hotel\Repositories\Caches\TaxCacheDecorator;
use Botble\Hotel\Repositories\Eloquent\TaxRepository;
use Botble\Hotel\Repositories\Interfaces\TaxInterface;
use Botble\Hotel\Models\Amenity;
use Botble\Hotel\Models\Booking;
use Botble\Hotel\Models\BookingAddress;
use Botble\Hotel\Models\BookingRoom;
use Botble\Hotel\Models\Currency;
use Botble\Hotel\Models\Customer;
use Botble\Hotel\Models\Feature;
use Botble\Hotel\Models\Food;
use Botble\Hotel\Models\FoodType;
use Botble\Hotel\Models\Place;
use Botble\Hotel\Models\Room;
use Botble\Hotel\Models\RoomCategory;
use Botble\Hotel\Models\RoomDate;
use Botble\Hotel\Models\Service;
use Botble\Hotel\Repositories\Caches\AmenityCacheDecorator;
use Botble\Hotel\Repositories\Caches\BookingAddressCacheDecorator;
use Botble\Hotel\Repositories\Caches\BookingCacheDecorator;
use Botble\Hotel\Repositories\Caches\BookingRoomCacheDecorator;
use Botble\Hotel\Repositories\Caches\CurrencyCacheDecorator;
use Botble\Hotel\Repositories\Caches\CustomerCacheDecorator;
use Botble\Hotel\Repositories\Caches\FeatureCacheDecorator;
use Botble\Hotel\Repositories\Caches\FoodCacheDecorator;
use Botble\Hotel\Repositories\Caches\FoodTypeCacheDecorator;
use Botble\Hotel\Repositories\Caches\PlaceCacheDecorator;
use Botble\Hotel\Repositories\Caches\RoomCacheDecorator;
use Botble\Hotel\Repositories\Caches\RoomCategoryCacheDecorator;
use Botble\Hotel\Repositories\Caches\RoomDateCacheDecorator;
use Botble\Hotel\Repositories\Caches\ServiceCacheDecorator;
use Botble\Hotel\Repositories\Eloquent\AmenityRepository;
use Botble\Hotel\Repositories\Eloquent\BookingAddressRepository;
use Botble\Hotel\Repositories\Eloquent\BookingRepository;
use Botble\Hotel\Repositories\Eloquent\BookingRoomRepository;
use Botble\Hotel\Repositories\Eloquent\CurrencyRepository;
use Botble\Hotel\Repositories\Eloquent\CustomerRepository;
use Botble\Hotel\Repositories\Eloquent\FeatureRepository;
use Botble\Hotel\Repositories\Eloquent\FoodRepository;
use Botble\Hotel\Repositories\Eloquent\FoodTypeRepository;
use Botble\Hotel\Repositories\Eloquent\PlaceRepository;
use Botble\Hotel\Repositories\Eloquent\RoomCategoryRepository;
use Botble\Hotel\Repositories\Eloquent\RoomDateRepository;
use Botble\Hotel\Repositories\Eloquent\RoomRepository;
use Botble\Hotel\Repositories\Eloquent\ServiceRepository;
use Botble\Hotel\Repositories\Interfaces\AmenityInterface;
use Botble\Hotel\Repositories\Interfaces\BookingAddressInterface;
use Botble\Hotel\Repositories\Interfaces\BookingInterface;
use Botble\Hotel\Repositories\Interfaces\BookingRoomInterface;
use Botble\Hotel\Repositories\Interfaces\CurrencyInterface;
use Botble\Hotel\Repositories\Interfaces\CustomerInterface;
use Botble\Hotel\Repositories\Interfaces\FeatureInterface;
use Botble\Hotel\Repositories\Interfaces\FoodInterface;
use Botble\Hotel\Repositories\Interfaces\FoodTypeInterface;
use Botble\Hotel\Repositories\Interfaces\PlaceInterface;
use Botble\Hotel\Repositories\Interfaces\RoomCategoryInterface;
use Botble\Hotel\Repositories\Interfaces\RoomDateInterface;
use Botble\Hotel\Repositories\Interfaces\RoomInterface;
use Botble\Hotel\Repositories\Interfaces\ServiceInterface;
use EmailHandler;
use Illuminate\Support\Facades\Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Language;
use SlugHelper;

class HotelServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(CurrencyInterface::class, function () {
            return new CurrencyCacheDecorator(
                new CurrencyRepository(new Currency)
            );
        });

        $this->app->bind(RoomInterface::class, function () {
            return new RoomCacheDecorator(
                new RoomRepository(new Room)
            );
        });

        $this->app->bind(RoomDateInterface::class, function () {
            return new RoomDateCacheDecorator(
                new RoomDateRepository(new RoomDate)
            );
        });

        $this->app->bind(AmenityInterface::class, function () {
            return new AmenityCacheDecorator(
                new AmenityRepository(new Amenity)
            );
        });

        $this->app->bind(FoodInterface::class, function () {
            return new FoodCacheDecorator(
                new FoodRepository(new Food)
            );
        });

        $this->app->bind(FoodTypeInterface::class, function () {
            return new FoodTypeCacheDecorator(
                new FoodTypeRepository(new FoodType)
            );
        });

        $this->app->bind(BookingInterface::class, function () {
            return new BookingCacheDecorator(
                new BookingRepository(new Booking)
            );
        });

        $this->app->bind(BookingAddressInterface::class, function () {
            return new BookingAddressCacheDecorator(
                new BookingAddressRepository(new BookingAddress)
            );
        });

        $this->app->bind(BookingRoomInterface::class, function () {
            return new BookingRoomCacheDecorator(
                new BookingRoomRepository(new BookingRoom)
            );
        });

        $this->app->bind(CustomerInterface::class, function () {
            return new CustomerCacheDecorator(
                new CustomerRepository(new Customer)
            );
        });

        $this->app->bind(RoomCategoryInterface::class, function () {
            return new RoomCategoryCacheDecorator(
                new RoomCategoryRepository(new RoomCategory)
            );
        });

        $this->app->bind(FeatureInterface::class, function () {
            return new FeatureCacheDecorator(
                new FeatureRepository(new Feature)
            );
        });

        $this->app->bind(ServiceInterface::class, function () {
            return new ServiceCacheDecorator(
                new ServiceRepository(new Service)
            );
        });

        $this->app->bind(PlaceInterface::class, function () {
            return new PlaceCacheDecorator(
                new PlaceRepository(new Place)
            );
        });

        $this->app->bind(TaxInterface::class, function () {
            return new TaxCacheDecorator(
                new TaxRepository(new Tax)
            );
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        SlugHelper::registerModule(Room::class, 'Rooms');
        SlugHelper::setPrefix(Room::class, 'rooms');

        SlugHelper::registerModule(Place::class, 'Places');
        SlugHelper::setPrefix(Place::class, 'places');

        $this->setNamespace('plugins/hotel')
            ->loadAndPublishConfigurations(['permissions', 'hotel', 'email'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web'])
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                Language::registerModule([
                    Room::class,
                    Room::class,
                    Amenity::class,
                    Food::class,
                    Feature::class,
                    Service::class,
                    Place::class,
                ]);
            }

            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-plugins-hotel',
                    'priority'    => 5,
                    'parent_id'   => null,
                    'name'        => 'plugins/hotel::hotel.name',
                    'icon'        => 'fas fa-hotel',
                    'url'         => route('room.index'),
                    'permissions' => ['room.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-room',
                    'priority'    => 1,
                    'parent_id'   => 'cms-plugins-hotel',
                    'name'        => 'plugins/hotel::room.name',
                    'icon'        => null,
                    'url'         => route('room.index'),
                    'permissions' => ['room.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-room-category',
                    'priority'    => 2,
                    'parent_id'   => 'cms-plugins-hotel',
                    'name'        => 'plugins/hotel::room-category.name',
                    'icon'        => null,
                    'url'         => route('room-category.index'),
                    'permissions' => ['room-category.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-amenities',
                    'priority'    => 3,
                    'parent_id'   => 'cms-plugins-hotel',
                    'name'        => 'plugins/hotel::amenity.name',
                    'icon'        => null,
                    'url'         => route('amenity.index'),
                    'permissions' => ['amenity.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-food',
                    'priority'    => 4,
                    'parent_id'   => 'cms-plugins-hotel',
                    'name'        => 'plugins/hotel::food.name',
                    'icon'        => null,
                    'url'         => route('food.index'),
                    'permissions' => ['food.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-food-type',
                    'priority'    => 5,
                    'parent_id'   => 'cms-plugins-hotel',
                    'name'        => 'plugins/hotel::food-type.name',
                    'icon'        => null,
                    'url'         => route('food-type.index'),
                    'permissions' => ['food-type.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-feature',
                    'priority'    => 6,
                    'parent_id'   => 'cms-plugins-hotel',
                    'name'        => 'plugins/hotel::feature.menu',
                    'icon'        => null,
                    'url'         => route('feature.index'),
                    'permissions' => ['feature.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-service',
                    'priority'    => 6,
                    'parent_id'   => 'cms-plugins-hotel',
                    'name'        => 'plugins/hotel::service.name',
                    'icon'        => null,
                    'url'         => route('service.index'),
                    'permissions' => ['service.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-place',
                    'priority'    => 6,
                    'parent_id'   => 'cms-plugins-hotel',
                    'name'        => 'plugins/hotel::place.name',
                    'icon'        => null,
                    'url'         => route('place.index'),
                    'permissions' => ['place.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-booking',
                    'priority'    => 6,
                    'parent_id'   => null,
                    'name'        => 'plugins/hotel::booking.name',
                    'icon'        => 'far fa-calendar-alt',
                    'url'         => route('booking.index'),
                    'permissions' => ['booking.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-customer',
                    'priority'    => 7,
                    'parent_id'   => 'cms-plugins-hotel',
                    'name'        => 'plugins/hotel::customer.name',
                    'icon'        => null,
                    'url'         => route('customer.index'),
                    'permissions' => ['customer.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-tax',
                    'priority'    => 8,
                    'parent_id'   => 'cms-plugins-hotel',
                    'name'        => 'plugins/hotel::tax.name',
                    'icon'        => null,
                    'url'         => route('tax.index'),
                    'permissions' => ['tax.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-hotel-settings',
                    'priority'    => 999,
                    'parent_id'   => 'cms-plugins-hotel',
                    'name'        => 'plugins/hotel::hotel.settings',
                    'icon'        => null,
                    'url'         => route('hotel.settings'),
                    'permissions' => ['hotel.settings'],
                ]);

            EmailHandler::addTemplateSettings(HOTEL_MODULE_SCREEN_NAME, config('plugins.hotel.email'));
        });

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
