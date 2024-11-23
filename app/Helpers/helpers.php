<?php

use Carbon\Carbon;
use Darryldecode\Cart\Facades\CartFacade as Cart;

function presentDate($date)
{
    try {
        return Carbon::parse($date)->format('M d, Y');
    } catch (\Exception $e) {
        return null; // أو يمكن إرجاع تاريخ افتراضي في حال حدوث خطأ
    }
}

function setActiveCategory($category, $output = 'active')
{
    return request()->category && request()->category == $category ? $output : '';
}

function productImage($path)
{
    if ($path && file_exists(public_path('storage/'.$path))) {
        return asset('storage/'.$path);
    }

    return asset('img/not-found.jpg');
}

// function getNumbers()
// {
//     $tax = config('cart.tax', 0) / 100; // تحديد قيمة افتراضية للضرائب
//     $discount = session()->get('coupon')['discount'] ?? 0;
//     $code = session()->get('coupon')['name'] ?? null;
//     $newSubtotal = max(0, Cart::subtotal() - $discount); // التأكد من أن المجموع لا يصبح سالبًا
//     $newTax = $newSubtotal * $tax;
//     $newTotal = $newSubtotal + $newTax;

//     return collect([
//         'tax' => $tax,
//         'discount' => $discount,
//         'code' => $code,
//         'newSubtotal' => $newSubtotal,
//         'newTax' => $newTax,
//         'newTotal' => $newTotal,
//     ]);
// }

function getNumbers()
{
    $cartItems = Cart::getContent();
    $subtotal = $cartItems->sum(function ($item) {
        return $item->quantity * $item->price;
    });

    $discount = session()->get('coupon')['discount'] ?? 0;
    $newSubtotal = $subtotal - $discount;

    $tax = config('cart.tax') / 100;
    $newTax = $newSubtotal * $tax;
    $newTotal = $newSubtotal + $newTax;

    return collect([
        'subtotal' => $subtotal,
        'discount' => $discount,
        'newSubtotal' => $newSubtotal,
        'newTax' => $newTax,
        'newTotal' => $newTotal,
    ]);
}


function getStockLevel($quantity)
{
    $stockThreshold = setting('site.stock_threshold', 5);

    if ($quantity > $stockThreshold) {
        return '<div class="badge badge-success">In Stock</div>';
    }

    if ($quantity > 0) {
        return '<div class="badge badge-warning">Low Stock</div>';
    }

    return '<div class="badge badge-danger">Not available</div>';
}
