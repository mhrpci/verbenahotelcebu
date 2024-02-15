<?php

use Botble\Gallery\Models\Gallery as GalleryModel;

Route::group(['namespace' => 'Botble\Gallery\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'galleries', 'as' => 'galleries.'], function () {
            Route::resource('', 'GalleryController')
                ->parameters(['' => 'gallery']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'GalleryController@deletes',
                'permission' => 'galleries.destroy',
            ]);
        });
    });

    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::get(SlugHelper::getPrefix(GalleryModel::class, 'galleries'), [
            'as'   => 'public.galleries',
            'uses' => 'PublicController@getGalleries',
        ]);

        if (SlugHelper::getPrefix(GalleryModel::class)) {
            Route::get(SlugHelper::getPrefix(GalleryModel::class, 'galleries') . '/{slug}', [
                'as'   => 'public.gallery',
                'uses' => 'PublicController@getGallery',
            ]);
        }
    });
});
