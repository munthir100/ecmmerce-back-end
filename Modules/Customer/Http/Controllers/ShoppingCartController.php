<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\CustomerService;
use Modules\Store\Entities\Store;
use App\Http\Controllers\Controller;
use App\Services\FeaturedProductService;
use Modules\Customer\Entities\ShoppingCart;
use Modules\Customer\Http\Requests\AddToCartRequest;
use Modules\Customer\Transformers\ShoppingCartResource;
use Modules\Customer\Http\Requests\UpdateQuantityRequest;
use Modules\Customer\Http\Requests\AddFeaturedProductToCartRequest;
use Modules\Customer\Http\Requests\UpdateFeaturedProductQuantity;

class ShoppingCartController extends Controller
{
    protected $cartService, $featuredProductService, $customerService;

    public function __construct(
        CartService $cartService,
        FeaturedProductService $featuredProductService,
        CustomerService $customerService
    ) {
        $this->cartService = $cartService;
        $this->featuredProductService = $featuredProductService;
        $this->customerService = $customerService;
    }

    public function getCartByCustomer()
    {
        $customer = request()->user()->customer;
        $cart = $customer->ShoppingCart()->with('items')->first();

        return $cart && !$cart->items->isEmpty()
            ? $this->responseSuccess(data: new ShoppingCartResource($cart))
            : $this->responseSuccess('The cart is empty');
    }



    public function addProductToCart(Store $store, $productId, AddToCartRequest $request)
    {
        $data = $request->validated();
        $customer = $request->user()->customer;
        $cart = $customer->shoppingCart()->firstOrCreate([]);
        $product = $this->cartService->findProduct($store, $productId);
        $validatedQuantity = $this->cartService->validateQuantityForSingleProduct($product, $data['quantity']);
        $ProductInCart = $cart->items()->where('product_id', $product->id)->first();

        $ProductInCart ?
            $ProductInCart->update([
                'quantity' => $validatedQuantity,
            ])
            : $cart->items()->create([
                'product_id' => $product->id,
                'store_id' => $store->id,
                'quantity' => $validatedQuantity,
            ]);

        return $this->responseSuccess(
            'shopping cart updated',
            data: new ShoppingCartResource($cart),
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
        $productOptionValue = $this->featuredProductService->GetProductOptionValue($product, $productOptionValue);
        $existingProduct = $this->featuredProductService->findProductInCartWithOptions($cart, $product, $productOption, $productOptionValue);
        $totalQuantity = $this->featuredProductService->calculateTotalQuantity($existingProduct, $data['quantity']);
        $availableQuantity = $productOptionValue->quantity;
        $this->featuredProductService->validateQuantity($availableQuantity, $totalQuantity);
        // $totalPrice = $this->featuredProductService->calculateTotalPrice($product, $productOptionValue, $data['quantity']);

        $this->featuredProductService->addItemToCart(
            $store,
            $cart,
            $productId,
            $productOption,
            $productOptionValue,
            $existingProduct,
            $totalQuantity
        );

        return $this->responseSuccess(
            'shopping cart updated',
            new ShoppingCartResource($cart),
        );
    }


    public function removeProductFromCart(Store $store, $itemId, CustomerService $customerService)
    {
        $customer = request()->user()->customer;
        $cart = $customer->ShoppingCart;
        $item = $cart->items()->findOrFail($itemId);
        $item->delete();

        return $this->responseSuccess('Product removed from cart');
    }



    public function updateProductQuantity(Store $store, $productId, UpdateQuantityRequest $request)
    {
        $product = $store->products()->findOrFail($productId);
        $cart = $request->user()->customer->shoppingCart()->with('items')->first();
        $item = $this->cartService->CheckIfProductExistsInCart($product, $cart);
        $request->ValidateQuantity($product->quantity);
        $item->update([
            'quantity' =>  $request->input('quantity')
        ]);

        return $this->responseSuccess('Updated Quantity Successfully', new ShoppingCartResource($item));
    }

    function updateFeaturedProductQuantity(Store $store, $productId, UpdateFeaturedProductQuantity $request)
    {
        $product = $store->products()->findOrFail($productId);
        $cart = $request->user()->customer->shoppingCart()->with('items')->first();
        $item = $this->cartService->CheckIfProductExistsInCart($product, $cart);
        $productOption = $product->options()->findOrFail($request->product_option_id);
        $productOptionValue = $productOption->values()->findOrFail($request->product_option_value_id);
        $request->validateQuantity($productOptionValue->quantity);
        $item->update([
            'quantity' =>  $request->input('quantity')
        ]);

        return $this->responseSuccess('Updated Quantity Successfully', new ShoppingCartResource($item));
    }
}
