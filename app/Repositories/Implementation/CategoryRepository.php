<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\Interfaces\IDGenerate\IDGenerateServiceInterface;
use Exception;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @var IDGenerateServiceInterface
     */
    private IDGenerateServiceInterface $IDGenerateService;

    /**
     * @param IDGenerateServiceInterface $IDGenerateService
     */
    public function __construct(IDGenerateServiceInterface $IDGenerateService)
    {
        $this->IDGenerateService = $IDGenerateService;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getAllCategories()
    {
        return Category::query()->orderBy('categoryID', 'asc')->get();
    }

    /**
     * @param StoreCategoryRequest $request
     * @return mixed
     */
    public function createCategory(StoreCategoryRequest $request)
    {
        return Category::query()->create([
            'categoryID' => $this->IDGenerateService->categoryID(),
            'categoryName' => data_get($request, 'categoryName'),
        ]);
    }

    /**
     * @param Category $category
     * @return mixed
     */
    public function getCategoryById(Category $category)
    {
        return Category::query()->find($category);
    }

    /**
     * @param Category $category
     * @return mixed
     */
    public function getSubjectsByCategoryId(Category $category)
    {
        return Category::query()->with('subjects')->find($category);
    }

    /**
     * @param UpdateCategoryRequest $request
     * @param Category $category
     * @return mixed
     * @throws Exception
     */
    public function updateCategory(UpdateCategoryRequest $request, Category $category)
    {
        $updated = $category->update([
            'categoryName' => data_get($request, 'categoryName', $category->categoryName),
        ]);

        if (!$updated){
            throw new Exception('Failed to update Category: ' . $category->categoryID);
        }

        return $category;
    }

    /**
     * @param Category $category
     * @return mixed
     * @throws Exception
     */
    public function forceDeleteCategory(Category $category)
    {
        $deleted = $category->delete();

        if (!$deleted){
            throw new Exception('Failed to delete Category: ' . $category->categoryID);
        }

        return $deleted;
    }
}
