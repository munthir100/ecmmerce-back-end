<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait FindsModelsForAdmin
{
    private function findModelOrFail($modelClass, $modelId)
    {
        $model = $modelClass::forAdmin(auth()->id())->findOrFail($modelId);

        if(!$model){
            $modelName = class_basename($modelClass);
            abort(response()->json(['message' => $modelName . ' not found'], 404));
        }
        return $model;

    }
}
