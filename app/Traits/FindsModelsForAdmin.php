<?php

namespace App\Traits;


trait FindsModelsForAdmin
{
    private function findModelOrFail($modelClass, $modelId)
    {
        $model = $modelClass::forAdmin(auth()->user()->admin->id)->find($modelId);

        if(!$model){
            $modelName = class_basename($modelClass);
            abort(response()->json(['message' => $modelName . ' not found'], 404));
        }
        return $model;

    }
}
