<?php

namespace App\Services;

class StoreService
{
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
