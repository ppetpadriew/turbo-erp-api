<?php

/**
 * @var \Laravel\Lumen\Routing\Router $router
 *
 * Application Routes
 *
 * Here is where you can register all of the routes for an application.
 * It is a breeze. Simply tell Lumen the URIs it should respond to
 * and give it the Closure to call when that URI is requested.
 */


use App\Http\Controllers\AddressController;
use App\Http\Controllers\BusinessPartnerController;
use App\Http\Controllers\FirstFreeNumberController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseOrderController;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$controllers = [
    'units'              => UnitController::class,
    'items'              => ItemController::class,
    'warehouses'         => WarehouseController::class,
    'warehouse_orders'   => WarehouseOrderController::class,
    'business_partners'  => BusinessPartnerController::class,
    'first_free_numbers' => FirstFreeNumberController::class,
    'business_partners'  => BusinessPartnerController::class,
    'addresses'          => AddressController::class,
];
foreach ($controllers as $resource => $controller) {
    $baseName = class_basename($controller);
    $router->get("/{$resource}", "{$baseName}@index");
    $router->get("/{$resource}/{id}", "{$baseName}@get");
    $router->post("/{$resource}", "{$baseName}@create");
    $router->put("/{$resource}/{id}", "{$baseName}@update");
    $router->delete("/{$resource}/{id}", "{$baseName}@delete");
}
