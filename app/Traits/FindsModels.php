<?php

namespace App\Traits;


trait FindsModels
{
    private function findModel($modelClass, $modelId)
    {
        $model = $modelClass::find($modelId);

        if (!$model) {
            $modelName = class_basename($modelClass);
            abort(response()->json(['message' => $modelName . ' not found'], 404));
        }
        return $model;
    }
}
