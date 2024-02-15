<?php

use Botble\Base\Http\Controllers\SystemController;

Route::group(['namespace' => 'Botble\Base\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'system/info'], function () {
            Route::get('', [
                'as'         => 'system.info',
                'uses'       => 'SystemController@getInfo',
                'permission' => ACL_ROLE_SUPER_USER,
            ]);
        });

        Route::group(['prefix' => 'system/cache'], function () {

            Route::get('', [
                'as'         => 'system.cache',
                'uses'       => 'SystemController@getCacheManagement',
                'permission' => ACL_ROLE_SUPER_USER,
            ]);

            Route::post('clear', [
                'as'         => 'system.cache.clear',
                'uses'       => 'SystemController@postClearCache',
                'permission' => ACL_ROLE_SUPER_USER,
                'middleware' => 'preventDemo',
            ]);
        });

        Route::post('membership/authorize', [
            'as'         => 'membership.authorize',
            'uses'       => 'SystemController@authorize',
            'permission' => false,
        ]);
    });

    Route::get('settings-language/{alias}', [SystemController::class, 'getLanguage'])->name('settings.language');
});
