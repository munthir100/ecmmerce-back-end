<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Controller;
use Modules\Store\Entities\Category;
use Modules\Admin\Http\Requests\CategoryRequest;
use Modules\Admin\Transformers\CategoryResource;
use Modules\Admin\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('View-Category');
        $categories = request()->store->categories()->useFilters()->dynamicPaginate();

        return $this->responseSuccess(data: [CategoryResource::collection($categories)]);
    }


    public function store(CategoryRequest $request)
    {
        $this->authorize('Create-Category');
        $data = $request->validated();
        $category = request()->store->categories()->create($data);
        $category->uploadMedia();

        return $this->responseCreated('category created successfully', new CategoryResource($category));
    }


    public function show($categoryId)
    {
        $this->authorize('View-Category');
        $category = request()->store->categories()->findOrFail($categoryId);

        return $this->responseSuccess(data: new CategoryResource($category));
    }

    public function update(UpdateCategoryRequest $request, $categoryId)
    {
        $this->authorize('Edit-Category');
        $category = request()->store->categories()->findOrFail($categoryId);
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
        $this->authorize('Delete-Category');
        $category = request()->store->categories()->findOrFail($categoryId);
        $category->delete();

        return $this->responseSuccess('category deleted');
    }
}
