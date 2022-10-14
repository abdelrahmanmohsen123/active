<?php

namespace App\Http\Controllers\api\auth;

use Cart;
use Session;
use App\Models\Card;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

        public function cartList()
    {
        $userId = Auth::user()->id;
        $cartItems = \Cart::session($userId)->getContent();
        if(!$cartItems){
            return response()->json([
                'status' => 'success',
                'cart' => 'empty',
            ]);
        }
        return response()->json([
            'status' => 'success',
            'cart' => $cartItems,
        ]);
    }    
    
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function addToCart(Request $request)
    {
        $product = Product::find($request->product_id);
        $userID =  Auth::user()->id;
        $rowId = 1;
        \Cart::session($userID)->add(array(
            'id' => $rowId++,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $request->quantity,
            'associatedModel' => $product
        ));
        // if(isset($cart[$request->product_id])) {
        //     $cart[$request->product_id]['quantity']++;
        // } else {
        //     $cart[$request->product_id] = [
        //         "name" => $product->name,
        //         "quantity" => 1,
        //         "price" => $product->price,
        //     ];
        // }
        $cart = Cart::session($userID)->getContent();
        // session()->put('cart', $cart);
        return response()->json([
            'status' => 'success',
            'message'=>'Product added to cart successfully!',
            'cart' => $cart,
        ]);
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function update(Request $request)
    {
        if($request->product_id && $request->quantity){
            $userID =  Auth::user()->id;
            $product = Product::find($request->product_id);
            return $product;
            \Cart::session($userID)->update($request->product_id,[
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);
            $cart = Cart::session($userID)->getContent();
            // $cart = session()->get('cart');
            // $cart[$request->id]["quantity"] = $request->quantity;
            // session()->put('cart', $cart);

            return response()->json([
                'status' => 'success',
                'message'=>'Cart updated successfully',
                'cart' => $cart,
            ]);
        }
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return response()->json([
                'status' => 'success',
                'message'=>'Product removed successfully',
                'cart' => $cart,
            ]);
        }
    }
}
