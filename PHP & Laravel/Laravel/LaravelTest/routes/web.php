<?php

use App\Http\Controllers\RegionController;
use App\Http\Controllers\TerritoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewPurchaseOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;


// Zone routes

Route::get('/zones', [ZoneController::class, 'index'])->name('zone.index');
Route::get('/zones/create', [ZoneController::class, 'create'])->name('zone.create');
Route::post('/zones', [ZoneController::class, 'store'])->name('zone.store');
Route::get('/zones/{zone}', [ZoneController::class, 'edit'])->name('zone.edit');
Route::put('/zones/{zone}', [ZoneController::class, 'update'])->name('zone.update');


Route::get('/regions', [RegionController::class, 'index'])->name('regions.index');
Route::get('/regions/create', [RegionController::class, 'create'])->name('region.create');
Route::post('/regions', [RegionController::class, 'store'])->name('region.store');
Route::get('/regions/edit/{id}', [RegionController::class, 'edit'])->name('region.edit');
Route::put('/regions/update/{id}', [RegionController::class, 'update'])->name('region.update');

Route::get('/territories', [TerritoryController::class, 'index'])->name('territories.index');
Route::get('/territories/create', [TerritoryController::class, 'create'])->name('territory.create');
Route::post('/territories', [TerritoryController::class, 'store'])->name('territory.store');
Route::patch('/territories/{id}', [TerritoryController::class, 'update'])->name('territories.update');
Route::get('/territories/edit/{id}', [TerritoryController::class, 'edit'])->name('territory.edit');
Route::get('/territories/delete/{id}', [TerritoryController::class, 'destroy'])->name('territory.delete');
Route::get('/get-regions', [TerritoryController::class, 'getRegions'])->name('get_regions');
Route::patch('/territories/{territory}', [TerritoryController::class, 'update'])->name('territory.update');


Route::get('/users', [UserController::class, 'index'])->name('user.index');
Route::get('/users/create', [UserController::class, 'create'])->name('user.create');
Route::post('/users', [UserController::class, 'store'])->name('user.store');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
Route::patch('/users/{user}', [UserController::class, 'update'])->name('user.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');


Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

// Create a Purchase Order
Route::get('/purchase_orders/create', [PurchaseOrderController::class, 'create'])->name('purchase_orders.create');
Route::post('/purchase_orders', [PurchaseOrderController::class,'store'])->name('purchase_orders.store');
Route::get('/purchase_orders', [PurchaseOrderController::class, 'index'])->name('purchase_orders.index');
Route::get('/purchase_orders/{purchase_order}', [PurchaseOrderController::class, 'show'])->name('purchase_orders.show');
Route::get('/purchase_orders/{purchase_order}/edit', 'PurchaseOrderController@edit')->name('purchase_orders.edit');
Route::put('/purchase_orders/{purchase_order}', 'PurchaseOrderController@update')->name('purchase_orders.update');
Route::post('/regions/fetch', 'PurchaseOrderController@fetchRegions')->name('regions.fetch');


Route::get('/purchase_orders', [PurchaseOrderController::class, 'index'])->name('purchase_orders.index');
Route::post('/purchase_orders/filter', [PurchaseOrderController::class, 'filter'])->name('purchase_orders.filter');

Route::get('/purchase_orders/{po_number}', [PurchaseOrderController::class, 'show'])->name('purchase_orders.show');


Route::get('purchase_orders/view_invoice/{po_number}', [PurchaseOrderController::class, 'viewInvoice'])->name('purchase_orders.view_invoice');
Route::post('purchase_orders/generate_invoices', [PurchaseOrderController::class, 'generateInvoices'])->name('purchase_orders.generate_invoices');

Route::get('/fetchRegions/{zoneId}', [PurchaseOrderController::class, 'getRegions']);
Route::get('/fetchTerritories/{regionId}', [PurchaseOrderController::class, 'getTerritories']);
Route::get('/fetchDistributors/{territoryId}', [PurchaseOrderController::class, 'getDistributors']);

Route::get('generate-invoices', [PurchaseOrderController::class, 'generateInvoices'])->name('purchase_orders.generateInvoices');
Route::get('generate-csv', [PurchaseOrderController::class,'generateCSV'])->name('purchase_orders.generateCSV');

Route::get('/', function () {
    return view('welcome');
});
