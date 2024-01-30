<?php

namespace App\CentralLogics;

use App\Model\BusinessSetting;
use App\Model\CategoryDiscount;
use App\Model\Currency;
use App\Model\DMReview;
use App\Model\Order;
use App\Model\Review;
use App\Model\FlashDeal;
use App\Model\FlashDealProduct;
use App\Model\WarehouseProduct;
use App\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\Help;
use Symfony\Component\Console\Helper\Helper;

class Helpers
{
    public static  function getWarehouseProductsdetail($whCategories = null)
    {
        $warehouseId = auth('api')->user()->warehouse_id;
        if (empty($whCategories)) {
            $whCategories = self::warehouseAssignCategories($warehouseId);// , 1 , 3 , 10 , 11 , 12 , 2 , 4 , 5 , 8 , 9 10 => 6 11 => 7
        }
       

        return WarehouseProduct::whereHas('productDetail', function ($query) use ($whCategories) {
            $query->whereIn('product_id', $whCategories)->active();
        })->where('warehouse_id', $warehouseId);

        // return WarehouseProduct::whereHas('productDetail', function ($query) use ($whCategories) {
        //     $query->whereIn('category_id', $whCategories)->active()->withCount(['wishlist'])->with(['rating'])->where(['daily_needs' => 1]);
        // })->where('warehouse_id', $warehouseId);
    }

    public static function warehouseAssignCategories($warehouseId)
    {
        $row = [];
        $data = \App\Model\WarehouseCategory::whereHas('getCategory', function ($q) {
            $q->active();
        })->where(['warehouse_id' => $warehouseId])->select('category_id')->pluck('category_id')->toArray();
        if (isset($data)) {
            $row = $data;
        }
        return $row;
    }


    public static  function getWhProductsByProductIds($product_ids)
    {
        $warehouseId = auth('api')->user()->warehouse_id;

        return WarehouseProduct::whereHas('productDetail', function ($query) use ($product_ids) {
            $query->whereIn('id', $product_ids)->active()->withCount(['wishlist'])->with(['rating']);
        })->where('warehouse_id', $warehouseId);
    }

    public static function getParentCategories($categoryIds)
    {
        $categoryModel = \App\Model\Category::get();
        $parentCategoriesArray = [];

        foreach ($categoryIds as $categoryId) {
            // Base case: If $categoryId is zero, skip to the next iteration
            if ($categoryId == 0) {
                continue;
            }

            // Retrieve parent_id for the given category_id
            $parentCategory = $categoryModel->where('id', $categoryId)->pluck('parent_id')->first();

            // Recursive case: Call the function with the parent category_id
            $parentCategories = Helpers::getParentCategories([$parentCategory]);

            // Append the current category_id to the result
            $parentCategories[] = $categoryId;

            // Merge the current result with the overall result array
            $parentCategoriesArray = array_merge($parentCategoriesArray, $parentCategories);
        }

        return $parentCategoriesArray;
    }

    public static function warehouseProductData($product_id, $warehouseId = false)
    {

        if (!$warehouseId) {
            $authUser = auth('admin')->user();
            if (in_array($authUser->admin_role_id, [6, 7])) {
                $warehouseId = $authUser->Store->warehouse_id;
            } elseif (in_array($authUser->admin_role_id, [3, 4, 5])) {
                $warehouseId = $authUser->warehouse_id;
            }
        } //dd($product_id, $warehouseId);
        return WarehouseProduct::where(['warehouse_id' => $warehouseId, 'product_id' => $product_id])->first();
    }


    public static function avgprice($product_id, $orderId)
    {
        $data =  \App\Model\PurchaseWarehouseOrderDetail::whereIn('purchase_warehouse_order_id', $orderId)->where('product_id', $product_id)->get();

        return $data;
    }

    public static function getWhProductOffers($product_id, $warehouseId = false)
    {

        if (!$warehouseId) {
            $authUser = auth('admin')->user();
            if (in_array($authUser->admin_role_id, [6, 7])) {
                $warehouseId = $authUser->Store->warehouse_id;
            } elseif (in_array($authUser->admin_role_id, [3, 4, 5])) {
                $warehouseId = $authUser->warehouse_id;
            }
        }
        // product is  FlashDealProduct
        $products = FlashDeal::query()->whereJsonContains('warehouse_id', $warehouseId)->active()->whereHas('products', function ($qu) use ($product_id) {
            $qu->where('product_id', $product_id);
        })->orderBy('title', 'asc')->get();
        //$products = FlashDeal::query()->whereJsonContains('warehouse_id', $warehouseId)->get();
        //dd(($products), $warehouseId);
        return  $products;
    }

    public static function adminWhOffers($warehouseId = false)
    {

        if (!$warehouseId) {
            $authUser = auth('admin')->user();
            if (in_array($authUser->admin_role_id, [6, 7])) {
                $warehouseId = $authUser->Store->warehouse_id;
            } elseif (in_array($authUser->admin_role_id, [3, 4, 5])) {
                $warehouseId = $authUser->warehouse_id;
            }
        }

        $adminOffers = FlashDeal::where('offer_type', 'one_rupee')->whereJsonContains('warehouse_id', $warehouseId)->active()->whereHas('products')->orderBy('title', 'asc')->get();
        //dd(($adminOffers), $warehouseId);
        return  $adminOffers;
    }

    public static function getWhProductOfferQty($product_id, $deal_id)
    {

        return FlashDealProduct::query()->where('product_id', $product_id)->where('flash_deal_id', $deal_id)->first()->quantity;
    }

    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            $err_keeper[] = ['code' => $index, 'message' => $error[0]];
        }
        return $err_keeper;
    }

    public static function combinations($arrays)
    {
        $result = [[]];
        foreach ($arrays as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property => $property_value]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    public static function variation_price($product, $variation)
    {
        if (empty(json_decode($variation, true))) {
            $result = $product['price'];
        } else {
            $match = json_decode($variation, true)[0];
            $result = 0;
            foreach (json_decode($product['variations'], true) as $property => $value) {
                if ($value['type'] == $match['type']) {
                    $result = $value['price'];
                }
            }
        }
        return self::set_price($result);
    }

    public static function product_data_formattingOld($data, $multi_data = false)
    {

        $storage = [];
        if ($multi_data == true) {
            foreach ($data as $item) {
                $variations = [];
                $item['category_id'] = json_decode($item['category_id']);
                $item['image'] = json_decode($item['image']);
                $item['attributes'] = json_decode($item['attributes']);
                $item['choice_options'] = json_decode($item['choice_options']);

                $categories = gettype($item['category_id']) == 'array' ? $item['category_id'] : json_decode($item['category_id']);
                if (!is_null($categories) && count($categories) > 0) {
                    $ids = [];
                    foreach ($categories as $value) {
                        if ($value->position == 1) {
                            $ids[] = $value->id;
                        }
                    }
                    $item['category_discount'] = CategoryDiscount::active()->where('category_id', $ids)->first();
                } else {
                    $item['category_discount'] = [];
                }

                foreach (json_decode($item['variations'], true) as $var) {
                    $variations[] = [
                        'type' => $var['type'],
                        'price' => (float)$var['price'],
                        'stock' => isset($var['stock']) ? (int)$var['stock'] : (int)0,
                    ];
                }
                $item['variations'] = $variations;

                if (count($item['translations'])) {
                    foreach ($item['translations'] as $translation) {
                        if ($translation->key == 'name') {
                            $item['name'] = $translation->value;
                        }
                        if ($translation->key == 'description') {
                            $item['description'] = $translation->value;
                        }
                    }
                }
                unset($item['translations']);
                array_push($storage, $item);
            }
            $data = $storage;
        } else {

            $variations = [];
            $data['category_id'] = json_decode($data['category_id']);
            $data['image'] = json_decode($data['image']);
            $data['attributes'] = json_decode($data['attributes']);
            $data['choice_options'] = json_decode($data['choice_options']);

            $categories = gettype($data['category_id']) == 'array' ? $data['category_id'] : json_decode($data['category_id']);
            if (!is_null($categories) && ($categories) > 0) {
                $ids[] = $categories;

                $data['category_discount'] = CategoryDiscount::active()->where('category_id', $ids)->first();
            } else {
                $data['category_discount'] = [];
            }

            if (isset($data['variations'])) {

                foreach (json_decode($data['variations'], true) as $var) {
                    $variations[] = [
                        'type' => $var['type'],
                        'price' => (float)$var['price'],
                        'stock' => isset($var['stock']) ? (int)$var['stock'] : (int)0,
                    ];
                }
            }
            $data['variations'] = $variations;
            if (count($data['translations']) > 0) {
                foreach ($data['translations'] as $translation) {
                    if ($translation->key == 'name') {
                        $data['name'] = $translation->value;
                    }
                    if ($translation->key == 'description') {
                        $data['description'] = $translation->value;
                    }
                }
            }
        }

        return $data;
    }


    public static function apk_product_data_formatting($data, $multi_data = false)
    {
 
        $storage = [];
        if ($multi_data == true) {
            foreach ($data as $item) {
                //dump(json_decode($item->productDetail['image']));
                //dd($item);
                $variations = [];
                $item['category_id'] = $item->productDetail->category->id;
                $item['name'] = $item->productDetail->name;
                $item['product_code'] = $item->productDetail->product_code;
                $item['price'] = $item->customer_price;
                $item['rating'] = $item->productDetail->rating;
                $item['tax'] = $item->productDetail->tax;
                $item['tax_type'] = $item->productDetail->tax_type;
                $item['attributes'] = [];

                $item['description'] = $item->productDetail->description;
                $item['unit'] = $item->productDetail->unit->title;
                $item['image'] = json_decode($item->productDetail['image']);
                $item['single_image'] = json_decode($item->productDetail['single_image']);
                $item['choice_options'] = json_decode($item->productDetail['choice_options']);

                if (isset($item)) {

                    foreach (json_decode($item['product_details'], true) as $var) {
                        $variations[] = [
                            'quantity' => $var['quantity'],
                            'discount' =>  $var['discount'],
                            'approx_piece' =>   $var['approx_piece'],
                            'title' =>   $var['title'],
                            'offer_price' =>   $var['offer_price'],
                            'market_price' =>    (int)$var['market_price'],
                        ];
                    }
                }
                $item['variations'] = $variations;

                $item['total_stock'] = $item->total_stock;
                //$item['attributes'] = json_decode($item['attributes']);

                if (count($item->productDetail['translations'])) {
                    foreach ($item->productDetail['translations'] as $translation) {
                        if ($translation->key == 'name') {
                            $item['name_translate'] = $translation->value;
                        }
                        if ($translation->key == 'description') {
                            $item['description_translate'] = $translation->value;
                        }
                    }
                }
                //dd($item);
                //unset($item['translations']);
                array_push($storage, $item);
            }
            $data = $storage;
        } else {

            $variations = [];
            $data['category_id'] = $data->productDetail->category->id;
            $data['name'] = $data->productDetail->name;
            $data['product_code'] = $data->productDetail->product_code;
            $data['price'] = $data->customer_price;
            $data['tax'] = $data->productDetail->tax;
            $data['tax_type'] = $data->productDetail->tax_type;
            $data['attributes'] = [];

            $data['description'] = $data->productDetail->description;
            $data['unit'] = $data->productDetail->unit->title;
            $data['image'] = json_decode($data->productDetail['image']);
            $data['single_image'] = json_decode($data->productDetail['single_image']);
            if (isset($data)) {

                foreach (json_decode($data['product_details'], true) as $var) {
                    $variations[] = [
                        'quantity' => $var['quantity'],
                        'discount' =>  $var['discount'],
                        'approx_piece' =>   $var['approx_piece'],
                        'title' =>   $var['title'],
                        'offer_price' =>   $var['offer_price'],
                        'market_price' =>   $var['market_price'],
                    ];
                }
            }
            $data['variations'] = $variations;

            $data['total_stock'] = $data->total_stock;

            if (count($data['translations']) > 0) {
                foreach ($data['translations'] as $translation) {
                    if ($translation->key == 'name') {
                        $data['name'] = $translation->value;
                    }
                    if ($translation->key == 'description') {
                        $data['description'] = $translation->value;
                    }
                }
            }
        }
       
        return $data;
    }

    public static function product_data_formatting($data, $multi_data = false)
    {

        $storage = [];
        // dd($data->productDetail['image'], $data->productDetail);

        $variations = [];
        $data['category_id'] = json_decode($data->productDetail->category->id); //json_decode($data['category_id']);
        $data['image'] = json_decode($data->productDetail['image']);
        $data['customer_price'] = json_decode($data['customer_price']);
        $data['store_price'] = json_decode($data['store_price']);
        $data['attributes'] = json_decode($data['attributes']);
        $data['choice_options'] = json_decode($data['choice_options']);

        $categories = gettype($data['category_id']) == 'array' ? $data['category_id'] : json_decode($data['category_id']);
        if (!is_null($categories) && ($categories) > 0) {
            $ids[] = $categories;

            $data['category_discount'] = CategoryDiscount::active()->where('category_id', $ids)->first();
        } else {
            $data['category_discount'] = [];
        }

        if (isset($data['variations'])) {

            foreach (json_decode($data['variations'], true) as $var) {
                $variations[] = [
                    'type' => $var['type'],
                    'price' => (float)$var['price'],
                    'stock' => isset($var['stock']) ? (int)$var['stock'] : (int)0,
                ];
            }
        }
        $data['variations'] = $variations;
        if ($variations && count($data['translations']) > 0) {
            foreach ($data['translations'] as $translation) {
                if ($translation->key == 'name') {
                    $data['name'] = $translation->value;
                }
                if ($translation->key == 'description') {
                    $data['description'] = $translation->value;
                }
            }
        }

        //dd($data);
        return $data;
    }

    public static function api_product_data_formatting($data, $multi_data = false)
    {
        $storage = [];
        $multi_image = '';
        //dd($data->productDetail['image'], $data->productDetail->category->id);
        // dd($data);
        foreach ($data as $item) {
            $variations = [];
            $item['category_id'] = isset($item->productDetail->category->id); //json_decode($item['category_id']);
            $item['image'] = json_decode($item->productDetail['image']);
            //$multi_image = json_decode(stripslashes($item->productDetail['image']));
            //dump($multi_image);
            // $item['attributes'] = json_decode($item['attributes']);
            $item['choice_options'] = json_decode($item['choice_options']);

            $categories = gettype($item['category_id']) == 'array' ? $item['category_id'] : json_decode($item['category_id']);
            // if (!is_null($categories) && count($categories) > 0) {
            //     $ids = [];
            //     foreach ($categories as $value) {
            //         if ($value->position == 1) {
            //             $ids[] = $value->id;
            //         }
            //     }
            //     $item['category_discount'] = CategoryDiscount::active()->where('category_id', $ids)->first();
            // } else {
            //     $item['category_discount'] = [];
            // }
            if (isset($item)) {

                foreach (json_decode($item['product_details'], true) as $var) {
                    $variations[] = [
                        'quantity' => $var['quantity'],
                        'discount' =>  $var['discount'],
                        'approx_piece' =>   $var['approx_piece'],
                        'title' =>   $var['title'],
                        'offer_price' =>   $var['offer_price'],
                        'market_price' =>  $var['market_price'],
                    ];
                }
            }
            $item['variations'] = $variations;

            $item['total_stock'] = $item->total_stock;
            // foreach (json_decode($item['variations'], true) as $var) {
            //     $variations[] = [
            //         'type' => $var['type'],
            //         'price' => (float)$var['price'],
            //         'stock' => isset($var['stock']) ? (int)$var['stock'] : (int)0,
            //     ];
            // }
            // $item['variations'] = $variations;

            // if (count($item['translations'])) {
            //     foreach ($item['translations'] as $translation) {
            //         if ($translation->key == 'name') {
            //             $item['name'] = $translation->value;
            //         }
            //         if ($translation->key == 'description') {
            //             $item['description'] = $translation->value;
            //         }
            //     }
            // }
            // unset($item['translations']);
            array_push($storage, $item);
        }

        $data = $storage;



        return $data;
    }


    public static function get_business_settings($name)
    {
        $config = null;
        $data = \App\Model\BusinessSetting::where(['key' => $name])->first();
        if (isset($data)) {
            $config = json_decode($data['value'], true);
            if (is_null($config)) {
                $config = $data['value'];
            }
        }
        return $config;
    }
    public static function getAlphabet()
    {
        // Generate an array containing A to Z
        return range('A', 'Z');
    }

    public static function getWhCategoriesData($catid, $whId)
    {
        $row = [];
        $data = \App\Model\WarehouseCategory::where(['warehouse_id' => $whId, 'category_id' => $catid])->first();
        if (isset($data)) {
            $row = $data;
        }
        return $row;
    }

    public static function currency_code()
    {
        $currency_code = BusinessSetting::where(['key' => 'currency'])->first()->value;
        return $currency_code;
    }

    public static function currency_symbol()
    {
        $currency_symbol = Currency::where(['currency_code' => Helpers::currency_code()])->first()->currency_symbol;
        return $currency_symbol;
    }

    public static function set_symbol($amount)
    {
        $decimal_point_settings = Helpers::get_business_settings('decimal_point_settings');
        $position = Helpers::get_business_settings('currency_symbol_position');
        if (!is_null($position) && $position == 'left') {
            $string = self::currency_symbol() . '' . number_format($amount, $decimal_point_settings);
        } else {
            $string = number_format($amount, $decimal_point_settings) . '' . self::currency_symbol();
        }
        return $string;
    }

    public static function set_price($amount)
    {
        $decimal_point_settings = Helpers::get_business_settings('decimal_point_settings');
        $amount = number_format($amount, $decimal_point_settings, '.', '');

        return $amount;
    }

    public static function send_push_notif_to_device($fcm_token, $data)
    {
        /*https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send*/
        $key = BusinessSetting::where(['key' => 'push_notification_key'])->first()->value;
        /*$project_id = BusinessSetting::where(['key' => 'fcm_project_id'])->first()->value;*/

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array(
            "authorization: key=" . $key . "",
            "content-type: application/json"
        );

        $postdata = '{
            "to" : "' . $fcm_token . '",
            "mutable-content": "true",
            "data" : {
                "title":"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_id":"' . $data['order_id'] . '",
                "type":"' . $data['type'] . '",
                "is_read": 0
              },
             "notification" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_id":"' . $data['order_id'] . '",
                "title_loc_key":"' . $data['order_id'] . '",
                "type":"' . $data['type'] . '",
                "is_read": 0,
                "icon" : "new",
                "sound" : "default"
              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function send_push_notif_to_topic($data)
    {
        /*https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send*/
        $key = BusinessSetting::where(['key' => 'push_notification_key'])->first()->value;
        /*$topic = BusinessSetting::where(['key' => 'fcm_topic'])->first()->value;*/
        /*$project_id = BusinessSetting::where(['key' => 'fcm_project_id'])->first()->value;*/

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array(
            "authorization: key=" . $key . "",
            "content-type: application/json"
        );

        $image = asset('storage/app/public/notification') . '/' . $data['image'];
        $postdata = '{
            "to" : "/topics/grofresh",
            "mutable-content": "true",
            "data" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $image . '",
                "order_id":"' . $data['order_id'] . '",
                "type":"' . $data['type'] . '",
                "is_read": 0
              },
              "notification" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $image . '",
                "order_id":"' . $data['order_id'] . '",
                "title_loc_key":"' . $data['order_id'] . '",
                "type":"' . $data['type'] . '",
                "is_read": 0,
                "icon" : "new",
                "sound" : "default"
              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function rating_count($product_id, $rating)
    {
        return Review::where(['product_id' => $product_id, 'rating' => $rating])->count();
    }

    public static function dm_rating_count($deliveryman_id, $rating)
    {
        return DMReview::where(['delivery_man_id' => $deliveryman_id, 'rating' => $rating])->count();
    }

    public static function tax_calculate($product, $price)
    {

        if ($product['tax_type'] == 'percent') {
            $price_tax = ($price / 100) * $product['tax'];
        } else {
            $price_tax = $product['tax'];
        }
        return $price_tax;
    }

    public static function discount_calculate($product, $price)
    {
        if ($product['discount_type'] == 'percent') {
            $price_discount = ($price / 100) * $product['discount'];
        } else {
            $price_discount = $product['discount'];
        }
        return self::set_price($price_discount);
    }

    public static function category_discount_calculate($category_id, $price)
    {
        $category_discount = CategoryDiscount::active()->where(['category_id' => $category_id])->first();
        if ($category_discount) {
            if ($category_discount['discount_type'] == 'percent') {
                $price_discount = ($price / 100) * $category_discount['discount_amount'];
                if ($category_discount['maximum_amount'] < $price_discount) {
                    $price_discount = $category_discount['maximum_amount'];
                }
            } else {
                $price_discount = $category_discount['discount_amount'];
            }
        } else {
            $price_discount = 0;
        }
        return self::set_price($price_discount);
    }

    public static function max_earning()
    {
        $data = Order::where(['order_status' => 'delivered'])->select('id', 'created_at', 'order_amount')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('m');
            });

        $max = 0;
        foreach ($data as $month) {
            $count = 0;
            foreach ($month as $order) {
                $count += $order['order_amount'];
            }
            if ($count > $max) {
                $max = $count;
            }
        }
        return $max;
    }

    public static function max_orders()
    {
        $data = Order::select('id', 'created_at')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('m');
            });

        $max = 0;
        foreach ($data as $month) {
            $count = 0;
            foreach ($month as $order) {
                $count += 1;
            }
            if ($count > $max) {
                $max = $count;
            }
        }
        return $max;
    }

    public static function order_status_update_message($status)
    {
        if ($status == 'pending') {
            $data = self::get_business_settings('order_pending_message');
        } elseif ($status == 'confirmed') {
            $data = self::get_business_settings('order_confirmation_msg');
        } elseif ($status == 'processing') {
            $data = self::get_business_settings('order_processing_message');
        } elseif ($status == 'out_for_delivery') {
            $data = self::get_business_settings('out_for_delivery_message');
        } elseif ($status == 'delivered') {
            $data = self::get_business_settings('order_delivered_message');
        } elseif ($status == 'delivery_boy_delivered') {
            $data = self::get_business_settings('delivery_boy_delivered_message');
        } elseif ($status == 'del_assign') {
            $data = self::get_business_settings('delivery_boy_assign_message');
        } elseif ($status == 'ord_start') {
            $data = self::get_business_settings('delivery_boy_start_message');
        } elseif ($status == 'returned') {
            $data = self::get_business_settings('returned_message');
        } elseif ($status == 'failed') {
            $data = self::get_business_settings('failed_message');
        } elseif ($status == 'canceled') {
            $data = self::get_business_settings('canceled_message');
        } elseif ($status == 'customer_notify_message') {
            $data = self::get_business_settings('customer_notify_message');
        } else {
            $data = '{"status":"0","message":""}';
        }

        if ($data == null || $data['status'] == 0) {
            return 0;
        }
        return $data['message'];
    }

    public static function day_part()
    {
        $part = "";
        $morning_start = date("h:i:s", strtotime("5:00:00"));
        $afternoon_start = date("h:i:s", strtotime("12:01:00"));
        $evening_start = date("h:i:s", strtotime("17:01:00"));
        $evening_end = date("h:i:s", strtotime("21:00:00"));

        if (time() >= $morning_start && time() < $afternoon_start) {
            $part = "morning";
        } elseif (time() >= $afternoon_start && time() < $evening_start) {
            $part = "afternoon";
        } elseif (time() >= $evening_start && time() <= $evening_end) {
            $part = "evening";
        } else {
            $part = "night";
        }

        return $part;
    }

    public static function remove_dir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        Helpers::remove_dir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public static function get_language_name($key)
    {
        $languages = array(
            "en" => "English",
            "hi" => "हिन्दी",
        );
        return array_key_exists($key, $languages) ? $languages[$key] : $key;
    }

    public static function get_language_name_old($key)
    {
        $languages = array(
            "af" => "Afrikaans",
            "sq" => "Albanian - shqip",
            "am" => "Amharic - አማርኛ",
            "ar" => "Arabic - العربية",
            "an" => "Aragonese - aragonés",
            "hy" => "Armenian - հայերեն",
            "ast" => "Asturian - asturianu",
            "az" => "Azerbaijani - azərbaycan dili",
            "eu" => "Basque - euskara",
            "be" => "Belarusian - беларуская",
            "bn" => "Bengali - বাংলা",
            "bs" => "Bosnian - bosanski",
            "br" => "Breton - brezhoneg",
            "bg" => "Bulgarian - български",
            "ca" => "Catalan - català",
            "ckb" => "Central Kurdish - کوردی (دەستنوسی عەرەبی)",
            "zh" => "Chinese - 中文",
            "zh-HK" => "Chinese (Hong Kong) - 中文（香港）",
            "zh-CN" => "Chinese (Simplified) - 中文（简体）",
            "zh-TW" => "Chinese (Traditional) - 中文（繁體）",
            "co" => "Corsican",
            "hr" => "Croatian - hrvatski",
            "cs" => "Czech - čeština",
            "da" => "Danish - dansk",
            "nl" => "Dutch - Nederlands",
            "en" => "English",
            "en-AU" => "English (Australia)",
            "en-CA" => "English (Canada)",
            "en-IN" => "English (India)",
            "en-NZ" => "English (New Zealand)",
            "en-ZA" => "English (South Africa)",
            "en-GB" => "English (United Kingdom)",
            "en-US" => "English (United States)",
            "eo" => "Esperanto - esperanto",
            "et" => "Estonian - eesti",
            "fo" => "Faroese - føroyskt",
            "fil" => "Filipino",
            "fi" => "Finnish - suomi",
            "fr" => "French - français",
            "fr-CA" => "French (Canada) - français (Canada)",
            "fr-FR" => "French (France) - français (France)",
            "fr-CH" => "French (Switzerland) - français (Suisse)",
            "gl" => "Galician - galego",
            "ka" => "Georgian - ქართული",
            "de" => "German - Deutsch",
            "de-AT" => "German (Austria) - Deutsch (Österreich)",
            "de-DE" => "German (Germany) - Deutsch (Deutschland)",
            "de-LI" => "German (Liechtenstein) - Deutsch (Liechtenstein)",
            "de-CH" => "German (Switzerland) - Deutsch (Schweiz)",
            "el" => "Greek - Ελληνικά",
            "gn" => "Guarani",
            "gu" => "Gujarati - ગુજરાતી",
            "ha" => "Hausa",
            "haw" => "Hawaiian - ʻŌlelo Hawaiʻi",
            "he" => "Hebrew - עברית",
            "hi" => "Hindi - हिन्दी",
            "hu" => "Hungarian - magyar",
            "is" => "Icelandic - íslenska",
            "id" => "Indonesian - Indonesia",
            "ia" => "Interlingua",
            "ga" => "Irish - Gaeilge",
            "it" => "Italian - italiano",
            "it-IT" => "Italian (Italy) - italiano (Italia)",
            "it-CH" => "Italian (Switzerland) - italiano (Svizzera)",
            "ja" => "Japanese - 日本語",
            "kn" => "Kannada - ಕನ್ನಡ",
            "kk" => "Kazakh - қазақ тілі",
            "km" => "Khmer - ខ្មែរ",
            "ko" => "Korean - 한국어",
            "ku" => "Kurdish - Kurdî",
            "ky" => "Kyrgyz - кыргызча",
            "lo" => "Lao - ລາວ",
            "la" => "Latin",
            "lv" => "Latvian - latviešu",
            "ln" => "Lingala - lingála",
            "lt" => "Lithuanian - lietuvių",
            "mk" => "Macedonian - македонски",
            "ms" => "Malay - Bahasa Melayu",
            "ml" => "Malayalam - മലയാളം",
            "mt" => "Maltese - Malti",
            "mr" => "Marathi - मराठी",
            "mn" => "Mongolian - монгол",
            "ne" => "Nepali - नेपाली",
            "no" => "Norwegian - norsk",
            "nb" => "Norwegian Bokmål - norsk bokmål",
            "nn" => "Norwegian Nynorsk - nynorsk",
            "oc" => "Occitan",
            "or" => "Oriya - ଓଡ଼ିଆ",
            "om" => "Oromo - Oromoo",
            "ps" => "Pashto - پښتو",
            "fa" => "Persian - فارسی",
            "pl" => "Polish - polski",
            "pt" => "Portuguese - português",
            "pt-BR" => "Portuguese (Brazil) - português (Brasil)",
            "pt-PT" => "Portuguese (Portugal) - português (Portugal)",
            "pa" => "Punjabi - ਪੰਜਾਬੀ",
            "qu" => "Quechua",
            "ro" => "Romanian - română",
            "mo" => "Romanian (Moldova) - română (Moldova)",
            "rm" => "Romansh - rumantsch",
            "ru" => "Russian - русский",
            "gd" => "Scottish Gaelic",
            "sr" => "Serbian - српски",
            "sh" => "Serbo-Croatian - Srpskohrvatski",
            "sn" => "Shona - chiShona",
            "sd" => "Sindhi",
            "si" => "Sinhala - සිංහල",
            "sk" => "Slovak - slovenčina",
            "sl" => "Slovenian - slovenščina",
            "so" => "Somali - Soomaali",
            "st" => "Southern Sotho",
            "es" => "Spanish - español",
            "es-AR" => "Spanish (Argentina) - español (Argentina)",
            "es-419" => "Spanish (Latin America) - español (Latinoamérica)",
            "es-MX" => "Spanish (Mexico) - español (México)",
            "es-ES" => "Spanish (Spain) - español (España)",
            "es-US" => "Spanish (United States) - español (Estados Unidos)",
            "su" => "Sundanese",
            "sw" => "Swahili - Kiswahili",
            "sv" => "Swedish - svenska",
            "tg" => "Tajik - тоҷикӣ",
            "ta" => "Tamil - தமிழ்",
            "tt" => "Tatar",
            "te" => "Telugu - తెలుగు",
            "th" => "Thai - ไทย",
            "ti" => "Tigrinya - ትግርኛ",
            "to" => "Tongan - lea fakatonga",
            "tr" => "Turkish - Türkçe",
            "tk" => "Turkmen",
            "tw" => "Twi",
            "uk" => "Ukrainian - українська",
            "ur" => "Urdu - اردو",
            "ug" => "Uyghur",
            "uz" => "Uzbek - o‘zbek",
            "vi" => "Vietnamese - Tiếng Việt",
            "wa" => "Walloon - wa",
            "cy" => "Welsh - Cymraeg",
            "fy" => "Western Frisian",
            "xh" => "Xhosa",
            "yi" => "Yiddish",
            "yo" => "Yoruba - Èdè Yorùbá",
            "zu" => "Zulu - isiZulu",
        );
        return array_key_exists($key, $languages) ? $languages[$key] : $key;
    }

    public static function get_default_language()
    {
        $data = self::get_business_settings('language');
        $default_lang = 'en';
        if ($data && array_key_exists('code', $data)) {
            foreach ($data as $lang) {
                if ($lang['default'] == true) {
                    $default_lang = $lang['code'];
                }
            }
        }

        return $default_lang;
    }

    public static function upload(string $dir, string $format, $image = null)
    {
        if ($image != null) {
            $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            Storage::disk('public')->put($dir . $imageName, file_get_contents($image));
        } else {
            $imageName = 'def.png';
        }

        return $imageName;
    }

    public static function language_load()
    {
        if (\session()->has('language_settings')) {
            $language = \session('language_settings');
        } else {
            $language = BusinessSetting::where('key', 'language')->first();
            \session()->put('language_settings', $language);
        }
        return $language;
    }

    public static function update(string $dir, $old_image, string $format, $image = null)
    {
        if (Storage::disk('public')->exists($dir . $old_image)) {
            Storage::disk('public')->delete($dir . $old_image);
        }
        $imageName = Helpers::upload($dir, $format, $image);
        return $imageName;
    }

    public static function delete($full_path)
    {
        if (Storage::disk('public')->exists($full_path)) {
            Storage::disk('public')->delete($full_path);
        }
        return [
            'success' => 1,
            'message' => 'Removed successfully !'
        ];
    }

    public static function setEnvironmentValue($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        if (is_bool(env($envKey))) {
            $oldValue = var_export(env($envKey), true);
        } else {
            $oldValue = env($envKey);
        }

        if (strpos($str, $envKey) !== false) {
            $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
        } else {
            $str .= "{$envKey}={$envValue}\n";
        }
        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
        return $envValue;
    }

    public static function requestSender($request): array
    {
        $remove = array("http://", "https://", "www.");
        $url = str_replace($remove, "", url('/'));

        $post = [
            base64_decode('dXNlcm5hbWU=') => $request['username'], //un
            base64_decode('cHVyY2hhc2Vfa2V5') => $request['purchase_key'], //pk
            base64_decode('c29mdHdhcmVfaWQ=') => base64_decode(env(base64_decode('U09GVFdBUkVfSUQ='))), //sid
            base64_decode('ZG9tYWlu') => $url,
        ];

        //session()->put('domain', 'https://' . preg_replace("#^[^:/.]*[:/]+#i", "", $request['domain']));

        $ch = curl_init('https://check.6amtech.com/api/v1/domain-register');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);
        curl_close($ch);

        try {
            if (base64_decode(json_decode($response, true)['active'])) {
                return [
                    'active' => (int)base64_decode(json_decode($response, true)['active'])
                ];
            }
            return [
                'active' => 0
            ];
        } catch (\Exception $exception) {
            return [
                'active' => 1
            ];
        }
    }

    public static function getPagination()
    {
        $pagination_limit = BusinessSetting::where('key', 'pagination_limit')->first();
        return $pagination_limit->value;
    }

    public static function remove_invalid_charcaters($str)
    {
        return str_ireplace(['"', ',', ';', '<', '>', '?'], ' ', $str);
    }

    public static function get_delivery_charge($distance)
    {
        $config = self::get_business_settings('delivery_management');

        if ($config['status'] != 1) {
            $delivery_charge = BusinessSetting::where(['key' => 'delivery_charge'])->first()->value;
            return $delivery_charge;
        } else {
            $delivery_charge = 0;

            $minimum_shipping_charge = $config['min_shipping_charge'];
            $shipping_charge = $config['shipping_per_km'];

            $delivery_charge = $shipping_charge * $distance;

            if ($delivery_charge > $minimum_shipping_charge) {
                return self::set_price($delivery_charge);
            } else {
                return self::set_price($minimum_shipping_charge);
            }
        }
    }

    public static function module_permission_check($mod_name)
    {
        $permission = auth('admin')->user()->role->module_access;

        if (isset($permission) && in_array($mod_name, (array)json_decode($permission)) == true) {
            return true;
        }

        if (auth('admin')->user()->admin_role_id == 1) {
            return true;
        }
        return false;
    }

    public static function file_remover(string $dir, $image)
    {
        if (!isset($image)) return true;

        if (Storage::disk('public')->exists($dir . $image)) Storage::disk('public')->delete($dir . $image);

        return true;
    }

    public static function generate_referer_code()
    {
        $ref_code = Str::random('20');
        if (User::where('referral_code', '=', $ref_code)->exists()) {
            return generate_referer_code();
        }
        return $ref_code;
    }

    public static function gen_mpdf($view, $file_prefix, $file_postfix)
    {
        $mpdf = new \Mpdf\Mpdf(['default_font' => 'FreeSerif', 'mode' => 'utf-8', 'format' => [190, 250]]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf_view = $view;
        $mpdf_view = $mpdf_view->render();
        $mpdf->WriteHTML($mpdf_view);
        $mpdf->Output($file_prefix . $file_postfix . '.pdf', 'D');
    }

    // function for unique data 
    public static function  uniqueRule($table, $column, $ignoreId = null)
    {
        return function ($attribute, $value) use ($table, $column, $ignoreId) {
            $query = DB::table($table)->where($column, $value);

            if ($ignoreId !== null) {
                $query->where('id', '!=', $ignoreId);
            }

            return $query->count() === 0;
        };
    }

    public static function getCategoryDropDown($categories, $parentId = 0, $level = 0, $selected = 0)
    {
        $categories_array = [];
        if (!empty($categories)) {
            $index = 0;
            foreach ($categories as $category) {
                $categories_array[$index]['id'] = $category->id;
                $categories_array[$index]['name'] = $category->name;
                $categories_array[$index]['parent_id'] = $category->parent_id;
                $index++;
            }
        }
        $options = self::buildCategoryOptions($categories_array, $parentId, $level, $selected);
        return $options;
    }

    public static function buildCategoryOptions($categories, $parentId = 0, $level = 0, $selected = 0)
    {
        $html = '';

        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                if (is_array($selected))
                    $sel = (in_array($category['id'], $selected)) ? 'selected' : '';
                else
                    $sel = ($category['id'] == $selected) ? 'selected' : '';

                $name = str_repeat("&nbsp;", $level * 5) . '<strong>' . $category['name'] . '</strong>'; // Indent based on level and make text bold
                $colorClass = isset($colors[$level]) ? $colors[$level] : '';
                $html .= "<option value='{$category['id']}' {$sel} class='{$colorClass}' > $name</option>";

                $html .= self::buildCategoryOptions($categories, $category['id'], $level + 1, $selected);
            }
        }
        return $html;
    }
    public static function getCategoryDropDownColors($categories, $parentId = 0, $level = 0, $selected = 0)
    {
        $categories_array = [];
        if (!empty($categories)) {
            $index = 0;
            foreach ($categories as $category) {
                $categories_array[$index]['id'] = $category->id;
                $categories_array[$index]['name'] = $category->name;
                $categories_array[$index]['parent_id'] = $category->parent_id;
                $index++;
            }
        }
        $options = self::buildCategoryOptionsColors($categories_array, $parentId, $level, $selected);
        return $options;
    }

    public static function buildCategoryOptionsColors($categories, $parentId = 0, $level = 0, $selected = 0)
    {
        $html = '';
        $colors = ['text-primary', 'text-success', 'text-warning', 'text-danger']; // Add more colors as needed

        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                if (is_array($selected))
                    $sel = (in_array($category['id'], $selected)) ? 'selected' : '';
                else
                    $sel = ($category['id'] == $selected) ? 'selected' : '';

                $name = str_repeat("&nbsp;", $level * 5) . '<strong>' . $category['name'] . '</strong>'; // Indent based on level and make text bold
                $colorClass = isset($colors[$level]) ? $colors[$level] : '';
                $html .= "<option value='{$category['id']}' {$sel} class='{$colorClass}' > $name</option>";

                $html .= self::buildCategoryOptionsColors($categories, $category['id'], $level + 1, $selected);
            }
        }
        return $html;
    }
}



function translate($key)
{
    $local = session()->has('local') ? session('local') : 'en';
    App::setLocale($local);

    try {
        $lang_array = include(base_path('resources/lang/' . $local . '/messages.php'));
        $processed_key = ucfirst(str_replace('_', ' ', Helpers::remove_invalid_charcaters($key)));

        if (!array_key_exists($key, $lang_array)) {

            // Stop Creating Label Auto
            //$lang_array[$key] = $processed_key;
            //$str = "<?php return " . var_export($lang_array, true) . ";";
            //file_put_contents(base_path('resources/lang/' . $local . '/messages.php'), $str);

            $result = $processed_key;
        } else {
            $result = __('messages.' . $key);
        }
    } catch (Exception $exception) {
        $result = __('messages.' . $key);
    }

    return $result;
}
