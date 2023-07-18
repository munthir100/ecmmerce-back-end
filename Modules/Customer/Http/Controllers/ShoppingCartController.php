<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Responses\MessageResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Store\Entities\Product;
use Modules\Customer\Entities\Customer;
use Illuminate\Contracts\Support\Renderable;
use Modules\Customer\Transformers\shoppingCartResource;
use Modules\Store\Entities\Store;

class ShoppingCartController extends Controller
{
    public function getCartByCustomer()
    {
        $customer = request()->user()->customer;
        $cart = $customer->shoppingCart;

        return $cart && !$cart->products->isEmpty()
            ? new MessageResponse(data: new shoppingCartResource($cart), statusCode: 200)
            : new MessageResponse(message: 'The cart is empty', statusCode: 200);
    }

    public function addProductToCart(Store $store, $productId, Request $request)
    {
        $product = $store->products->find($productId);
        if (!$product) {
            return new MessageResponse(
                message: 'The product does not exist in the store',
                statusCode: 404
            );
        }
        $data = $request->validate([
            'quantity' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($product) {
                    if (!$product->unspecifiedQuantity && $value > $product->quantity) {
                        $fail('The requested quantity exceeds the available quantity for this product');
                    }
                },
            ],
        ]);
        if ($product->featured_product) {
            $data['product_option'] = $request->validate([
                'product_option' => 'required'
            ]);
        }

        $newQuantity = $data['quantity'];
        $customer = $request->user()->customer;

        $cart = $customer->shoppingCart()->firstOrCreate([]);

        $updatedQuantity = 0;

        if ($existingProduct = $cart->products->find($product->id)) {
            $existingQuantity = $existingProduct->pivot->quantity;
            $updatedQuantity = $existingQuantity + $newQuantity;

            $cart->products()->updateExistingPivot($product->id, [
                'quantity' => $updatedQuantity
            ]);
        } else {
            $cart->products()->attach($product, [
                'store_id' => $store->id,
                'quantity' => $newQuantity,
            ]);
            $updatedQuantity = $newQuantity; // Assign the new quantity to the variable
        }

        return new MessageResponse(
            message: 'Product added to cart successfully',
            data: [
                'product_id' => $product->id,
                'product_price' => $product->price,
                'quantity' => $updatedQuantity,
            ],
            statusCode: 200
        );
    }


    public function removeProductFromCart(Store $store, $productId)
    {
        $product = $store->products->find($productId);

        if (!$product) {
            return new MessageResponse(message: 'The product does not exist in the store', statusCode: 404);
        }

        $customer = request()->user()->customer;
        $cart = $customer->shoppingCart;

        if (!$cart || !$cart->products->contains('id', $product->id)) {
            return new MessageResponse(message: 'The product does not exist in the cart', statusCode: 404);
        }

        $cart->products()->detach($product);

        return new MessageResponse(message: 'Product removed from cart', statusCode: 200);
    }


    public function updateProductQuantity(Store $store, $productId, Request $request)
    {
        $product = $store->products->find($productId);

        if (!$product) {
            return new MessageResponse(message: 'The product does not exist in the store', statusCode: 404);
        }


        $data = $request->validate([
            'quantity' => 'required|integer'
        ]);
        $newQuantity = $data['quantity'];
        $customer = request()->user()->customer;
        $cart = $customer->shoppingCart;

        if (!$cart || $cart->products->isEmpty() || !$cart->products->contains('id', $product->id)) {
            return new MessageResponse(message: 'The product does not exist in the cart', statusCode: 404);
        }

        $cart->products()->updateExistingPivot($product->id, ['quantity' => $newQuantity]);

        return new MessageResponse(message: 'Product quantity updated in cart', statusCode: 200);
    }
}
