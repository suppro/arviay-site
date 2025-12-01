<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $cart = session('cart', []);
        return view('cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Проверяем наличие товара
        if ($product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Недостаточно товара на складе. Доступно: ' . $product->stock_quantity);
        }

        $cart = session('cart', []);
        $productId = $product->id;

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $request->quantity;
            // Проверяем наличие при увеличении количества
            if ($product->stock_quantity < $newQuantity) {
                return back()->with('error', 'Недостаточно товара на складе. Доступно: ' . $product->stock_quantity);
            }
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            $cart[$productId] = [
                'product' => $product,
                'quantity' => $request->quantity
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Товар добавлен в корзину!');
    }

    public function update(Request $request)
    {
        $cart = session('cart', []);
        if (isset($cart[$request->id])) {
            $product = $cart[$request->id]['product'];
            $newQuantity = max(1, (int)$request->quantity);
            
            // Проверяем наличие товара
            if ($product->stock_quantity < $newQuantity) {
                return back()->with('error', 'Недостаточно товара на складе. Доступно: ' . $product->stock_quantity);
            }
            
            $cart[$request->id]['quantity'] = $newQuantity;
        }
        session(['cart' => $cart]);
        return back();
    }
    
    public function clear()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        session()->forget('cart');
        
        return redirect()->route('cart')->with('success', 'Корзина очищена');
    }

    public function remove($id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);
        return back()->with('success', 'Товар удален из корзины');
    }
}