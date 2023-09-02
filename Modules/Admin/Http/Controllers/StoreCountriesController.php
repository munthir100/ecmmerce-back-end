<?php

namespace Modules\Admin\Http\Controllers;

use App\Traits\FindsModels;
use App\Services\StoreService;
use Illuminate\Routing\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use Modules\Shipping\Entities\Country;
use App\Http\Responses\MessageResponse;
use App\Services\StoreCountriesService;
use Modules\Store\Entities\StoreCountry;
use Modules\Admin\Http\Requests\StoreCountriesRequest;

class StoreCountriesController extends Controller
{
    protected $storeService, $store, $storeCountriesService;

    public function __construct(StoreCountriesService $storeCountriesService, StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
        $this->storeCountriesService = $storeCountriesService;
    }


    public function index()
    {
        $countries = $this->storeCountriesService->getStoreCountries($this->store);

        return $this->responseSuccess('supported countries', ['countries' => $countries]);
    }

    public function store(StoreCountriesRequest $request)
    {
        $data = $request->validated();
        $country = $this->storeService->findStoreModel($this->store, Country::class, $data['country_id']);
        $this->storeCountriesService->checkIfCountryExestsInStore($this->store, $country);
        $this->storeCountriesService->createStoreCountry($this->store, $country, $data);


        return $this->responseSuccess('country created');
    }

    public function setAsDefault($countryId)
    {
        $country = $this->storeService->findStoreModel($this->store, Country::class, $countryId);
        $this->storeCountriesService->checkIfCountryNotExestsInStore($this->store, $country);
        $this->storeCountriesService->checkIfCountryIsActivated($this->store->countries(), $countryId);
        $this->storeCountriesService->SetDefaultCountry($this->store->countries(), $countryId);
        $this->storeCountriesService->setDefaultStoreCurruncy($this->store, $country->currency_code);

        return $this->responseSuccess('Country is been default');
    }


    public function destroy($countryId)
    {
        $country = $this->storeService->findStoreModel($this->store, Country::class, $countryId);
        $this->storeCountriesService->checkIfCountryNotExestsInStore($this->store, $country);
        $this->storeCountriesService->checkIfCountryIsDefault($this->store->countries(), $countryId);
        $this->store->countries()->detach($countryId);

        return $this->responseSuccess('country deleted');
    }

    public function toggleActivation($countryId)
    {
        $country = $this->storeService->findStoreModel($this->store, Country::class, $countryId);
        $this->storeCountriesService->checkIfCountryNotExestsInStore($this->store, $country);
        $this->storeCountriesService->checkIfCountryIsDefault($this->store->countries(), $countryId);
        $storeCountry = StoreCountry::where('country_id', $countryId)->first();

        $storeCountry->update([
            'is_active' => !$storeCountry->is_active
        ]);

        return $this->responseSuccess('Country status updated.');
    }
}
