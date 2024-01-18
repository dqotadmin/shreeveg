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
            if(isset(auth('api')->user()->warehouse_id)){
              $warehouse_id =  auth('api')->user()->warehouse_id;
            $category_id = $this->warehouse_category->where(['warehouse_id' => $warehouse_id])->pluck('category_id')->toArray();
            if (!empty($category_id)) {
                $categories = $this->category->whereIn('id', $category_id)->where('parent_id', 0)->get();
            } else {
                $categories = collect(); // or any default value you prefer
            }
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

        return response()->json(Helpers::api_product_data_formatting(CategoryLogic::products($id), true), 200);
        // return response()->json(Helpers::product_data_formattingOld(CategoryLogic::products($id), true), 200);
    }

    public function get_all_products($id): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json(Helpers::api_product_data_formatting(CategoryLogic::all_products($id), true), 200);
            // return response()->json(Helpers::product_data_formattingOld(CategoryLogic::all_products($id), true), 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }
}
