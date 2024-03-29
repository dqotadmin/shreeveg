<?php

namespace App\CentralLogics;

use App\Model\Category;
use App\Model\Product;
use App\Model\WarehouseProduct;
use PhpParser\Node\Stmt\Return_;
use Symfony\Component\Console\Helper\Helper;

class CategoryLogic
{
    public static function parents()
    {
        return Category::where('position', 0)->get();
    }

    public static function child($parent_id)
    {
        return Category::where(['parent_id' => $parent_id])->get();
    }

    public static function products($category_id)
    {

        $warehouse_id = auth('api')->user()->warehouse_id;
        $whProoducts = WarehouseProduct::whereHas('productDetail', function ($query) use ($category_id) {
            $query->active()->withCount(['wishlist', 'active_reviews'])->with('rating')
                ->where('category_id', $category_id);
        })->where('warehouse_id', $warehouse_id)->active()->get();

        return $whProoducts;


        $products = Product::active()->get();
        $product_ids = [];
        foreach ($products as $product) {
            if ($product['category_id'] == $category_id) {
                array_push($product_ids, $product['id']);
            }
            // foreach (json_decode($product['category_ids'], true) as $category) {
            //     if ($category['id'] == $category_id) {
            //         array_push($product_ids, $product['id']);
            //     }
            // }
        }



        return Product::active()->withCount(['wishlist', 'active_reviews'])->with('rating')->whereIn('id', $product_ids)->get();
    }

    public static function all_products($id)
    {
        $warehouse_id = auth('api')->user()->warehouse_id;

        $assign_category_check = Helpers::getWhCategoriesData($id, $warehouse_id);
        if ($assign_category_check) {
            $cate_ids = [];
            array_push($cate_ids, (int)$id);
            foreach (CategoryLogic::child($id) as $ch1) {
                array_push($cate_ids, $ch1['id']);
                foreach (CategoryLogic::child($ch1['id']) as $ch2) {
                    array_push($cate_ids, $ch2['id']);
                }
            }
            $whProoducts = WarehouseProduct::whereHas('productDetail', function ($query) use ($cate_ids) {
                $query->whereIn('category_id', $cate_ids)->active()->withCount(['wishlist', 'active_reviews'])->with('rating');
            })->where('warehouse_id', $warehouse_id)->get();

            return $whProoducts;
        }
        return 'data not found';

        // $products = Product::active()->with('rating', 'active_reviews')->get();
        // $product_ids = [];
        // foreach ($products as $product) {
        //     foreach (json_decode($product['category_ids'], true) as $category) {
        //         if (in_array($category['id'], $cate_ids)) {
        //             array_push($product_ids, $product['id']);
        //         }
        //     }
        // }

        // return Product::active()->withCount(['wishlist'])->with('rating', 'active_reviews')->whereIn('id', $product_ids)->get();
    }
}
