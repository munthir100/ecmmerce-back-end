<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Store\Entities\Store;
use Illuminate\Routing\Controller;
use App\Http\Responses\MessageResponse;
use Illuminate\Contracts\Support\Renderable;

class CheckoutController extends Controller
{
    public function checkout(Store $store, Request $request)
    {
        $data = $request->validate([
            'location_id' => 'required|exists:locations,id'
        ]);
    
        $customer = auth()->user()->customer;
        $location = $customer->locations()->find($data['location_id']);
    
        if (!$location) {
            return new MessageResponse(
                message: 'Invalid location',
                statusCode: 400
            );
        }
    
        // Perform the checkout process
    
        return new MessageResponse(
            message: 'Checkout completed successfully',
            statusCode: 200
        );
    }
    
}
