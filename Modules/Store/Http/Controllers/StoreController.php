<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Store\Entities\Store;
use Illuminate\Routing\Controller;
use Modules\Store\Entities\Product;
use App\Http\Responses\MessageResponse;
use Modules\Shipping\Entities\Location;
use Modules\Admin\Transformers\ProductResource;
use Modules\Admin\Transformers\CategoryResource;
use Modules\Admin\Http\Requests\AddLocationRequest;
use Modules\Admin\Transformers\ProductWithOptionsResource;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\Order;
use Modules\Customer\Entities\ShoppingCart;
use Modules\Customer\Transformers\LocationResource;
use Modules\Store\Entities\Brand;
use Modules\Store\Entities\Category;

class StoreController extends Controller
{
    public function products(Store $store)
    {
        $term = request()->query('term', '');
        $perPage = request()->query('PerPage', 25);
        $products = Product::search($term)
            ->where('store_id', $store->id)
            ->paginate($perPage);

        return new MessageResponse(
            data: ['products' => ProductResource::collection($products),],
            statusCode: 200
        );
    }

    public function categories(Store $store)
    {
        $term = request()->query('term', '');
        $perPage = request()->query('PerPage', 25);

        $categories = Category::search($term)
            ->where('store_id', $store->id)
            ->paginate($perPage);


        return new MessageResponse(
            data: ['categories' => CategoryResource::collection($categories)],
            statusCode: 200
        );
    }

    public function brands(Store $store)
    {
        $term = request()->query('term', '');
        $perPage = request()->query('PerPage', 25);

        $brands = Brand::search($term)
            ->whereHas('category', function ($query) use ($store) {
                $query->where('store_id', $store->id);
            })
            ->paginate($perPage);

        return new MessageResponse(
            data: ['brands' => CategoryResource::collection($brands)],
            statusCode: 200
        );
    }


    public function productDetails(Store $store, $productId)
    {
        $product = $store->products()->find($productId);
        if (!$product) {
            return new MessageResponse(message: 'this product not found', statusCode: 404);
        }
        return new MessageResponse(
            data: new ProductWithOptionsResource($product),
            statusCode: 200
        );
    }


    public function categorizedProducts(Store $store, $categoryId)
    {
        $term = request()->query('term', '');
        $perPage = request()->query('PerPage', 25);
        $products = Product::search($term)
            ->where('category_id', $categoryId)
            ->where('store_id', $store->id)
            ->paginate($perPage);

        return new MessageResponse(
            data: ['products' => ProductResource::collection($products),],
            statusCode: 200
        );
    }


    function addLocation(AddLocationRequest $request)
    {
        $data = $request->validated();
        $data['customer_id'] = request()->user()->customer()->id;
        $location = Location::create($data);

        return new MessageResponse(message: 'location created', data: ['location' => new LocationResource($location)]);
    }

    function deleteLocation(Location $location)
    {
        $location->delete();

        return new MessageResponse(message: 'location deleted');
    }

    function checkout(Store $store, Request $request)
    {
        $request->validate([
            'location_id' => 'exists:locations,id',
            'captain_id' => 'exists:captains,id',
        ]);
        $order = new Order();
        $order->customer_id = $request->user()->customer()->id;
        $order->store_id = $store->id;
        $order->captain_id = $request->captain_id;
        $order->location_id = $request->location_id;
        $order->payment_type = 'cash';
        $sumPrices = ShoppingCart::where('customer_id', $order->customer_id)->sum('price');
        $sumQuantities = ShoppingCart::where('customer_id', $order->customer_id)->sum('quantity');
        $order->total_price = $sumPrices * $sumQuantities;
        $order->save();

        $cartItems = ShoppingCart::where('customer_id', $order->customer_id)->map(function ($item) {
            return [
                'product_id' => $item->id,
                'quantity' => $item->qty,
                'shipping' => 'N/A',
                'payment' => 0, //cash on delivary
            ];
        });
        $order->orderDetails()->createMany($cartItems->toArray());
        $customer = Customer::find($order);
        $customer->update([
            'number_of_orders' => $customer->number_of_orders + 1
        ]);
        // delete shopping cart
    }

    function GetFeaturedProducts(Store $store)
    {
        $term = request()->query('term', '');
        $perPage = request()->query('PerPage', 25);
        $products = Product::search($term)
            ->where('store_id', $store->id)
            ->whereHas('options')
            ->paginate($perPage);


        return new MessageResponse(
            data: ['products' => ProductResource::collection($products),],
            statusCode: 200
        );
    }
}
