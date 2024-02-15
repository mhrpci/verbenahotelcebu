<?php

register_page_template([
    'no-sidebar' => __('No Sidebar'),
    'full-width' => __('Full width'),
    'homepage'   => __('Homepage'),
]);

register_sidebar([
    'id'          => 'footer_sidebar',
    'name'        => __('Footer sidebar'),
    'description' => __('Sidebar in the footer of site'),
]);

Menu::removeMenuLocation('main-menu')
    ->removeMenuLocation('footer-menu')
    ->addMenuLocation('side-menu', __('Side Navigation'));

RvMedia::setUploadPathAndURLToPublic();

RvMedia::addSize('380x280', 380, 280)
    ->addSize('380x575', 380, 575)
    ->addSize('775x280', 775, 280)
    ->addSize('770x460', 770, 460)
    ->addSize('550x580', 550, 580)
    ->addSize('1170x570', 1170, 570);
