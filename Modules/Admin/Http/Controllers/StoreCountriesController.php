<?php

namespace Modules\Admin\Http\Controllers;

use App\Traits\FindsModels;
use Illuminate\Routing\Controller;
use Modules\Shipping\Entities\Country;
use App\Http\Responses\MessageResponse;
use App\Services\StoreCountriesService;
use Modules\Admin\Http\Requests\StoreCountriesRequest;
use Modules\Store\Entities\StoreCountry;

class StoreCountriesController extends Controller
{
    use FindsModels;
    public $storeCountriesService;
    public function __construct(StoreCountriesService $storeCountriesService)
    {
        $this->storeCountriesService = $storeCountriesService;
    }

    public function index()
    {
        $store = request()->user()->admin->store;
        $countries = $this->storeCountriesService->getStoreCountries($store);

        return new MessageResponse('supported countries', ['countries' => $countries], 200);
    }

    public function store(StoreCountriesRequest $request)
    {
        $store = request()->user()->admin->store;
        $data = $request->validated();
        $country = $this->findModel(Country::class, $data['country_id']);
        $this->storeCountriesService->checkIfCountryExestsInStore($store, $country);
        $this->storeCountriesService->createStoreCountry($store, $country, $data);


        return new MessageResponse('country created', statusCode: 200);
    }

    public function setAsDefault($countryId)
    {
        $store = request()->user()->admin->store;
        $country = $this->findModel(Country::class, $countryId);
        $this->storeCountriesService->checkIfCountryNotExestsInStore($store, $country);
        $this->storeCountriesService->checkIfCountryIsActivated($store->countries(), $countryId);
        $this->storeCountriesService->SetDefaultCountry($store->countries(), $countryId);
        $this->storeCountriesService->setDefaultStoreCurruncy($store,$country->currency_code);
        return new MessageResponse('Country is been default', statusCode: 200);
    }


    public function destroy($countryId)
    {
        $store = request()->user()->admin->store;
        $country = $this->findModel(Country::class, $countryId);
        $this->storeCountriesService->checkIfCountryNotExestsInStore($store, $country);
        $this->storeCountriesService->checkIfCountryIsDefault($store->countries(), $countryId);
        
        $store->countries()->detach($countryId);

        return new MessageResponse('country deleted');
    }

    public function toggleActivation($countryId)
    {
        $store = request()->user()->admin->store;
        $country = $this->findModel(Country::class, $countryId);
        $this->storeCountriesService->checkIfCountryNotExestsInStore($store, $country);
        $this->storeCountriesService->checkIfCountryIsDefault($store->countries(), $countryId);

        $storeCountry = StoreCountry::where('country_id', $countryId)->first();
        $storeCountry->update([
            'is_active' => !$storeCountry->is_active
        ]);

        return new MessageResponse('Country status updated.', statusCode: 200);
    }
}
