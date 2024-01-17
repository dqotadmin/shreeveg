<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\RateList;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Model\Product;
use App\Model\Category;
use App\Model\WarehouseCategory;
use App\Model\WarehouseProduct;

class RateListController extends Controller
{
    public function __construct(
        private RateList $rate_list,
        private Product $product,
        private WarehouseCategory $warehouse_categories,
        private Category $category

    ) {
    }

    /**
     * @param Request $request 
     * @return Application|Factory|View
     */
    function index(Request $request): View|Factory|Application
    {

        $query = $this->product;
        $authUser = auth('admin')->user();
        if ($authUser->admin_role_id == 3 || $authUser->admin_role_id == 5) {
            $assign_categories =  $this->warehouse_categories->where('warehouse_id', $authUser->warehouse_id)->pluck('category_id')->toArray();
            $query = $query->whereIn('category_id', $assign_categories);
        }

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search') && $search) {
            $key = explode(' ', $request['search']);
            $query = $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('product_code', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }
        $products = $query->latest()->with('category')->paginate(Helpers::getPagination())->appends($query_param);
        $wh_assign_categories = $this->warehouse_categories->where('warehouse_id', $authUser->warehouse_id)->pluck('category_id');
        $categories = $this->category->whereIn('id', $wh_assign_categories)->get();
        $options = Helpers::getCategoryDropDownColors($categories);

        return view('admin-views.rate_list.index', compact('products', 'options', 'search'));
    }



    function get_product_by_cat(Request $request, $cat_id): \Illuminate\Http\JsonResponse
    {
        $authUser = auth('admin')->user();
        if (in_array($authUser->admin_role_id, [6, 7])) {
            $warehouse_id = $authUser->Store->warehouse_id;
        } elseif (in_array($authUser->admin_role_id, [3, 4])) {
            $warehouse_id = $authUser->warehouse_id;
        }
        $assign_categories = $this->warehouse_categories->where('warehouse_id', $warehouse_id)->pluck('category_id');
        $products = $this->product->whereIn('category_id', $assign_categories)->active();
        if ($cat_id > 0) {
            $products = $products->where('category_id', $cat_id);
        }
        $products = $products->get();
        return response()->json([
            'success' => 1,
            'd_none_class' => 'd-none',
            'view' => view('admin-views.rate_list.product_details', compact('products'))->render(),
        ]);
    }




    /**
     * @param Request $request
     * @return Application|Factory|View
     */




    /**
     * @param Request $request
     * @return JsonResponse
     */


    /**
     * @return Factory|View|Application
     */

    /**
     * @param Request $request
     * @return RedirectResponse
     */

    function store(Request $request)
    { 
        $warehouse_id =   auth('admin')->user()->warehouse_id;
        $array = $request->all();
        $productIds = $array['product_id'];
        $customer_price = 0;
        $discountPercentage = null;
        $chkStatus = false;
        // Process each product ID
        foreach ($productIds as $key => $productId) {
            if(!empty($array['market_price'][$key])){
        
            $chkStatus = true;
                $originalmarketPrice = NULL;
                $findId =   WarehouseProduct::where('warehouse_id', $warehouse_id)->where('product_id', $productId)->first();
                if ($findId) {
                    $product_data =   json_decode($findId->product_details, true);
                    //dd($product_data[0]['approx_piece']);
                }

                // Initialize an array to store combined data for the current product
                $combinedData = [];

                // Process the first slot
                $firstSlotData = [];
                $quantity = $array['1_slot']['quantity'][$key];
                $offerPrice = ($array['1_slot']['offer_price'][$key] === "NaN") ? '0' : $array['1_slot']['offer_price'][$key];
                if(isset($quantity) &&  $quantity != "NaN"){
                    $marketPrice = $array['market_price'][$key] * $quantity;
                    $originalmarketPrice = $array['market_price'][$key];
                    $discountPercentage = ($marketPrice - ($offerPrice / $quantity)) / $marketPrice * 100;
                }
                $unit = $array['unit_id'][$key];
                $margin = $array['1_slot']['margin'][$key];
                $per_unit_price = $array['1_slot']['per_unit_price'][$key];
                //    if($marketPrice === null || $quantity == null){
                //     $marketPrice = 1;
                //     $quantity = 1;
                // }
    
                $firstSlotData[] = [
                    'quantity' => $quantity,
                    'offer_price' => $offerPrice,
                    'market_price' => $marketPrice,
                    'margin' => $margin,
                    'per_unit_price' => $per_unit_price,
                    'discount' => number_format($discountPercentage, 2, '.', ''),
                    'approx_piece' => @$product_data[0]['approx_piece'],
                    'title' => @$product_data[0]['title'],

                ];
                $customer_price = @$per_unit_price;
                // Process the second slot
                $secondSlotData = [];
                $quantity = $array['2_slot']['quantity'][$key];
                $offerPrice = ($array['2_slot']['offer_price'][$key] === "NaN") ? '0' : $array['2_slot']['offer_price'][$key];
                if(isset($quantity) &&  $quantity != "NaN"){
                    $marketPrice = $array['market_price'][$key] * $quantity;
                    $discountPercentage = ($marketPrice - ($offerPrice / $quantity)) / $marketPrice * 100;
                }
                $margin = $array['2_slot']['margin'][$key];
                $per_unit_price = $array['2_slot']['per_unit_price'][$key];
                // if($marketPrice === null || $quantity == null){
                //     $marketPrice = 1;
                //     $quantity = 1;
                //    }
                // Calculate discount percentage
                $secondSlotData[] = [
                    'quantity' => $quantity,
                    'offer_price' => $offerPrice,
                    'market_price' => $marketPrice,
                    'margin' => $margin,
                    'per_unit_price' => $per_unit_price,
                    'discount' => number_format($discountPercentage, 2, '.', ''),
                    'approx_piece' => @$product_data[1]['approx_piece'],
                    'title' => @$product_data[1]['title'],
                ];
                // Process the third slot
                $thirdSlotData = [];
                $quantity = $array['3_slot']['quantity'][$key];
                $offerPrice = ($array['3_slot']['offer_price'][$key] === "NaN") ? '0' : $array['3_slot']['offer_price'][$key];
                if(isset($quantity) &&  $quantity != "NaN"){
                    $marketPrice = $array['market_price'][$key] * $quantity;
                    $discountPercentage = ($marketPrice - ($offerPrice / $quantity)) / $marketPrice * 100;
                }
                $margin = $array['3_slot']['margin'][$key];
                $per_unit_price = $array['3_slot']['per_unit_price'][$key];
                // if($marketPrice === null || $quantity == null ){
                //     $marketPrice = 1;
                //     $quantity = 1;
                //    }
                // Calculate discount percentage

                $thirdSlotData[] = [
                    'quantity' => $quantity,
                    'offer_price' => $offerPrice,
                    'market_price' => $marketPrice,
                    'margin' => $margin,
                    'per_unit_price' => $per_unit_price,
                    'discount' => number_format($discountPercentage, 2, '.', ''),
                    'approx_piece' => @$product_data[2]['approx_piece'],
                    'title' => @$product_data[2]['title'],
                ];
                    // Combine the data for all slots
                $combinedData = array_merge($firstSlotData, $secondSlotData, $thirdSlotData);
                // Store the combined data in the database
                $maxDiscount = 0;
                $discounts = array_column($combinedData, 'discount');
                if ($discounts) {
                    $maxDiscount = max($discounts);
                }
                if ($findId) {
                    $findId->product_details = json_encode($combinedData);
                    $findId->market_price = $originalmarketPrice;
                    $findId->default_unit = $unit;
                    $findId->customer_price = @$customer_price;
                    $findId->discount_upto = @$maxDiscount;
                    $findId->product_rate_updated_date = date('Y-m-d H:i:s');

                    $findId->save();
                } else {
                    WarehouseProduct::create([
                        'product_id' => $productId,
                        'warehouse_id' => $warehouse_id,
                        'market_price' => $originalmarketPrice,
                        'default_unit' => $unit,
                        'product_rate_updated_date' => date('Y-m-d H:i:s'),
                        'customer_price' => @$customer_price,
                        'product_details' => json_encode($combinedData),
                        'discount_upto' => @$maxDiscount,

                    ]);
                }

                //dump($chkStatus);
        }
        
        
    }
    
    if(!$chkStatus){
        Toastr::error(translate('Please fill atleast one market price!'));
        return redirect()->back();
    }
        Toastr::success(translate('Price Updated Successfully!'));
        return redirect()->back();
    }

    // function store(Request $request): RedirectResponse
    // {

    //     $array = $request->all();

    //     // Assuming there are two product IDs
    //     $productIds = $array['product_id'];

    //     // Process each product ID
    //     foreach ($productIds as $productId) {
    //         // Initialize an array to store combined data
    //         $combinedData = [];

    //         // Process the first slot
    //         $firstSlotData = [];
    //         for ($i = 0; $i < count($array['1_slot']['quantity']); $i++) {
    //             $quantity = $array['1_slot']['quantity'][$i];
    //             $offerPrice = $array['1_slot']['offer_price'][$i];
    //             $marketPrice = $array['market_price'][$i];
    //             $margin = $array['1_slot']['margin'][$i];

    //             // Calculate discount percentage
    //             $discountPercentage = ($marketPrice - ($offerPrice/$quantity)) / $marketPrice * 100;

    //             $firstSlotData[] = [
    //                 'quantity' => $quantity,
    //                 'offer_price' => $offerPrice,
    //                 'market_price' => $marketPrice,
    //                 'margin' => $margin,
    //                 'discount' => $discountPercentage,
    //             ];
    //         }

    //         // Process the second slot
    //         $secondSlotData = [];
    //         for ($i = 0; $i < count($array['2_slot']['quantity']); $i++) {
    //             $quantity = $array['2_slot']['quantity'][$i];
    //             $offerPrice = $array['2_slot']['offer_price'][$i];
    //             $marketPrice = $array['market_price'][$i];
    //             $margin = $array['2_slot']['margin'][$i];

    //             // Calculate discount percentage
    //             $discountPercentage = ($marketPrice - ($offerPrice/$quantity)) / $marketPrice * 100;

    //             $secondSlotData[] = [
    //                 'quantity' => $quantity,
    //                 'offer_price' => $offerPrice,
    //                 'market_price' => $marketPrice,
    //                 'margin' => $margin,
    //                 'discount' => $discountPercentage,
    //             ];
    //         }

    //         // Process the third slot
    //         $thirdSlotData = [];
    //         for ($i = 0; $i < count($array['3_slot']['quantity']); $i++) {
    //             $quantity = $array['3_slot']['quantity'][$i];
    //             $offerPrice = $array['3_slot']['offer_price'][$i];
    //             $marketPrice = $array['market_price'][$i];
    //             $margin = $array['3_slot']['margin'][$i];

    //             // Calculate discount percentage
    //             $discountPercentage = ($marketPrice - ($offerPrice/$quantity)) / $marketPrice * 100;

    //             $thirdSlotData[] = [
    //                 'quantity' => $quantity,
    //                 'offer_price' => $offerPrice,
    //                 'market_price' => $marketPrice,
    //                 'margin' => $margin,
    //                 'discount' => $discountPercentage,
    //             ];
    //         }

    //         // Combine the data for all slots
    //         $combinedData = array_merge($combinedData, $firstSlotData, $secondSlotData, $thirdSlotData);

    //         // Encode the combined data to JSON
    //         $jsonCombinedData = json_encode($combinedData, JSON_PRETTY_PRINT);
    //     // dump($productId);
    //     echo $jsonCombinedData;
    //         // Store the combined data in the database
    //         // WarehouseProduct::create([
    //         //     'product_id' => $productId,
    //         //     'product_details' => $jsonCombinedData,
    //         // ]);
    //     }

    //             dd('stop');    

    //             $request->validate([
    //                 'title' => 'required|unique:units',
    //                 'description' => 'required',
    //             ]);

    //                 if (strlen($request->title) > 10) {
    //                     toastr::error(translate('Title is too long!'));
    //                     return back();
    //                 }




    //             //into db
    //             $unit = $this->unit;
    //             $unit->title = $request->title;
    //             $unit->description = $request->description;
    //             $unit->position = $request->position;
    //             $unit->save();


    //             Toastr::success(translate('unit Added Successfully!') );
    //             return redirect()->route('admin.unit.list');

    // }

    
 
}
