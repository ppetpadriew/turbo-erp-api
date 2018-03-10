<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\UnitController;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$controllers = [
    'units'      => UnitController::class,
    'item_types' => ItemTypeController::class,
];
foreach ($controllers as $resource => $controller) {
    $baseName = class_basename($controller);
    $router->get("/{$resource}", "{$baseName}@index");
    $router->post("/{$resource}", "{$baseName}@create");
    $router->put("/{$resource}/{id}", "{$baseName}@update");
    $router->delete("/{$resource}/{id}", "{$baseName}@delete");
}
