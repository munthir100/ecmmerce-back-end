<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Modules\Store\Entities\Category;
use Modules\Admin\Http\Requests\CategoryRequest;
use Modules\Admin\Transformers\CategoryResource;
use Modules\Admin\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    use AuthorizesRequests;
    protected $storeService,$store;
    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
    }
    public function index()
    {
        $this->authorize('View-Category');
        $categories = $this->store->categories()->useFilters()->dynamicPaginate();

        return $this->responseSuccess('categories', new CategoryResource($categories));
    }


    public function store(CategoryRequest $request)
    {
        $this->authorize('Create-Category');
        $data = $request->validated();
        $category = $this->store->categories()->create($data);
        $category->uploadMedia();

        return $this->responseCreated('category created successfully', new CategoryResource($category));
    }


    public function show($categoryId)
    {
        $this->authorize('View-Category');
        $category = $this->storeService->findStoreModel($this->store, Category::class, $categoryId);

        return $this->responseSuccess(data: new CategoryResource($category));
    }

    public function update(UpdateCategoryRequest $request, $categoryId)
    {
        $this->authorize('Edit-Category');
        $category = $this->storeService->findStoreModel($this->store, Category::class, $categoryId);
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
        $category = $this->storeService->findStoreModel($this->store, Category::class, $categoryId);
        $category->delete();

        return $this->responseSuccess('category deleted');
    }
}
