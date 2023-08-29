<?php

namespace Modules\Admin\Http\Controllers;

use App\Traits\ModelsForAdmin;
use Illuminate\Routing\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use Modules\Store\Entities\Category;
use Modules\Admin\Http\Requests\CategoryRequest;
use Modules\Admin\Transformers\CategoryResource;
use Modules\Admin\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    use ModelsForAdmin, ApiResponse;

    

    public function index()
    {
        $categories = Category::useFilters()->forAdmin(auth()->user()->admin->id)->dynamicPaginate();

        return $this->responseSuccess('categories', new CategoryResource($categories));
    }


    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        $data['store_id'] = $request->user()->admin->store->id;
        $category = Category::create($data);
        $category->uploadMedia();

        return $this->responseCreated('category created successfully', new CategoryResource($category));
    }


    public function show($categoryId)
    {
        $category = $this->findAdminModel(auth()->user()->admin, Category::class, $categoryId);

        return $this->responseSuccess(data: new CategoryResource($category));
    }

    public function update(UpdateCategoryRequest $request, $categoryId)
    {
        $category = $this->findAdminModel(auth()->user()->admin, Category::class, $categoryId);
        if ($request->has('image')) {
            $category->clearMediaCollection('image');
            $category->uploadMedia();
        }
        $category->update($request->validated());

        return $this->responseSuccess(
            'category updated',
            ['category' => new CategoryResource($category)]
        );
    }

    public function destroy($categoryId)
    {
        $category = $this->findAdminModel(auth()->user()->admin, Category::class, $categoryId);
        $category->delete();

        return $this->responseSuccess('category deleted');
    }
}
