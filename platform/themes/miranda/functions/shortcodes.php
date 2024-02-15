<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Gallery\Repositories\Interfaces\GalleryInterface;
use Botble\Hotel\Repositories\Interfaces\FeatureInterface;
use Botble\Hotel\Repositories\Interfaces\FoodInterface;
use Botble\Hotel\Repositories\Interfaces\FoodTypeInterface;
use Botble\Hotel\Repositories\Interfaces\PlaceInterface;
use Botble\Hotel\Repositories\Interfaces\RoomCategoryInterface;
use Botble\Hotel\Repositories\Interfaces\RoomInterface;
use Botble\Testimonial\Repositories\Interfaces\TestimonialInterface;
use Carbon\Carbon;

app()->booted(function () {
    if (is_plugin_active('testimonial')) {
        add_shortcode('testimonial', __('Testimonial'), __('Testimonial'), function ($shortCode) {
            $testimonials = app(TestimonialInterface::class)->allBy(['status' => BaseStatusEnum::PUBLISHED]);

            return Theme::partial('short-codes.testimonial', [
                'title'        => $shortCode->title,
                'description'  => $shortCode->description,
                'testimonials' => $testimonials,
            ]);
        });
        shortcode()->setAdminConfig('testimonial', Theme::partial('short-codes.testimonial-admin-config'));
    }

    if (is_plugin_active('blog')) {
        add_shortcode('featured-news', __('Featured News'), __('Featured News'), function ($shortCode) {
            $posts = get_featured_posts(6, ['author']);

            return Theme::partial('short-codes.featured-news', [
                'title'       => $shortCode->title,
                'description' => $shortCode->description,
                'posts'       => $posts,
            ]);
        });
        shortcode()->setAdminConfig('featured-news', Theme::partial('short-codes.featured-news-admin-config'));
    }

    add_shortcode('video-introduction', __('Video Introduction'), __('Video Introduction'), function ($shortCode) {
        Theme::asset()->usePath()->add('magnific-popup-css', 'css/magnific-popup.css');
        Theme::asset()->container('footer')->usePath()->add('jquery.magnific-popup', 'js/jquery.magnific-popup.min.js');

        return Theme::partial('short-codes.video-introduction', [
            'title'            => $shortCode->title,
            'sub_title'        => $shortCode->sub_title,
            'description'      => $shortCode->description,
            'background_image' => $shortCode->background_image,
            'video_image'      => $shortCode->video_image,
            'video_url'        => $shortCode->video_url,
            'button_text'      => $shortCode->button_text,
            'button_url'       => $shortCode->button_url,
        ]);
    });
    shortcode()->setAdminConfig('video-introduction', Theme::partial('short-codes.video-introduction-admin-config'));

    add_shortcode('rooms-introduction', __('Rooms Introduction'), __('Rooms Introduction'), function ($shortCode) {
        return Theme::partial('short-codes.rooms-introduction', [
            'title'            => $shortCode->title,
            'description'      => $shortCode->description,
            'background_image' => $shortCode->background_image,
            'first_image'      => $shortCode->first_image,
            'second_image'     => $shortCode->second_image,
            'third_image'      => $shortCode->third_image,
            'button_text'      => $shortCode->button_text,
            'button_url'       => $shortCode->button_url,
        ]);
    });
    shortcode()->setAdminConfig('rooms-introduction', Theme::partial('short-codes.rooms-introduction-admin-config'));

    add_shortcode('hotel-about', __('Hotel About'), __('Hotel About'), function ($shortCode) {
        return Theme::partial('short-codes.hotel-about', [
            'title'        => $shortCode->title,
            'description'  => $shortCode->description,
            'block_icon_1' => $shortCode->block_icon_1,
            'block_text_1' => $shortCode->block_text_1,
            'block_icon_2' => $shortCode->block_icon_2,
            'block_text_2' => $shortCode->block_text_2,
            'block_icon_3' => $shortCode->block_icon_3,
            'block_text_3' => $shortCode->block_text_3,
            'block_icon_4' => $shortCode->block_icon_4,
            'block_text_4' => $shortCode->block_text_4,
            'block_icon_5' => $shortCode->block_icon_5,
            'block_text_5' => $shortCode->block_text_5,
        ]);
    });
    shortcode()->setAdminConfig('hotel-about', Theme::partial('short-codes.hotel-about-admin-config'));

    add_shortcode('google-map', __('Google map'), __('Custom map'), function ($shortCode) {
        return Theme::partial('short-codes.google-map', ['address' => $shortCode->content]);
    });

    shortcode()->setAdminConfig('google-map', Theme::partial('short-codes.google-map-admin-config'));

    add_shortcode('youtube-video', __('Youtube video'), __('Add youtube video'), function ($shortCode) {
        $url = rtrim($shortCode->content, '/');
        if (str_contains($url, 'watch?v=')) {
            $url = str_replace('watch?v=', 'embed/', $url);
        } else {
            $exploded = explode('/', $url);

            if (count($exploded) > 1) {
                $url = 'https://www.youtube.com/embed/' . Arr::last($exploded);
            }
        }

        return Theme::partial('short-codes.youtube-video', compact('url'));
    });

    shortcode()->setAdminConfig('youtube-video', Theme::partial('short-codes.youtube-video-admin-config'));

    add_shortcode('contact-info', __('Contact information'), __('Contact information'), function () {
        return Theme::partial('short-codes.contact-info');
    });

    shortcode()->setAdminConfig('youtube-video', Theme::partial('short-codes.video-admin-config'));

    add_shortcode('home-banner', 'Home Banner', 'Home Banner', function () {
        return Theme::partial('short-codes.home-banner');
    });

    if (is_plugin_active('hotel')) {
        add_shortcode('hotel-featured-features', __('Hotel Featured Features'), __('Hotel Featured Features'),
            function ($shortCode) {
                $features = app(FeatureInterface::class)->allBy([
                    'status'      => BaseStatusEnum::PUBLISHED,
                    'is_featured' => true,
                ]);

                return Theme::partial('short-codes.hotel-featured-features', [
                    'title'       => $shortCode->title,
                    'description' => $shortCode->description,
                    'button_text' => $shortCode->button_text,
                    'button_url'  => $shortCode->button_url,
                    'features'    => $features,
                ]);
            });
        shortcode()->setAdminConfig('hotel-featured-features',
            Theme::partial('short-codes.hotel-featured-features-admin-config'));

        add_shortcode('rooms', __('Rooms'), __('Rooms'), function () {
            $rooms = app(RoomInterface::class)->allBy(['status' => BaseStatusEnum::PUBLISHED], ['slugable']);

            return Theme::partial('short-codes.rooms', compact('rooms'));
        });

        add_shortcode('room-categories', __('Room Categories'), __('Room Categories'), function ($shortCode) {
            $categories = app(RoomCategoryInterface::class)->advancedGet([
                'condition' => ['status' => BaseStatusEnum::PUBLISHED],
                'with'      => [
                    'rooms',
                    'rooms.slugable',
                ],
            ]);

            return Theme::partial('short-codes.room-categories', [
                'title'            => $shortCode->title,
                'sub_title'        => $shortCode->sub_title,
                'background_image' => $shortCode->background_image,
                'categories'       => $categories,
            ]);
        });
        shortcode()->setAdminConfig('room-categories', Theme::partial('short-codes.room-categories-admin-config'));

        add_shortcode('food-types', __('Food Types'), __('Food Types'), function () {
            $foodTypes = app(FoodTypeInterface::class)->allBy(['status' => BaseStatusEnum::PUBLISHED], ['foods']);;

            return Theme::partial('short-codes.food-types', compact('foodTypes'));
        });

        add_shortcode('foods', __('Foods'), __('Foods'), function ($shortCode) {
            $foods = app(FoodInterface::class)->allBy(['status' => BaseStatusEnum::PUBLISHED], ['type']);

            return Theme::partial('short-codes.foods', [
                'title'     => $shortCode->title,
                'sub_title' => $shortCode->sub_title,
                'foods'     => $foods,
            ]);
        });
        shortcode()->setAdminConfig('foods', Theme::partial('short-codes.foods-admin-config'));

        add_shortcode('hotel-core-features', __('Hotel Core Features'), __('Hotel Core Features'),
            function ($shortCode) {
                $features = app(FeatureInterface::class)->allBy(['status' => BaseStatusEnum::PUBLISHED]);

                return Theme::partial('short-codes.hotel-core-features', [
                    'title'     => $shortCode->title,
                    'sub_title' => $shortCode->sub_title,
                    'features'  => $features,
                ]);
            });
        shortcode()->setAdminConfig('hotel-featured-features',
            Theme::partial('short-codes.hotel-core-features-admin-config'));

        add_shortcode('check-availability-form', __('Check Availability Form'), __('Check Availability Form'),
            function () {
                return Theme::partial('short-codes.check-availability-form');
            });

        add_shortcode('our-offers', __('Our offers'), __('Our offers'), function () {
            $rooms = app(RoomInterface::class)->allBy(['status' => BaseStatusEnum::PUBLISHED, 'is_featured' => true],
                ['slugable', 'amenities', 'category']);

            return Theme::partial('short-codes.our-offers', compact('rooms'));
        });

        add_shortcode('all-rooms', __('All Rooms'), __('Display all rooms'), function () {
            if (Request::input('start_date') && Request::input('end_date')) {
                $startDate = Carbon::createFromFormat('d-m-Y', Request::input('start_date'));
                $endDate = Carbon::createFromFormat('d-m-Y', Request::input('end_date'));
            } else {
                $startDate = now();
                $endDate = now()->addDay();
            }

            $filters = [
                'keyword' => Request::query('q'),
            ];

            $params = [
                'paginate' => [
                    'per_page'      => 10,
                    'current_paged' => (int)Request::input('page', 1),
                ],
            ];

            $allRooms = app(RoomInterface::class)->getRooms($filters, $params);

            $condition = [
                'start_date' => $startDate->format('d-m-Y'),
                'end_date'   => $endDate->format('d-m-Y'),
                'adults'     => Request::input('adults', 1),
            ];

            $rooms = [];
            foreach ($allRooms as $allRoom) {
                if ($allRoom->isAvailableAt($condition)) {
                    $rooms[] = $allRoom;
                }
            }

            $nights = $endDate->diffInDays($startDate);

            return Theme::partial('short-codes.all-rooms', compact('rooms', 'nights'));
        });
    }

    if (is_plugin_active('gallery')) {
        add_shortcode('all-galleries', __('All Galleries'), __('All Galleries'), function ($shortCode) {
            $galleries = app(GalleryInterface::class)->allBy(['status' => BaseStatusEnum::PUBLISHED], ['slugable']);

            return Theme::partial('short-codes.all-galleries', [
                'title'     => $shortCode->title,
                'sub_title' => $shortCode->sub_title,
                'galleries' => $galleries,
            ]);
        });
        shortcode()->setAdminConfig('all-galleries', Theme::partial('short-codes.all-galleries-admin-config'));

        add_shortcode('places', __('Places'), __('Places'), function () {
            $places = app(PlaceInterface::class)->allBy(['status' => BaseStatusEnum::PUBLISHED]);

            return Theme::partial('short-codes.places', [
                'places' => $places,
            ]);
        });
    }

    add_shortcode('youtube-video', __('Youtube video'), __('Add youtube video'), function ($shortCode) {
        Theme::asset()->usePath()->add('magnific-popup-css', 'css/magnific-popup.css');
        Theme::asset()->container('footer')->usePath()->add('jquery.magnific-popup', 'js/jquery.magnific-popup.min.js');

        return Theme::partial('short-codes.video', [
            'url'              => $shortCode->url,
            'background_image' => $shortCode->background_image,
        ]);
    });

    if (is_plugin_active('contact')) {
        add_filter(CONTACT_FORM_TEMPLATE_VIEW, function () {
            return Theme::getThemeNamespace() . '::partials.short-codes.contact-form';
        }, 120);
    }
});
