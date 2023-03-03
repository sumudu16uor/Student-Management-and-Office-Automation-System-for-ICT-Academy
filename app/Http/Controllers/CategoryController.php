<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $categoryRepository;

    /**
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return CategoryCollection
     */
    public function index()
    {
        $categories = $this->categoryRepository->getAllCategories();

        return new CategoryCollection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCategoryRequest $request
     * @return CategoryResource
     */
    public function store(StoreCategoryRequest $request)
    {
        $created = $this->categoryRepository->createCategory($request);

        return new CategoryResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return CategoryCollection
     */
    public function show(Category $category)
    {
        $category = $this->categoryRepository->getCategoryById($category);

        return new CategoryCollection($category);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return CategoryCollection
     */
    public function showSubjects(Category $category)
    {
        $category = $this->categoryRepository->getSubjectsByCategoryId($category);

        return new CategoryCollection($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCategoryRequest $request
     * @param Category $category
     * @return CategoryResource
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $updated = $this->categoryRepository->updateCategory($request, $category);

        return new CategoryResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category)
    {
        $deleted = $this->categoryRepository->forceDeleteCategory($category);

        return new JsonResponse([
            'success' => $deleted,
            'status' => 'deleted',
            'data' => new CategoryResource($category),
        ]);
    }
}
