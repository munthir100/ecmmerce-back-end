<?php

namespace App\Traits;


trait ModelsForAdmin
{
    private function findAdminModel($admin,$modelClass, $modelId)
    {
        $model = $modelClass::forAdmin($admin->id)->find($modelId);

        if (!$model) {
            $modelName = class_basename($modelClass);
            abort(response()->json(['message' => $modelName . ' not found'], 404));
        }
        return $model;
    }
}
