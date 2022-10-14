<?php

namespace App\Http\Controllers\api\auth;

use Cart;
use Session;
use App\Models\Card;
use App\Models\Product;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Catch_;
use App\Models\Cart as ModelsCart;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Darryldecode\Cart\Cart as CartCart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

        public function cartList()
    {
        $user = Auth::user()->id;
       
        if(!$user){
            return response()->json([
                'status' => 'success',
                'cart' => 'empty',
            ]);
        }
        $results = DB::table('carts')
                ->join('cart_products', 'carts.id', '=', 'cart_products.cart_id')
                ->where('carts.user_id', $user->id)
                ->get();

        return response()->json([
            'status' => 'success',
            'cart' => $results,
        ]);
    }    
    
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function addToCart(Request $request)
    {
        $user = Auth::user();
        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
        $product = Product::find($request->product_id);
        $cart = new ModelsCart();
        $cart->user_id = $user->id;
        $cart->save();

        $cartItems = new CartProduct();
        $cartItems->cart_id = $cart->id;
        $cartItems->product_id = $product->id;
        $cartItems->quantity = $request->quantity;
        $cartItems->sub_total = $request->quantity * $product->price;
        $cartItems->save();
        
        // all products in carts
        $results = DB::table('cart_products')
                    ->join('carts', 'cart_products.cart_id', '=', 'carts.id')
                    ->where('carts.user_id', $user->id)
                    ->get();

        // $counts = DB::table('cart_products')
        //             ->join('carts', 'cart_products.cart_id', '=', 'carts.id')
        //             ->where('carts.user_id', $user->id)
        //             ->count(); 
        // $final_price=0;                       
        // for ($i=0; $i <=$counts ; $i++) { 
        //     # code...
        //     $final_price += $results[$i]->sub_total; 
        // } 
        // $cart2 = ModelsCart::where('user_id',$user->id);
        // $cart2->total_price = $final_price;
        // $cart2->count_product = $counts;

        return response()->json([
            'status' => 'success',
            'message'=> 'Product added to cart successfully!',
            'cart' => $results,
        ]);

        // $product = Product::find($request->product_id);
        // $userID =  Auth::user()->id;
        // $rowId = 1;
        // \Cart::session($userID)->add(array(
        //     'id' => $rowId++,
        //     'name' => $product->name,
        //     'price' => $product->price,
        //     'quantity' => $request->quantity,
        //     'associatedModel' => $product
        // ));
        // if(isset($cart[$request->product_id])) {
        //     $cart[$request->product_id]['quantity']++;
        // } else {
        //     $cart[$request->product_id] = [
        //         "name" => $product->name,
        //         "quantity" => 1,
        //         "price" => $product->price,
        //     ];
        // }
        // $cart = Cart::session($userID)->getContent();
        // // session()->put('cart', $cart);
        // return response()->json([
        //     'status' => 'success',
        //     'message'=>'Product added to cart successfully!',
        //     'cart' => $cart,
        // ]);
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
