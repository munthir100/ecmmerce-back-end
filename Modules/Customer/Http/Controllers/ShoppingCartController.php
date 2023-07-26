<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use Modules\Store\Entities\Store;
use Illuminate\Routing\Controller;
use Modules\Store\Entities\Product;
use App\Http\Responses\MessageResponse;
use App\Services\FeaturedProductService;
use Modules\Customer\Entities\Customer;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Http\Requests\AddFeaturedProductToCartRequest;
use Modules\Customer\Http\Requests\AddToCartRequest;
use Modules\Customer\Transformers\shoppingCartResource;

class ShoppingCartController extends Controller
{
    protected $cartService, $featuredProductService;

    public function __construct(CartService $cartService, FeaturedProductService $featuredProductService)
    {
        $this->cartService = $cartService;
        $this->featuredProductService = $featuredProductService;
    }

    public function getCartByCustomer()
    {
        $customer = request()->user()->customer;
        $cart = $customer->shoppingCart;

        return $cart && !$cart->items->isEmpty()
            ? new MessageResponse(data: new shoppingCartResource($cart), statusCode: 200)
            : new MessageResponse(message: 'The cart is empty', statusCode: 200);
    }



    public function addProductToCart(Store $store, $productId, AddToCartRequest $request)
    {
        $data = $request->validated();
        $customer = $request->user()->customer;
        $cart = $customer->shoppingCart()->firstOrCreate([]);
        $product = $this->cartService->findProduct($store, $productId);
        $validatedQuantity = $this->cartService->validateProductQuantity($product,$data['quantity'],$cart);
        $ProductInCart = $this->cartService->CheckIfProductExistsInCart($product, $cart);

        $ProductInCart ?
            $ProductInCart->update(['quantity' => $validatedQuantity])
            : $cart->items()->create([
                'product_id' => $product->id,
                'product_id' => $product->id,
                'store_id' => $store->id,
                'quantity' => $validatedQuantity,
            ]);
        return new MessageResponse(
            message: 'shopping cart updated',
            data: new shoppingCartResource($cart),
            statusCode: 200
        );
    }














    public function addFeaturedProductToCart(Store $store, $productId, AddFeaturedProductToCartRequest $request)
    {
        $data = $request->validated();
        $productOption = $data['product_option'];
        $productOptionValue = $data['product_option_value'];
        $customer = $request->user()->customer;
        $cart = $customer->shoppingCart()->firstOrCreate([]);
        $product = $this->featuredProductService->findProduct($store, $productId, $productOption, $productOptionValue);
        $existingProduct = $this->featuredProductService->findProductInCartWithOptions($cart, $product, $productOption, $productOptionValue);
        $existingProduct ? $totalQuantity = $existingProduct->quantity + $data['quantity'] : $totalQuantity = $data['quantity'];
        $availableQuantity = $this->featuredProductService->calculateOptionValueAvailableQuantity($product, $productOption, $productOptionValue);
        $this->featuredProductService->validateQuantity($availableQuantity, $totalQuantity);
        $this->featuredProductService->addItemToCart(
            $store,
            $cart,
            $productId,
            $productOption,
            $productOptionValue,
            $existingProduct,
            $totalQuantity
        );

        return new MessageResponse('product added to cart',statusCode:200);
    }














    public function removeProductFromCart(Store $store, $itemId)
    {
        $customer = request()->user()->customer;
        $cart = $customer->shoppingCart;
        $item = $cart->items->find($itemId);
        $item->delete();

        return new MessageResponse(message: 'Product removed from cart', statusCode: 200);
    }


    public function updateProductQuantity(Store $store, $productId, Request $request)
    {
        $product = $store->products->find($productId);

        if (!$product) {
            return new MessageResponse(message: 'The product does not exist in the store', statusCode: 404);
        }

        $data = $request->validated();
        $newQuantity = $data['quantity'];
        $customer = request()->user()->customer;
        $cart = $customer->shoppingCart->with('items');

        if (!$cart || $cart->items()->isEmpty() || !$cart->items()->contains('id', $product->id)) {
            return new MessageResponse(message: 'The product does not exist in the cart', statusCode: 404);
        }

        $cart->products()->updateExistingPivot($product->id, ['quantity' => $newQuantity]);

        return new MessageResponse(message: 'Product quantity updated in cart', statusCode: 200);
    }
}
