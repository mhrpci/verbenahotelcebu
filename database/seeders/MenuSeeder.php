<?php

namespace Database\Seeders;

use Botble\Menu\Models\Menu as MenuModel;
use Botble\Menu\Models\MenuLocation;
use Botble\Menu\Models\MenuNode;
use Botble\Page\Models\Page;
use Botble\Base\Supports\BaseSeeder;
use Illuminate\Support\Arr;

class MenuSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            [
                'name'     => 'Header menu',
                'slug'     => 'header-menu',
                'location' => 'header-menu',
                'items'    => [
                    [
                        'title'     => 'Home',
                        'url'       => '/',
                        'parent_id' => 0,
                    ],
                    [
                        'title'     => 'Rooms',
                        'url'       => '/rooms',
                        'parent_id' => 0,
                        'has_child' => true,
                        'items'     => [
                            [
                                'title'     => 'Luxury Hall Of Fame',
                                'url'       => '/rooms/luxury-hall-of-fame',
                                'parent_id' => 2,
                            ],
                            [
                                'title'     => 'Pendora Fame',
                                'url'       => '/rooms/pendora-fame',
                                'parent_id' => 2,
                            ],
                        ],
                    ],
                    [
                        'title'          => 'News',
                        'url'            => '/news',
                        'reference_id'   => 2,
                        'reference_type' => Page::class,
                        'parent_id'      => 0,
                    ],
                    [
                        'title'          => 'Contact',
                        'url'            => '/contact',
                        'reference_id'   => 3,
                        'reference_type' => Page::class,
                        'parent_id'      => 0,
                    ],
                ],
            ],
            [
                'name'     => 'Our pages',
                'slug'     => 'our-pages',
                'location' => 'side-menu',
                'items'    => [
                    [
                        'title'          => 'About Us',
                        'url'            => '/about-us',
                        'reference_id'   => 6,
                        'reference_type' => Page::class,
                        'parent_id'      => 0,
                    ],
                    [
                        'title'          => 'Our Gallery',
                        'url'            => '/our-gallery',
                        'reference_id'   => 5,
                        'reference_type' => Page::class,
                        'parent_id'      => 0,
                        'has_child' => true,
                        'items'     => [
                            [
                                'title'     => 'King Bed',
                                'url'       => '/galleries/king-bed',
                                'parent_id' => 8,
                            ],
                            [
                                'title'     => 'Duplex Restaurant',
                                'url'       => '/galleries/duplex-restaurant',
                                'parent_id' => 8,
                            ],
                        ],
                    ],
                    [
                        'title'          => 'Restaurant',
                        'url'            => '/restaurant',
                        'reference_id'   => 4,
                        'reference_type' => Page::class,
                        'parent_id'      => 0,
                    ],
                    [
                        'title'          => 'Places',
                        'url'            => '/places',
                        'reference_id'   => 7,
                        'reference_type' => Page::class,
                        'parent_id'      => 0,
                    ],
                    [
                        'title'          => 'Our Offers',
                        'url'            => '/our-offers',
                        'reference_id'   => 8,
                        'reference_type' => Page::class,
                        'parent_id'      => 0,
                    ],
                ],
            ],
            [
                'name'     => 'Services.',
                'slug'     => 'services',
                'items'    => [
                    [
                        'title'          => 'Restaurant & Bar',
                        'url'            => '#',
                        'parent_id'      => 0,
                    ],
                    [
                        'title'          => 'Swimming Pool',
                        'url'            => '#',
                        'parent_id'      => 0,
                    ],
                    [
                        'title'          => 'Restaurant',
                        'url'            => '#',
                        'parent_id'      => 0,
                    ],
                    [
                        'title'          => 'Conference Room',
                        'url'            => '#',
                        'parent_id'      => 0,
                    ],
                    [
                        'title'          => 'Cocktail Party Houses',
                        'url'            => '#',
                        'parent_id'      => 0,
                    ],
                    [
                        'title'          => 'Gaming Zone',
                        'url'            => '#',
                        'parent_id'      => 0,
                    ],
                    [
                        'title'          => 'Marriage Party',
                        'url'            => '#',
                        'parent_id'      => 0,
                    ],
                    [
                        'title'          => 'Party Planning',
                        'url'            => '#',
                        'parent_id'      => 0,
                    ],
                    [
                        'title'          => 'Tour Consultancy',
                        'url'            => '#',
                        'parent_id'      => 0,
                    ],
                ],
            ],
        ];

        MenuModel::truncate();
        MenuLocation::truncate();
        MenuNode::truncate();

        foreach ($menus as $index => $item) {
            $menu = MenuModel::create(Arr::except($item, ['items', 'location']));

            if (isset($item['location'])) {
                MenuLocation::create([
                    'menu_id'  => $menu->id,
                    'location' => $item['location'],
                ]);
            }

            foreach ($item['items'] as $menuNode) {
                $menuNode['menu_id'] = $index + 1;
                $data = $menuNode;
                if (Arr::has($data, 'items')) {
                    $data = Arr::except($menuNode, ['items']);
                }
                MenuNode::create($data);

                if (Arr::has($menuNode, 'items')) {
                    foreach ($menuNode['items'] as $menuNodeItemIndex => $menuNodeItem) {
                        $menuNodeItem['menu_id'] = $index + 1;
                        $menuNodeItem['position'] = $menuNodeItemIndex;
                        MenuNode::create($menuNodeItem);
                    }
                }
            }
        }
    }
}
