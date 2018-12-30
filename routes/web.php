<?php

$cart_products = [
    ['name' => 'Product name', 'description' => 'Brief description', 'price' => '12',],
    ['name' => 'Second product', 'description' => 'Brief description', 'price' => '8',],
    ['name' => 'Third item', 'description' => 'Brief description', 'price' => '5',],
    ['name' => 'Promo code', 'description' => 'EXAMPLECODE', 'price' => '-5',],
];
//done this way in order to simplify

Route::get('/', function () use($cart_products) {
    return view('payment_form')
    ->with('cart_products',$cart_products)
    ;
});

Route::post('pay', 'ChargeController@store');

Route::get('paypal_return', 'ChargeController@paypal_return');
Route::get('paypal_cancel', function () use($cart_products) {
    return view('payment_form')
    ->with('cart_products',$cart_products)
    ;
});

Route::get('success', function () use($cart_products) {
    return view('success_page')
    ->with('cart_products',$cart_products)
    ;
});
