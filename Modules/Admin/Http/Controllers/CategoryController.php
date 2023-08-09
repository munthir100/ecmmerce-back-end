<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Store\Entities\Category;
use App\Http\Responses\MessageResponse;
use App\Traits\ModelsForAdmin;
use Modules\Admin\Http\Requests\CategoryRequest;
use Modules\Admin\Transformers\CategoryResource;
use Modules\Admin\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    use ModelsForAdmin;

    public function index()
    {
        $term = request()->get('term', '');
        $perPage = request()->get('perPage', 25);
        $categories = $this->getAdminModels(Category::class, $term, $perPage);

        return new MessageResponse(
            data: ['categories' => CategoryResource::collection($categories)],
        );
    }


    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        $data['store_id'] = $request->user()->admin->store->id;
        $category = Category::create($data);
        $category->uploadMedia();

        return new MessageResponse(
            message: 'category created successfully',
            data: ['category' => new CategoryResource($category)]
        );
    }


    public function show($categoryId)
    {
        $category = $this->findModelOrFail(Category::class, $categoryId);

        return new MessageResponse(
            data: ['category' => new CategoryResource($category)]
        );
    }

    public function update(UpdateCategoryRequest $request, $categoryId)
    {
        $category = $this->findModelOrFail(Category::class, $categoryId);
        if ($request->has('image')) {
            $category->clearMediaCollection('image');
            $category->uploadMedia();
        }
        $category->update($request->validated());

        return new MessageResponse(
            message: 'category updated',
            data: ['category' => new CategoryResource($category)]
        );
    }

    public function destroy($categoryId)
    {
        $category = $this->findModelOrFail(Category::class, $categoryId);
        $category->delete();

        return new MessageResponse(
            message: 'category deleted',
            data: ['category' => new CategoryResource($category)],
        );
    }
}
