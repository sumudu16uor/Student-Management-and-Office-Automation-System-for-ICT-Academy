<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getAllCategories();

    /**
     * @param StoreCategoryRequest $request
     * @return mixed
     */
    public function createCategory(StoreCategoryRequest $request);

    /**
     * @param Category $category
     * @return mixed
     */
    public function getCategoryById(Category $category);

    /**
     * @param Category $category
     * @return mixed
     */
    public function getSubjectsByCategoryId(Category $category);

    /**
     * @param UpdateCategoryRequest $request
     * @param Category $category
     * @return mixed
     */
    public function updateCategory(UpdateCategoryRequest $request, Category $category);

    /**
     * @param Category $category
     * @return mixed
     */
    public function forceDeleteCategory(Category $category);
}
