<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait FindsModelsForAdmin
{
    private function findModelOrFail($modelClass, $modelId)
    {
        try {
            $model = $modelClass::forAdmin(auth()->id())->findOrFail($modelId);
            return $model;
        } catch (ModelNotFoundException $e) {
            $modelName = class_basename($modelClass);
            abort(response()->json(['message' => $modelName . ' not found'], 404));
        }
    }
}
