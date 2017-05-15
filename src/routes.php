<?php

Route::group([
    'prefix'     => 'admin/orders',
    'middleware' => ['admin', 'auth.admin'],
    'namespace'  => 'LaraMod\Admin\Orders\Controllers',
], function () {
    Route::get('/', ['as' => 'admin.orders', 'uses' => 'OrdersController@index']);
    Route::get('/form', ['as' => 'admin.orders.form', 'uses' => 'OrdersController@getForm']);
    Route::post('/form', ['as' => 'admin.orders.form', 'uses' => 'OrdersController@postForm']);

    Route::get('/delete', ['as' => 'admin.orders.delete', 'uses' => 'OrdersController@delete']);

    Route::delete('items', ['as' => 'admin.orders.items', 'uses' => 'OrdersController@deleteItem']);
    Route::post('items', ['as' => 'admin.orders.items', 'uses' => 'OrdersController@updateItem']);
    Route::get('items', ['as' => 'admin.orders.items', 'uses' => 'OrdersController@getItems']);
    Route::get('items/restore', ['as' => 'admin.orders.items.restore', 'uses' => 'OrdersController@restoreItem']);

    Route::get('/datatable', ['as' => 'admin.orders.datatable', 'uses' => 'OrdersController@dataTable']);
});
