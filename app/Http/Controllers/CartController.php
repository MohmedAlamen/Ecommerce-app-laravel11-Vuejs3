<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        // الحصول على المنتجات المقترحة
        $mightAlsoLike = Product::mightAlsoLike()->get();
        $top_pick = Product::orderBy('id', 'DESC')->paginate(4);
        $top_pick2 = Product::orderBy('id', 'ASC')->paginate(4);

        // حساب المجموع الفرعي
        $subtotal = Cart::getSubTotal();

        // نسبة الضريبة (مثال: 13%)
        $taxRate = 0.13;

        // حساب الضريبة
        $tax = $subtotal * $taxRate;

        // حساب المجموع الكلي
        $total = $subtotal + $tax;

        // تمرير البيانات إلى الواجهة
        return view('cart', compact('mightAlsoLike', 'top_pick', 'top_pick2', 'subtotal', 'tax', 'total'));
    }

    public function store(Product $product)
    {
        // التحقق من المنتجات المكررة في السلة
        $duplicates = Cart::getContent()->filter(function ($cartItem) use ($product) {
            return $cartItem->id === $product->id;
        });

        if ($duplicates->isNotEmpty()) {
            return redirect()->route('cart.index')->with('success_message', 'Item is already in your cart!');
        }

        // إضافة المنتج إلى السلة
        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'quantity' => 1,
            'price' => $product->price,
            'attributes' => [],
            'associatedModel' => $product,
        ]);

        return redirect()->route('cart.index')->with('success_message', 'Item was added to your cart!');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric|between:1,5',
        ]);

        if ($validator->fails()) {
            session()->flash('errors', collect(['Quantity must be between 1 and 5.']));
            return response()->json(['success' => false], 400);
        }

        if ($request->quantity > $request->productQuantity) {
            session()->flash('errors', collect(['We currently do not have enough items in stock.']));
            return response()->json(['success' => false], 400);
        }

        Cart::update($id, [
            'quantity' => [
                'relative' => false,
                'value' => $request->quantity,
            ],
        ]);

        session()->flash('success_message', 'Quantity was updated successfully!');
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Cart::remove($id);

        return back()->with('success_message', 'Item has been removed!');
    }

    public function switchToSaveForLater($id)
    {
        $item = Cart::get($id);

        Cart::remove($id);

        $duplicates = Cart::instance('saveForLater')->getContent()->filter(function ($cartItem) use ($id) {
            return $cartItem->id === $id;
        });

        if ($duplicates->isNotEmpty()) {
            return redirect()->route('cart.index')->with('success_message', 'Item is already Saved For Later!');
        }

        Cart::instance('saveForLater')->add([
            'id' => $item->id,
            'name' => $item->name,
            'quantity' => 1,
            'price' => $item->price,
            'attributes' => [],
            'associatedModel' => $item->associatedModel,
        ]);

        return redirect()->route('cart.index')->with('success_message', 'Item has been Saved For Later!');
    }
}
