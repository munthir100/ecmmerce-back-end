<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class StoreService
{
    public function getStore()
    {
        if (auth()->check()) {
            $user = Auth::user();

            if ($user->isAdmin) {
                return $user->admin->store;
            } else {
                return $user->seller->store;
            }
        }
    }

    public function findStoreModel($store, $modelClass, $modelId)
    {
        $model =  $modelClass::where('store_id', $store->id)->find($modelId);

        if (!$model) {
            $modelName = class_basename($modelClass);
            abort(response()->json(['message' => $modelName . ' not found in this store'], 404));
        }

        return $model;
    }
}
