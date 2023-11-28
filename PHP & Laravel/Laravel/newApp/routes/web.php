<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FreeIssueController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OrderController;

Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('/customers', [CustomerController::class, 'store']);
Route::post('/customers/{id}/update', [CustomerController::class, 'update'])->name('customers.update');
Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::post('/products', [ProductController::class, 'store']);
Route::post('/products/{id}/update', [ProductController::class, 'update']);
Route::get('/products/{id}/edit', [ProductController::class, 'edit']);

Route::get('/free-issues', [FreeIssueController::class, 'index'])->name('free_issues.index');
Route::get('/free-issues/create', [FreeIssueController::class, 'create'])->name('free_issues.create');
Route::post('/free-issues', [FreeIssueController::class, 'store'])->name('free_issues.store');
Route::get('/free-issues/{id}/edit', [FreeIssueController::class, 'edit'])->name('free_issues.edit');
Route::put('/free-issues/{id}', [FreeIssueController::class, 'update'])->name('free_issues.update');

Route::get('/discounts', [DiscountController::class, 'index'])->name('discounts.index');
Route::post('/discounts', [DiscountController::class, 'store'])->name('discounts.store');
Route::get('/discounts/{id}/edit', [DiscountController::class, 'edit'])->name('discounts.edit');
Route::put('/discounts/{id}/update', [DiscountController::class,'update'])->name('discounts.update');

Route::get('/place_order', [OrderController::class, 'showPlaceOrderForm'])->name('orders.place_order.form');
Route::post('/place_order', [OrderController::class, 'placeOrder'])->name('orders.place_order');
Route::get('/view-orders', [OrderController::class, 'viewOrders'])->name('orders.view_orders');
Route::get('/view-order-details/{orderNumber}', [OrderController::class, 'viewOrderDetails'])->name('orders.view_order_details');

Route::get('/', function () {
    return view('welcome');
});
