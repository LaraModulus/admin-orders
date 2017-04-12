<?php

Route::group([
    'prefix' => 'admin/orders',
    'middleware' => ['web', 'auth'],
    'namespace' => 'LaraMod\AdminOrders',
], function(){
    Route::get('/', ['as' => 'admin.orders', 'uses' => 'AdminOrdersController@index']);
    Route::get('/form', ['as' => 'admin.orders.form', 'uses' => 'AdminOrdersController@getForm']);
    Route::post('/form', ['as' => 'admin.orders.form', 'uses' => 'AdminOrdersController@postForm']);

    Route::get('/delete', ['as' => 'admin.orders.delete', 'uses' => 'AdminOrdersController@delete']);
});
