<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\ProductCategory;
use App\Services\ProductCategoryService;
use App\DataTransferObjects\ProductCategoryDTO;
use App\Http\Requests\StoreProductCategoryRequest;
use App\EloquentProxies\ProductCategoryEloquentProxies;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{

    private ProductCategoryEloquentProxies $productCategoryEloquentProxies;

    public function __construct(ProductCategoryEloquentProxies $productCategoryEloquentProxies)
    {
        $this->productCategoryEloquentProxies = $productCategoryEloquentProxies;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $perPage = 50;
        $modelsProductCategories = $this->productCategoryEloquentProxies->allWithPaginate($perPage);
        return view('admin.product-category.index')->with('modelsProductCategories', $modelsProductCategories);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $productCategoryDTO = ProductCategoryDTO::getEmptyDTO();
        if($request->old()) {
            $productCategoryDTO = ProductCategoryDTO::loadFromArray($request->old());
        }
        return view('admin.product-category.create', [
            'productCategoryDTO' => $productCategoryDTO,
            'modelsProductCategory' => $this->productCategoryEloquentProxies->all(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Request $request, int $id)
    {
        $modelProductCategory = $this->productCategoryEloquentProxies->getByIdOrFail($id);
        if($request->old()) {
            $productCategoryDTO = ProductCategoryDTO::loadFromArray($request->old());
            $productCategoryDTO->setId($modelProductCategory->id);
        } else {
            $productCategoryDTO = ProductCategoryDTO::loadFromModel($modelProductCategory);
        }
        return view('admin.product-category.edit', [
            'productCategoryDTO' => $productCategoryDTO,
            'modelsProductCategory' => $this->productCategoryEloquentProxies
                ->allWithoutId($productCategoryDTO->getId()),
        ]);
    }

    /**
     * @param StoreProductCategoryRequest $request
     * @param ProductCategoryService $productCategoryService
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(
        StoreProductCategoryRequest $request,
        ProductCategoryService $productCategoryService
    )
    {
        $productCategoryData = ProductCategoryDTO::loadFromRequest($request);
        $modelProductCategory = new ProductCategory();
        $productCategoryService->saveProductCategory(
            $modelProductCategory,
            $productCategoryData
        );
        return redirect()->route('admin.product-category.index')->with('success','Категория успешно создана');
    }

    /**
     * @param StoreProductCategoryRequest $request
     * @param int $id
     * @param ProductCategoryService $productCategoryService
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(
        int $id,
        StoreProductCategoryRequest $request,
        ProductCategoryService $productCategoryService
    )
    {
        $productCategoryData = ProductCategoryDTO::loadFromRequest($request);
        $modelProductCategory = $this->productCategoryEloquentProxies->getByIdOrFail($id);
        $productCategoryService->saveProductCategory(
            $modelProductCategory,
            $productCategoryData
        );
        return redirect()->route('admin.product-category.index')->with('success','Категория успешно создана');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(int $id)
    {
        $modelProductCategory = $this->productCategoryEloquentProxies->getByIdOrFail($id);
        $modelProductCategory->delete();
        return redirect()->route('admin.product-category.index')->with('success','Post deleted successfully');
    }
}
