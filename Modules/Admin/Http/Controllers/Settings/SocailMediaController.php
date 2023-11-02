<?php

namespace Modules\Admin\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Services\StoreService;
use App\Http\Controllers\Controller;
use App\Http\Responses\MessageResponse;
use Modules\Admin\Http\Requests\UpdateSocialMediaLinksRequest;
use Modules\Admin\Transformers\Settings\SocialMediaLinksResource;

class SocailMediaController extends Controller
{

    public function update(UpdateSocialMediaLinksRequest $request)
    {
        $data = $request->validated();
        $socialMediaLinks = $request->store->socialMediaLinks()->updateOrCreate(
            ['store_id' => request()->store->id],
            $data
        );

        return $this->responseSuccess(
            'social media links updated',
            new SocialMediaLinksResource($socialMediaLinks),
        );
    }
}
