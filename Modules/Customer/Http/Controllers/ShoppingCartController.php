<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use Modules\Store\Entities\Store;
use Illuminate\Routing\Controller;
use App\Services\CustomerService;
use App\Services\FeaturedProductService;
use Modules\Customer\Entities\ShoppingCart;
use Modules\Customer\Http\Requests\AddFeaturedProductToCartRequest;
use Modules\Customer\Http\Requests\AddToCartRequest;
use Modules\Customer\Transformers\shoppingCartResource;

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
        $cart = $this->customerService->findModel($customer, ShoppingCart::class);

        return $cart && !$cart->items->isEmpty()
            ? $this->responseSuccess(data: new shoppingCartResource($cart))
            : $this->responseSuccess('The cart is empty');
    }



    public function addProductToCart(Store $store, $productId, AddToCartRequest $request)
    {
        $data = $request->validated();
        $customer = $request->user()->customer;
        $cart = $customer->shoppingCart()->firstOrCreate([]);
        $product = $this->cartService->findProduct($store, $productId);
        $validatedQuantity = $this->cartService->validateProductQuantity($product, $data['quantity'], $cart);
        $ProductInCart = $this->cartService->CheckIfProductExistsInCart($product, $cart);

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
            data: new shoppingCartResource($cart),
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
            new shoppingCartResource($cart),
        );
    }


    public function removeProductFromCart(Store $store, $itemId, CustomerService $customerService)
    {
        $customer = request()->user()->customer;
        $cart = $customerService->findModel($customer, ShoppingCart::class);
        $item = $cart->items->find($itemId);
        if (!$item) {
            return $this->responseNotFound('this item not exist in cart');
        }
        $item->delete();

        return $this->responseSuccess('Product removed from cart');
    }


    public function updateProductQuantity(Store $store, $productId, Request $request)
    {
        $product = $store->products->find($productId);

        if (!$product) {
            return $this->responseNotFound('The product does not exist in the store');
        }

        $data = $request->validated();
        $newQuantity = $data['quantity'];
        $customer = request()->user()->customer;
        $cart = $customer->shoppingCart->with('items');

        if (!$cart || $cart->items()->isEmpty() || !$cart->items()->contains('id', $product->id)) {
            return $this->responseNotFound('The product does not exist in the cart');
        }

        $cart->products()->updateExistingPivot($product->id, ['quantity' => $newQuantity]);

        return $this->responseSuccess('Product quantity updated in cart');
    }
}
