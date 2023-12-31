<?php

namespace App\Api\Controllers\Task;

use App\Api\Requests\AddCategoryRequest;
use App\Api\Resources\CategoryResource;
use Domain\Task\Actions\AddCategoryAction;
use Domain\Task\Actions\DeleteCategoryAction;
use Domain\Task\Models\Category;
use Illuminate\Http\JsonResponse;
use Support\Http\Controllers\ApiController;

class CategoryController extends ApiController
{

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Category::class);
        return $this->sendResponse('', CategoryResource::collection(auth()->user()->categories));
    }

    public function store(AddCategoryRequest $storeCategoryRequest, AddCategoryAction $storeCategoryAction): JsonResponse
    {
        $this->authorize('create', Category::class);

        $data = $storeCategoryRequest->validated();

        $category = $storeCategoryAction->execute($data['name'], auth()->user());

        return $this->sendResponse('', new CategoryResource($category), 201);
    }

    public function delete(DeleteCategoryAction $deleteCategoryAction, Category $category): JsonResponse
    {
        $this->authorize('delete', $category);
        $deleteCategoryAction->execute($category, auth()->user());
        return response()->json([], 204);
    }
}
