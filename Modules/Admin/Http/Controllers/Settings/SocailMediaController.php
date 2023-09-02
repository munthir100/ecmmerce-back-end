<?php

namespace Modules\Admin\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Services\StoreService;
use Illuminate\Routing\Controller;
use App\Http\Responses\MessageResponse;
use Modules\Admin\Transformers\Settings\SocialMediaLinksResource;

class SocailMediaController extends Controller
{
    protected $storeService, $store, $storeCountriesService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
    }
    public function update(Request $request)
    {
        $socialMediaLinks = $this->store->socialMediaLink()->firstOrCreate([]);

        $data = $request->validate([
            'facebook' => 'nullable|string',
            'snapchat' => 'nullable|string',
            'twitter' => 'nullable|string',
            'tictok' => 'stringnullable|',
            'whatsapp' => 'nullable|string',
            'maroof' => 'stringnullable|',
            'instagram' => 'nullable|string',
            'telegram' => 'nullable|string',
            'google_play' => 'nullable|string',
            'app_store' => 'nullable|string',
        ]);

        $socialMediaLinks->update($data);

        return new MessageResponse(
            'social media links updated',
            new SocialMediaLinksResource($socialMediaLinks),
            200
        );
    }
}
