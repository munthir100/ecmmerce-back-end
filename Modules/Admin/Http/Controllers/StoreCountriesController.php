<?php

namespace Modules\Admin\Http\Controllers;

use App\Traits\FindsModels;
use Illuminate\Routing\Controller;
use Modules\Shipping\Entities\Country;
use App\Http\Responses\MessageResponse;
use App\Services\StoreCountriesService;
use Essa\APIToolKit\Api\ApiResponse;
use Modules\Admin\Http\Requests\StoreCountriesRequest;
use Modules\Store\Entities\StoreCountry;

class StoreCountriesController extends Controller
{
    use FindsModels,ApiResponse;
    public $storeCountriesService;
    public function __construct(StoreCountriesService $storeCountriesService)
    {
        $this->storeCountriesService = $storeCountriesService;
    }

    public function index()
    {
        $countries = $this->storeCountriesService->getStoreCountries(auth()->user()->admin->store);

        return $this->responseSuccess('supported countries', ['countries' => $countries]);
    }

    public function store(StoreCountriesRequest $request)
    {
        $data = $request->validated();
        $country = $this->findModel(Country::class, $data['country_id']);
        $this->storeCountriesService->checkIfCountryExestsInStore(auth()->user()->admin->store, $country);
        $this->storeCountriesService->createStoreCountry(auth()->user()->admin->store, $country, $data);


        return $this->responseSuccess('country created');
    }

    public function setAsDefault($countryId)
    {
        $country = $this->findModel(Country::class, $countryId);
        $this->storeCountriesService->checkIfCountryNotExestsInStore(auth()->user()->admin->store, $country);
        $this->storeCountriesService->checkIfCountryIsActivated(auth()->user()->admin->store->countries(), $countryId);
        $this->storeCountriesService->SetDefaultCountry(auth()->user()->admin->store->countries(), $countryId);
        $this->storeCountriesService->setDefaultStoreCurruncy(auth()->user()->admin->store,$country->currency_code);

        return $this->responseSuccess('Country is been default');
    }


    public function destroy($countryId)
    {
        $country = $this->findModel(Country::class, $countryId);
        $this->storeCountriesService->checkIfCountryNotExestsInStore(auth()->user()->admin->store, $country);
        $this->storeCountriesService->checkIfCountryIsDefault(auth()->user()->admin->store->countries(), $countryId);
        $this->store->countries()->detach($countryId);

        return $this->responseSuccess('country deleted');
    }

    public function toggleActivation($countryId)
    {
        $country = $this->findModel(Country::class, $countryId);
        $this->storeCountriesService->checkIfCountryNotExestsInStore(auth()->user()->admin->store, $country);
        $this->storeCountriesService->checkIfCountryIsDefault(auth()->user()->admin->store->countries(), $countryId);
        $storeCountry = StoreCountry::where('country_id', $countryId)->first();
        
        $storeCountry->update([
            'is_active' => !$storeCountry->is_active
        ]);

        return $this->responseSuccess('Country status updated.');
    }
}
