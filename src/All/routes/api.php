<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \Gavan4eg\HouseBank\HandlerWebhook;

/**
 * Create webhook
 */
Route::post('/my-warehouse/create', function (Request $request) {
    $myWarehouseWebhook = new HandlerWebhook();

    return $myWarehouseWebhook->create(
        $request->all()
    );
});

/**
 * Update webhook
 */
Route::post('/my-warehouse/update', function (Request $request) {
    $myWarehouseWebhook = new HandlerWebhook();

    return $myWarehouseWebhook->update(
        $request->all()
    );
});

/**
 * Delete webhook
 */
Route::post('/my-warehouse/delete', function (Request $request) {
    $myWarehouseWebhook = new HandlerWebhook();

    return $myWarehouseWebhook->delete(
        $request->all()
    );
});
