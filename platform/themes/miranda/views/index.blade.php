@php
Theme::layout('homepage')
@endphp

{!! do_shortcode('[home-banner][/home-banner]') !!}
{!! do_shortcode('[check-availability-form][/check-availability-form]') !!}

{!! do_shortcode('[hotel-about title="since 2000" description="Verbena Hotel Cebu is 2 Star hotel." 
block_icon_1="flaticon-coffee" block_text_1="Breakfast" block_icon_2="flaticon-air-freight" block_text_2="Airport Pickup" block_icon_3="flaticon-car" block_text_3="Shuttle Service" block_icon_4="flaticon-message" block_text_4="Fast Response" block_icon_5="flaticon-hotel" block_text_5="Luxury Room"][/hotel-about]') !!}
{!! do_shortcode('[room-categories title="Room Type" sub_title="Inspired Loding" background_image="general/bg.jpg"][/room-categories]') !!}
{!! do_shortcode('[hotel-featured-features title="The Thin Escape" description="Verbena Hotel Cebu has everything for your trip & every single things." button_text="Take a tour" button_url="#"][/hotel-featured-features]') !!}
{!! do_shortcode('[rooms][/rooms]') !!}
{!! do_shortcode('[video-introduction title="Take a tour" sub_title="Discover Our Underground." description="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat." background_image="general/video-background-02.jpg" video_image="general/328531947-1524558351402966-4902087595706018719-n.jpg" video_url="https://www.youtube.com/watch?v=ZSiqV1L2mDk" button_text="Book Now" button_url="rooms"][/video-introduction]') !!}
{!! do_shortcode('[testimonial title="testimonials" description="Client Feedback"][/testimonial]') !!}
{!! do_shortcode('[rooms-introduction title="Our rooms" description="Each of our nine lovely double guest rooms feature a private bath, wi-fi, cable television and include full breakfast." background_image="general/328531947-1524558351402966-4902087595706018719-n.jpg" first_image="general/family-room.PNG" second_image="general/standard-double-or-twin-room.PNG" third_image="general/standard-double-or-twin-room.PNG" button_text="Take a tour" button_url="#"][/rooms-introduction]') !!}
{!! do_shortcode('[featured-news title="Blog" description="News Feeds"][/featured-news]') !!}
