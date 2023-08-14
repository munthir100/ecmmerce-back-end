<?php

namespace App\Traits;


trait ModelsForStore
{
    static function findModelById($store, $modelClass, $modelId)
    {
        $model = $modelClass::where('store_id', $store->id)
            ->findOrFail($modelId);
        if (!$model) {
            $modelName = class_basename($modelClass);
            abort(response()->json(['message' => $modelName . ' not found'], 404));
        }

        return $model;
    }
}
