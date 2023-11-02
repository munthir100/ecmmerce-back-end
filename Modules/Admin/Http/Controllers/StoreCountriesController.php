<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\StoreCountriesService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Store\Entities\StoreCountry;
use Modules\Admin\Http\Requests\StoreCountriesRequest;

class StoreCountriesController extends Controller
{
    use AuthorizesRequests;
    protected $storeCountriesService;

    public function __construct(StoreCountriesService $storeCountriesService)
    {
        $this->storeCountriesService = $storeCountriesService;
    }


    public function index()
    {
        $this->authorize('Manage-Store-Countries');
        $countries = $this->storeCountriesService->getStoreCountries(request()->store);

        return $this->responseSuccess('supported countries', ['countries' => $countries]);
    }

    public function store(StoreCountriesRequest $request)
    {
        $this->authorize('Manage-Store-Countries');
        $data = $request->validated();
        $country = request()->store->countries()->findOrFail($data['country_id']);
        $this->storeCountriesService->checkIfCountryExestsInStore(request()->store, $country);
        $this->storeCountriesService->createStoreCountry(request()->store, $country, $data);


        return $this->responseSuccess('country created');
    }

    public function setAsDefault($countryId)
    {
        $this->authorize('Manage-Store-Countries');
        $country = request()->store->countries()->findOrFail($countryId);
        $this->storeCountriesService->checkIfCountryNotExestsInStore(request()->store, $country);
        $this->storeCountriesService->checkIfCountryIsActivated(request()->store->countries(), $countryId);
        $this->storeCountriesService->SetDefaultCountry(request()->store->countries(), $countryId);
        $this->storeCountriesService->setDefaultStoreCurruncy(request()->store, $country->currency_code);

        return $this->responseSuccess('Country is been default');
    }


    public function destroy($countryId)
    {
        $this->authorize('Manage-Store-Countries');
        $country = request()->store->countries()->findOrFail($countryId);
        $this->storeCountriesService->checkIfCountryNotExestsInStore(request()->store, $country);
        $this->storeCountriesService->checkIfCountryIsDefault(request()->store->countries(), $countryId);
        request()->store->countries()->detach($countryId);

        return $this->responseSuccess('country deleted');
    }

    public function toggleActivation($countryId)
    {
        $this->authorize('Manage-Store-Countries');
        $country = request()->store->countries()->findOrFail($countryId);
        $this->storeCountriesService->checkIfCountryNotExestsInStore(request()->store, $country);
        $this->storeCountriesService->checkIfCountryIsDefault(request()->store->countries(), $countryId);
        $storeCountry = StoreCountry::where('country_id', $countryId)->first();

        $storeCountry->update([
            'is_active' => !$storeCountry->is_active
        ]);

        return $this->responseSuccess('Country status updated.');
    }
}
