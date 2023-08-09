<?php

namespace App\Traits;


trait ModelsForAdmin
{
    private function getAdminModels($modelClass, $term, $perPage)
    {
        $admin = auth()->user()->admin;
        return $modelClass::search($term)->forAdmin($admin->id)->paginate($perPage);
    }

    // private function createModelInAdminStore(string $modelClass, array $data)
    // {
    //     $admin = auth()->user()->admin;
    //     $data['store_id'] = $admin->store->id;
    //     $model = $modelClass::create($data);

    //     return $model;
    // }

    private function findAdminModel($modelClass, $modelId)
    {
        $admin = auth()->user()->admin;
        $model = $modelClass::forAdmin($admin->id)->find($modelId);

        if (!$model) {
            $modelName = class_basename($modelClass);
            abort(response()->json(['message' => $modelName . ' not found'], 404));
        }
        return $model;
    }

    // private function deleteAdminModel($modelClass, $modelId)
    // {
    //     $model = $this->findAdminModel($modelClass, $modelId);
    //     $model->delete();
    // }
}
