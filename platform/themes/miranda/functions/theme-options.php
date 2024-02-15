<?php

theme_option()
    ->setField([
        'id'         => 'copyright',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'text',
        'label'      => __('Copyright'),
        'attributes' => [
            'name'    => 'copyright',
            'value'   => '© 2021 Botble Technologies. All right reserved.',
            'options' => [
                'class'        => 'form-control',
                'placeholder'  => __('Change copyright'),
                'data-counter' => 250,
            ],
        ],
        'helper'     => __('Copyright on footer of site'),
    ])
    ->setField([
        'id'         => 'logo_white',
        'section_id' => 'opt-text-subsection-logo',
        'type'       => 'mediaImage',
        'label'      => __('Logo White'),
        'attributes' => [
            'name'  => 'logo_white',
            'value' => null,
        ],
    ])
    ->setField([
        'id'         => 'about-us',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'textarea',
        'label'      => __('About us'),
        'attributes' => [
            'name'    => 'about-us',
            'value'   => null,
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'hotel_rules',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'editor',
        'label'      => __('Hotel Rules'),
        'attributes' => [
            'name'    => 'hotel_rules',
            'value'   => null,
            'options' => [
                'class' => 'form-control',
                'rows'  => 2,
            ],
        ],
    ])
    ->setField([
        'id'         => 'cancellation',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'editor',
        'label'      => __('Cancellation'),
        'attributes' => [
            'name'    => 'cancellation',
            'value'   => null,
            'options' => [
                'class' => 'form-control',
                'rows'  => 2,
            ],
        ],
    ])
    ->setField([
        'id'         => 'hotline',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'text',
        'label'      => __('Hotline'),
        'attributes' => [
            'name'    => 'hotline',
            'value'   => null,
            'options' => [
                'class'        => 'form-control',
                'placeholder'  => 'Hotline',
                'data-counter' => 30,
            ],
        ],
    ])
    ->setField([
        'id'         => 'address',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'text',
        'label'      => __('Address'),
        'attributes' => [
            'name'    => 'address',
            'value'   => null,
            'options' => [
                'class'        => 'form-control',
                'placeholder'  => 'Address',
                'data-counter' => 120,
            ],
        ],
    ])
    ->setField([
        'id'         => 'email',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'email',
        'label'      => __('Email'),
        'attributes' => [
            'name'    => 'email',
            'value'   => null,
            'options' => [
                'class'        => 'form-control',
                'placeholder'  => 'Email',
                'data-counter' => 120,
            ],
        ],
    ])
    ->setField([
        'id'         => 'term_of_use_url',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'text',
        'label'      => __('Term of use URL'),
        'attributes' => [
            'name'    => 'term_of_use_url',
            'value'   => null,
            'options' => [
                'class'        => 'form-control',
                'placeholder'  => 'Term of use URL',
                'data-counter' => 120,
            ],
        ],
    ])
    ->setField([
        'id'         => 'privacy_policy_url',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'text',
        'label'      => __('Privacy Environmental Policy URL'),
        'attributes' => [
            'name'    => 'privacy_policy_url',
            'value'   => null,
            'options' => [
                'class'        => 'form-control',
                'placeholder'  => 'Privacy Environmental Policy URL',
                'data-counter' => 120,
            ],
        ],
    ])
    ->setField([
        'id'         => 'primary_font',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'googleFonts',
        'label'      => __('Primary font'),
        'attributes' => [
            'name'  => 'primary_font',
            'value' => 'Archivo',
        ],
    ])
    ->setField([
        'id'         => 'secondary_font',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'googleFonts',
        'label'      => __('Secondary font'),
        'attributes' => [
            'name'  => 'secondary_font',
            'value' => 'Old Standard TT',
        ],
    ])
    ->setField([
        'id'         => 'tertiary_font',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'googleFonts',
        'label'      => __('Tertiary font'),
        'attributes' => [
            'name'  => 'tertiary_font',
            'value' => 'Roboto',
        ],
    ])
    ->setField([
        'id'         => 'primary_color',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'customColor',
        'label'      => __('Primary color'),
        'attributes' => [
            'name'  => 'primary_color',
            'value' => '#bead8e',
        ],
    ])
    ->setField([
        'id'         => 'news_banner',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'mediaImage',
        'label'      => __('Banner image in news'),
        'attributes' => [
            'name'  => 'news_banner',
            'value' => null,
        ],
    ])
    ->setField([
        'id'         => 'rooms_banner',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'mediaImage',
        'label'      => __('Banner image in rooms'),
        'attributes' => [
            'name'  => 'rooms_banner',
            'value' => null,
        ],
    ])
    ->setField([
        'id'         => 'preloader_enabled',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'select',
        'label'      => __('Enable Preloader?'),
        'attributes' => [
            'name'    => 'preloader_enabled',
            'list'    => [
                'no'  => 'No',
                'yes' => 'Yes',
            ],
            'value'   => 'no',
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setSection([
        'title'      => __('Social'),
        'desc'       => __('Social links'),
        'id'         => 'opt-text-subsection-social',
        'subsection' => true,
        'icon'       => 'fa fa-share-alt',
    ])
    ->setField([
        'id'         => 'facebook',
        'section_id' => 'opt-text-subsection-social',
        'type'       => 'text',
        'label'      => 'Facebook',
        'attributes' => [
            'name'    => 'facebook',
            'value'   => null,
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'twitter',
        'section_id' => 'opt-text-subsection-social',
        'type'       => 'text',
        'label'      => 'Twitter',
        'attributes' => [
            'name'    => 'twitter',
            'value'   => null,
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'youtube',
        'section_id' => 'opt-text-subsection-social',
        'type'       => 'text',
        'label'      => 'Youtube',
        'attributes' => [
            'name'    => 'youtube',
            'value'   => null,
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'behance',
        'section_id' => 'opt-text-subsection-social',
        'type'       => 'text',
        'label'      => 'Behance',
        'attributes' => [
            'name'    => 'behance',
            'value'   => null,
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'linkedin',
        'section_id' => 'opt-text-subsection-social',
        'type'       => 'text',
        'label'      => 'Linkedin',
        'attributes' => [
            'name'    => 'linkedin',
            'value'   => null,
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ]);

$sliderFields = [];
for ($i = 1; $i <= 5; $i++) {
    $sliderFields = array_merge($sliderFields, [
        [
            'id'         => 'slider-image-' . $i,
            'type'       => 'mediaImage',
            'label'      => __('Slider image :number', ['number' => $i]),
            'attributes' => [
                'name'    => 'slider-image-' . $i,
                'value'   => '',
                'options' => [
                    'allow_thumb' => false,
                ],
            ],
        ],
        [
            'id'         => 'slider-title-' . $i,
            'type'       => 'text',
            'label'      => __('Slider title :number', ['number' => $i]),
            'attributes' => [
                'name'    => 'slider-title-' . $i,
                'value'   => '',
                'options' => [
                    'class'        => 'form-control',
                    'data-counter' => 120,
                ],
            ],
        ],
        [
            'id'         => 'slider-description-' . $i,
            'type'       => 'editor',
            'label'      => __('Slider description :number', ['number' => $i]),
            'attributes' => [
                'name'    => 'slider-description-' . $i,
                'value'   => '',
                'options' => [
                    'class'        => 'form-control',
                    'data-counter' => 120,
                    'rows'         => 4,
                ],
            ],
        ],
        [
            'id'         => 'slider-primary-button-text-' . $i,
            'type'       => 'text',
            'label'      => __('Slider action text :number', ['number' => $i]),
            'attributes' => [
                'name'    => 'slider-primary-button-text-' . $i,
                'value'   => 'Take A tour',
                'options' => [
                    'class'        => 'form-control',
                    'data-counter' => 120,
                ],
            ],
        ],
        [
            'id'         => 'slider-primary-button-url-' . $i,
            'type'       => 'text',
            'label'      => __('Slider learn more URL :number', ['number' => $i]),
            'attributes' => [
                'name'    => 'slider-primary-button-url-' . $i,
                'value'   => '#',
                'options' => [
                    'class'        => 'form-control',
                    'data-counter' => 120,
                ],
            ],
        ],
        [
            'id'         => 'slider-secondary-button-text-' . $i,
            'type'       => 'text',
            'label'      => __('Slider secondary button text :number', ['number' => $i]),
            'attributes' => [
                'name'    => 'slider-secondary-button-text-' . $i,
                'value'   => 'Learn more',
                'options' => [
                    'class'        => 'form-control',
                    'data-counter' => 120,
                ],
            ],
        ],
        [
            'id'         => 'slider-secondary-button-url-' . $i,
            'type'       => 'text',
            'label'      => __('Slider secondary-button URL :number', ['number' => $i]),
            'attributes' => [
                'name'    => 'slider-secondary-button-url-' . $i,
                'value'   => '#',
                'options' => [
                    'class'        => 'form-control',
                    'data-counter' => 120,
                ],
            ],
        ],
    ]);
}

theme_option()
    ->setSection([
        'title'      => __('Sliders'),
        'desc'       => __('Sliders'),
        'id'         => 'opt-text-subsection-sliders',
        'subsection' => true,
        'icon'       => 'fa fa-images',
        'fields'     => $sliderFields,
    ]);
