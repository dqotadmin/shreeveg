<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\CategoryLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\WarehouseCategory;

class CategoryController extends Controller
{
    public function __construct(
        private Category $category,
        private WarehouseCategory $warehouse_category
    ) {
    }

    public function get_categories(): \Illuminate\Http\JsonResponse
    {
        try {

            $warehouseId = auth('api')->user()->warehouse_id;
            $whcategpries = Helpers::warehouseAssignCategories($warehouseId);
            //dd($whcategpries);
            if (!empty($whcategpries)) {
                $categories = $this->category->whereIn('id', $whcategpries)->where('parent_id', 0)->get();
            } else {
                $categories = collect(); // or any default value you prefer
            }

            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function get_childes($id): \Illuminate\Http\JsonResponse
    {
        try {
            $categories = $this->category->where(['parent_id' => $id, 'status' => 1])->get();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function get_products($id): \Illuminate\Http\JsonResponse
    {
        $products = CategoryLogic::products($id); // $id means category id
        //dd($products);
        return response()->json(Helpers::apk_product_data_formatting($products, true), 200);
        // return response()->json(Helpers::product_data_formattingOld(CategoryLogic::products($id), true), 200);
    }

    public function get_all_products($id): \Illuminate\Http\JsonResponse
    {
        try {

            $products = CategoryLogic::all_products($id); // $id means category id
            return response()->json(Helpers::apk_product_data_formatting($products, true), 200);
            // return response()->json(Helpers::product_data_formattingOld(CategoryLogic::all_products($id), true), 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }
}
