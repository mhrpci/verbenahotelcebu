<?php

namespace Database\Seeders;

use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;
use Botble\Base\Supports\BaseSeeder;
use Html;
use Illuminate\Support\Str;
use SlugHelper;

class PageSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'name'     => 'Homepage',
                'content'  =>
                    Html::tag('div', '[home-banner][/home-banner]') .
                    Html::tag('div', '[check-availability-form][/check-availability-form]') .
                    Html::tag('div', '[hotel-about title="since 1994" description="Situated In Prime Position At The Foot Of The Slopes Of Courchevel Moriond." block_icon_1="flaticon-coffee" block_text_1="Breakfast" block_icon_2="flaticon-air-freight" block_text_2="Airport Pickup" block_icon_3="flaticon-marker" block_text_3="City Guide" block_icon_4="flaticon-barbecue" block_text_4="BBQ Party" block_icon_5="flaticon-hotel" block_text_5="Luxury Room"][/hotel-about]') .
                    Html::tag('div', '[room-categories title="Room Type" sub_title="Inspired Loding" background_image="general/bg.jpg"][/room-categories]') .
                    Html::tag('div', '[hotel-featured-features title="The Thin Escape" description="Miranda has everything for your trip & every single things." button_text="Take a tour" button_url="/rooms"][/hotel-featured-features]') .
                    Html::tag('div', '[rooms][/rooms]') .
                    Html::tag('div', '[video-introduction title="Take a tour" sub_title="Discover Our Underground." description="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat." background_image="general/video-background-02.jpg" video_image="general/video-banner-01.jpg" video_url="https://www.youtube.com/watch?v=EEJFMdfraVY" button_text="Book Now" button_url="/rooms"][/video-introduction]') .
                    Html::tag('div', '[testimonial title="testimonials" description="Client Feedback"][/testimonial]') .
                    Html::tag('div', '[rooms-introduction title="Our rooms" description="Each of our nine lovely double guest rooms feature a private bath, wi-fi, cable television and include full breakfast." background_image="general/bg.jpg" first_image="general/01.jpg" second_image="general/02.jpg" third_image="general/03.jpg" button_text="Take a tour" button_url="/rooms"][/rooms-introduction]') .
                    Html::tag('div', '[featured-news title="Blog" description="News Feeds"][/featured-news]')
                ,
                'template' => 'homepage',
                'user_id'  => 1,
            ],
            [
                'name'     => 'News',
                'content'  => Html::tag('p', '--'),
                'template' => 'default',
                'user_id'  => 1,
            ],
            [
                'name'     => 'Contact',
                'content'  => Html::tag('div', '[contact-info][/contact-info]') . Html::tag('div', '[google-map]19/A, Cirikon City hall Tower New York, NYC[/google-map]') . Html::tag('div', '[contact-form][/contact-form]'),
                'template' => 'no-sidebar',
                'user_id'  => 1,
            ],
            [
                'name'     => 'Restaurant',
                'content'  => Html::tag('div', '[food-types][/food-types]') . Html::tag('div', '[foods title="Restaurant" sub_title="Trending Menu"][/foods]'),
                'template' => 'no-sidebar',
                'user_id'  => 1,
            ],
            [
                'name'     => 'Our Gallery',
                'content'  => Html::tag('div', '[all-galleries title="Gallery" sub_title="Our Rooms"][/all-galleries]'),
                'template' => 'no-sidebar',
                'user_id'  => 1,
            ],
            [
                'name'     => 'About us',
                'content'  => Html::tag('div', '[youtube-video url="https://www.youtube.com/watch?v=EEJFMdfraVY" background_image="general/04.jpg"][/youtube-video]') .
                    Html::tag('p', 'Hello. Our hotel has been present for over 20 years. We make the best or all our customers. Hello. Our hotel has been present for over 20 years. We make the best or all our customers. Hello. Our hotel has been present for over 20 years. We make the best or all our customers.') .
                    Html::tag('div', '[hotel-core-features title="Facilities" sub_title="Core Features"][/hotel-core-features]') .
                    Html::tag('div', '[featured-news title="Blog" description="News Feeds"][/featured-news]')
                ,
                'template' => 'no-sidebar',
                'user_id'  => 1,
            ],
            [
                'name'     => 'Places',
                'content'  => Html::tag('div', '[places][/places]')
                ,
                'template' => 'no-sidebar',
                'user_id'  => 1,
            ],
            [
                'name'     => 'Our Offers',
                'content'  => Html::tag('div', '[our-offers][/our-offers]')
                ,
                'template' => 'no-sidebar',
                'user_id'  => 1,
            ],
        ];

        Page::truncate();
        Slug::where('reference_type', Page::class)->delete();
        MetaBoxModel::where('reference_type', Page::class)->delete();

        foreach ($pages as $index => $item) {
            $page = Page::create($item);

            Slug::create([
                'reference_type' => Page::class,
                'reference_id'   => $page->id,
                'key'            => Str::slug($page->name),
                'prefix'         => SlugHelper::getPrefix(Page::class),
            ]);
        }
    }
}
