<?php

Route::group([
    'prefix' => 'admin/',
    'namespace' => 'Admin',
    'as' => 'admin.'
], function () {
    Route::get('login', 'AuthController@showLoginForm')->name('login');
    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout')->name('logout');
    Route::post('/file/upload', 'ImageController@upload')->name('image.upload');
    Route::post('/file/delete', 'ImageController@delete')->name('image.delete');
    Route::post('reset-password-mail', 'ResetPasswordController@sendMail')->name('password.sendmail');
    Route::get('reset-password', 'ResetPasswordController@showResetForm');
    Route::post('reset-password', 'ResetPasswordController@resetPassword')->name('password.reset');
    Route::get('mark-as-read-all', 'NotificationController@markAsReadAll')->name('notification.mark-all');
    Route::group([
        'middleware' => ['admin', 'image.unuse', 'auth:admin']
    ], function () {
        Route::get('/', 'HomeController@index')->name('index');

        Route::group([
            'as' => 'brand.',
            'prefix' => 'brands'
        ], function () {
            Route::get('/', 'BrandController@index')->name('index');
            Route::get('/create', 'BrandController@create')->name('create');
            Route::post('/create', 'BrandController@store');
            Route::get('/{brand}/edit', 'BrandController@edit')->name('update');
            Route::put('/{brand}/edit', 'BrandController@update');
            Route::get('/{brand}/destroy', 'BrandController@destroy')->name('destroy');
            Route::get('/{brand}/active', 'BrandController@active')->name('active');
            Route::get('/{id}/images', 'BrandController@images')->name('images');
        });


        Route::group([
            'as' => 'user.',
            'prefix' => 'users'
        ], function () {
            Route::get('/', 'AdminController@index')->name('index');
            Route::get('/create', 'AdminController@create')->name('create');
            Route::post('/', 'AdminController@store')->name('store');
            // Route::get('/{admin}/edit', 'AdminController@edit')->name('update');
            // Route::post('/{admin}/edit', 'AdminController@update');
            Route::get('/{admin}/destroy', 'AdminController@destroy')->name('destroy');
            Route::get('/{admin}/active', 'AdminController@active')->name('active');
            Route::get('/{admin}/deactivate', 'AdminController@deactivate')->name('deactivate');
        });

        Route::group([
            'as' => 'role.',
            'prefix' => 'roles'
        ], function () {
            Route::get('/create', 'RoleController@create')->name('create');
            Route::post('/', 'RoleController@store')->name('store');
            Route::get('/{role}/edit', 'RoleController@edit')->name('update');
            Route::post('/{role}/edit', 'RoleController@update');
            Route::get('/{role}/destroy', 'RoleController@destroy')->name('destroy');
        });

        Route::group([
            'as' => 'place.',
            'prefix' => 'places'
        ], function () {
            Route::get('/', 'PlaceController@index')->name('index');
            Route::get('/create', 'PlaceController@create')->name('create');
            Route::post('/', 'PlaceController@store')->name('store');
            Route::get('/{place}/edit', 'PlaceController@edit')->name('update');
            Route::post('/{place}/edit', 'PlaceController@update');
            Route::get('/{place}/destroy', 'PlaceController@destroy')->name('destroy');
        });
        Route::group([
            'as' => 'province.',
            'prefix' => 'provinces'
        ], function () {
            Route::post('/places', 'ProvinceController@places')->name('places');
        });
        Route::group([
            'as' => 'route.',
            'prefix' => 'routes'
        ], function () {
            Route::get('/', 'RouteController@index')->name('index');
            Route::get('/create', 'RouteController@create')->name('create');
            Route::post('/', 'RouteController@store')->name('store');
            Route::get('/{route}/edit', 'RouteController@edit')->name('update');
            Route::post('/{route}/edit', 'RouteController@update');
            Route::get('/{route}/destroy', 'RouteController@destroy')->name('destroy');
            Route::post('/passingPlaces', 'RouteController@passingPlaces')->name('passingPlaces');
            Route::get('/{route}/trips', 'RouteController@getTrips')->name('trips');
            Route::get('/{route}/active', 'RouteController@active')->name('active');
            Route::get('/{route}/inactive', 'RouteController@inactive')->name('inactive');
        });

        Route::group([
            'as' => 'trip.',
            'prefix' => 'trips'
        ], function () {
            Route::get('/', 'TripController@index')->name('index');
            Route::get('/create', 'TripController@create')->name('create');
            Route::post('/', 'TripController@store')->name('store');
            Route::get('/{trip}/edit', 'TripController@edit')->name('update');
            Route::post('/{trip}/edit', 'TripController@update');
            Route::get('/{trip}/destroy', 'TripController@destroy')->name('destroy');
            Route::get('/{trip}/active', 'TripController@active')->name('active');
            Route::get('/{trip}/inactive', 'TripController@inactive')->name('inactive');
        });

        Route::group([
            'as' => 'ticket.',
            'prefix' => 'tickets'
        ], function () {
            Route::get('/', 'TicketController@index')->name('index');
            Route::get('/{ticket_code}/show', 'TicketController@show')->name('show');
            Route::get('/create', 'TicketController@create')->name('create');
            Route::post('/', 'TicketController@store')->name('store');
            Route::get('/{ticket_code}/edit', 'TicketController@edit')->name('update');
            Route::post('/{ticket_code}/edit', 'TicketController@update');
            Route::get('/{ticket_code}/destroy', 'TicketController@destroy')->name('destroy');
            Route::get('/search', 'TicketController@search')->name('search');
            Route::post('/{ticket_code}/update-status', 'TicketController@updateStatus')->name('update_status');
        });

        Route::group([
            'as' => 'trip_date.',
            'prefix' => 'trip_dates'
        ], function () {
            Route::get('/', 'TripDepartDateController@index')->name('index');
            Route::post('/create_single_schedule', 'TripDepartDateController@createSingleSchedule');
            Route::post('/create_multi_schedule', 'TripDepartDateController@createMultiSchedule');
            Route::post('/change_status_schedule', 'TripDepartDateController@changeStatusSchedule');
            Route::get('/change_status_schedule', 'TripDepartDateController@changeStatusSchedule1');
            Route::get('/search', 'TripDepartDateController@search')->name('search');
            Route::get('/test', 'TripDepartDateController@test')->name('test');
        });

        Route::group([
            'as' => 'province.',
            'prefix' => 'provinces'
        ], function () {
            Route::get('/', 'ProvinceController@index')->name('index');
            Route::post('/districts', 'ProvinceController@getDistricts')->name('get_districts');
            Route::get('/create', 'ProvinceController@create')->name('create');
            Route::post('/', 'ProvinceController@store')->name('store');
            Route::get('/{province}/edit', 'ProvinceController@edit')->name('edit');
            Route::post('/{province}', 'ProvinceController@update')->name('update');
            Route::get('/{province}/delete', 'ProvinceController@destroy')->name('destroy');
            // Route::post('/places', 'ProvinceController@places')->name('places');
        });

        Route::group([
            'as' => 'district.',
            'prefix' => 'districts'
        ], function () {
            Route::get('/', 'DistrictController@index')->name('index');
            Route::get('/create', 'DistrictController@create')->name('create');
            Route::post('/', 'DistrictController@store')->name('store');
            Route::get('/{district}/edit', 'DistrictController@edit')->name('edit');
            Route::post('/{district}', 'DistrictController@update')->name('update');
            Route::get('/{district}/delete', 'DistrictController@destroy')->name('destroy');
            Route::get('/search', 'DistrictController@search')->name('search');
        });

        Route::group([
            'as' => 'profile.',
            'prefix' => 'profile'
        ], function () {
            Route::get('/', 'ProfileController@index')->name('index');
            Route::post('/', 'ProfileController@update')->name('update');
            Route::get('/change-password', 'ProfileController@getChangePasswordForm')->name('change_password');
            Route::post('/change-password', 'ProfileController@changePassword');
        });

        Route::group([
            'as' => 'report.',
            'prefix' => 'reports'
        ], function () {
            Route::get('/', 'ReportController@index')->name('index');
            Route::get('/search', 'ReportController@search')->name('search');
            Route::get('/excel', 'ReportController@excel')->name('excel');
        });
    });
});
