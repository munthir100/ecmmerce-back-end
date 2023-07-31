<?php

namespace Modules\Admin\Http\Controllers\Settings;

use App\Http\Responses\MessageResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Transformers\Settings\SocialMediaLinksResource;

class SocailMediaController extends Controller
{
    public function update(Request $request)
    {
        $store = auth()->user()->admin->store;
        $socialMediaLinks = $store->socialMediaLink()->firstOrCreate([]);

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
