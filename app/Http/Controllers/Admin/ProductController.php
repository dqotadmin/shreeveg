<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Category;
use App\Model\Unit;
use App\Model\WarehouseProduct;
use App\Model\FlashDealProduct;
use App\Model\Product;
use App\Model\Review;
use App\Model\WarehouseCategory;
use App\Model\Tag;
use App\Model\Translation;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function __construct(
        private BusinessSetting $business_setting,
        private Category $category,
        private Unit $unit,
        private Product $product,
        private Review $review,
        private Tag $tag,
        private Translation $translation,
        private WarehouseCategory $warehouse_categories,
        private WarehouseProduct $warehouse_products
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function variant_combination(Request $request): \Illuminate\Http\JsonResponse
    {
        $options = [];
        $price = $request->price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('', $request[$name]);
                $options[] = explode(',', $my_str);
            }
        }

        $result = [[]];
        foreach ($options as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property => $property_value]);
                }
            }
            $result = $tmp;
        }
        $combinations = $result;
        return response()->json([
            'view' => view('admin-views.product.partials._variant-combinations', compact('combinations', 'price', 'product_name'))->render(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_categories(Request $request): \Illuminate\Http\JsonResponse
    {
        $cat = $this->category->where('deleted_at', null)->where(['parent_id' => $request->parent_id])->get();
        $res = '<option value="' . 0 . '" disabled selected>---Select---</option>';
        foreach ($cat as $row) {
            if ($row->id == $request->sub_category) {
                $res .= '<option value="' . $row->id . '" selected >' . $row->name . '</option>';
            } else {
                $res .= '<option value="' . $row->id . '">' . $row->name . '</option>';
            }
        }
        return response()->json([
            'options' => $res,
        ]);
    }



    /**
     * @param Request $request
     * @return Factory|View|Application
     */
    public function list(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $stock_limit = $this->business_setting->where('key', 'minimum_stock_limit')->first()->value;
        $query = $this->product;
        $authUser = auth('admin')->user();
        if ($authUser->admin_role_id == 3 || $authUser->admin_role_id == 5) {
            $assign_categories =  $this->warehouse_categories->where('warehouse_id', $authUser->warehouse_id)->pluck('category_id')->toArray();
            $query = $query->whereIn('category_id', $assign_categories)->where('status', 1);
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

        return view('admin-views.product.list', compact('products', 'search', 'stock_limit'));
    }

    /**
     * @return Factory|View|Application
     */
    public function add(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        //Category Dropdown
        $categories = $this->category->get();
        $options = Helpers::getCategoryDropDown($categories);
        $units =  $this->unit->get();
        $groups = \App\Model\Group::whereStatus(1)->pluck('name', 'id')->toArray();
        return view('admin-views.product.add', compact('options', 'units', 'groups'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        $key = explode(' ', $request['search']);
        $products = $this->product->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->get();
        return response()->json([
            'view' => view('admin-views.product.partials._table', compact('products'))->render(),
        ]);
    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function view($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $product = $this->product->where(['id' => $id])->first();
        $reviews = $this->review->where(['product_id' => $id])->latest()->paginate(20);
        $product_id = isset($id) ? $id : 0;
        return view('admin-views.product.view', compact('product', 'reviews', 'product_id'));
    }

    public function prices_by_wareohuse($warehouse_id, $product_id)
    {
        $data =  $this->warehouse_products->where('warehouse_id', $warehouse_id)->where('product_id', $product_id)->pluck('product_details');
        // Decode JSON data to an associative array

        return response()->json([
            'view' => view('admin-views.product.render_warehouse_price', compact('data'))->render()
        ]);
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {

        Validator::make($request->all(), [
            'name' => 'required|unique:products',
            'description' => 'required',
            'category_id' => 'required',
            'product_code' => 'required|unique:products',
            'unit_id' => 'required',
            'images' => 'required',
        ], [
            'name.required' => translate('Product name is required!'),
            'name.unique' => translate('Product name must be unique!'),
            'category_id.required' => translate('category  is required!'),
            'product_code.required' => translate('Product code is required!'),
            'product_code.unique' => translate('Product code must be unique!'),
        ]);


        $img_names = [];
        if (!empty($request->file('images'))) {
            foreach ($request->images as $img) {
                $image_data = Helpers::upload('product/', 'png', $img);
                $img_names[] = $image_data;
            }
            $image_data = json_encode($img_names);
        } else {
            $image_data = json_encode([]);
        }


        $single_img_names = [];
        if (!empty($request->file('single_image'))) {
            foreach ($request->single_image as $single_image) {
                $single_img_data = Helpers::upload('product/single/', 'png', $single_image);
                $single_img_names[] = $single_img_data;
            }
            $single_img_names = json_encode($single_img_names);
        } else {
            $single_img_names = json_encode([]);
        }

        $p = $this->product;
        $p->name = $request->name[array_search('en', $request->lang)];
        $p->description = $request->description[array_search('en', $request->lang)];

        $p->category_id = $request->category_id;
        $p->product_code = $request->product_code;

        $p->unit_id = $request->unit_id;
        $p->image = $image_data;
        $p->single_image = $single_img_names;
        $p->maximum_order_quantity = $request->maximum_order_quantity;
        $p->status = $request->status ? $request->status : 0;
        $p->group_ids = json_encode($request->group_ids);
        //dd($p);ALTER TABLE `products` ADD `group_ids` TEXT NULL AFTER `daily_needs`;
        $p->save();

        $data = [];
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                $data[] = array(
                    'translationable_type' => 'App\Model\Product',
                    'translationable_id' => $p->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                );
            }
            if ($request->description[$index] && $key != 'en') {
                $data[] = array(
                    'translationable_type' => 'App\Model\Product',
                    'translationable_id' => $p->id,
                    'locale' => $key,
                    'key' => 'description',
                    'value' => $request->description[$index],
                );
            }
        }


        $this->translation->insert($data);

        return response()->json([], 200);
    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function edit($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $product = $this->product->withoutGlobalScopes()->with('translations')->find($id);
        $product_category = $product->category_id;
        $categories = $this->category->get();
        $options = Helpers::getCategoryDropDown($categories, 0, 0, $product->category_id);
        $units =  $this->unit->get();
        // dd($product_category);
        return view('admin-views.product.edit', compact('product', 'product_category', 'options', 'units'));
    }

    public function warehouse_edit($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $warehouse_id = auth('admin')->user()->warehouse_id;
        $assign_categories =  $this->warehouse_categories->where('warehouse_id', $warehouse_id)->pluck('category_id'); //    0 => 1 // 1 => 4  // 2 => 22
        $warehouse_products =  $this->warehouse_products->where('warehouse_id', $warehouse_id)->where('product_id', $id)->first(); //    0 => 1 // 1 => 4  // 2 => 22
        $product = $this->product->withoutGlobalScopes()->with('translations')->find($id);
        $product_category = $product->category_id;
        $categories = $this->category->get();
        $options = Helpers::getCategoryDropDown($categories, 0, 0, $product->category_id);
        $units =  $this->unit->get();
        return view('admin-views.product.warehouse_edit', compact('product', 'product_category', 'options', 'units', 'warehouse_products'));
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        if (auth('admin')->user()->admin_role_id == 3) {
            $product = $this->warehouse_products->find($request->id);
            $product->status = $request->status;
            $product->save();
            Toastr::success(translate('Product status updated!'));
            return back();
        } else {

            $product = $this->product->find($request->id);
            $product->status = $request->status;
            $product->save();
            Toastr::success(translate('Product status updated!'));
            return back();
        }
    }

    // public function order(Request $request) 
    // {
    //     $product = $this->product->find($request->product_id);
    //     $existingProduct = $this->product->where('ordering', $request->ordering)
    //     ->where('id', '!=', $request->product_id)
    //     ->first();
    //     if ($existingProduct) {
    //         // Ordering value already exists, choose another value or handle it as needed
    //         return response()->json(['ordering_exist' => translate('Ordering value already exists. Choose another value.')]);
    //     }
    //     // Set the new ordering value
    //     $product->ordering = $request->ordering;
    //     $product->save();

    //     return response()->json(['message' => translate('Product ordering updated!')]);
    // }

    public function order(Request $request)
    {
        $product = $this->product->find($request->product_id);
        $existingProducts = $this->product->where('ordering', $request->ordering)
            ->where('id', '!=', $request->product_id)
            ->get(); // we check if this ordering is already exist or not
        if ($existingProducts->isNotEmpty()) {

            // Ordering value already exists, so increment ordering values for products greater than or equal to the given value
            foreach ($existingProducts as $productExist) {
                $productExist->ordering = $request->ordering + 1;
                $productExist->save();
            }
        } else {
            // Set the new ordering value
            $product->ordering = $request->ordering;
            $product->save();
        }

        return response()->json(['message' => translate('Product ordering updated!')]);
    }




    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function feature(Request $request): \Illuminate\Http\RedirectResponse
    {
        $product = $this->product->find($request->id);
        $product->is_featured = $request->is_featured;
        $product->save();
        Toastr::success(translate('product feature status updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function daily_needs(Request $request): \Illuminate\Http\JsonResponse
    {
        $product = $this->product->find($request->id);
        $product->daily_needs = $request->status;
        $product->save();
        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {

        Validator::make($request->all(), [
            'name' => 'required|unique:products',
            'description' => 'required',
            'category_id' => 'required',
            'product_code' => 'required|unique:products',
            'unit_id' => 'required',
            'images' => 'required',
        ], [
            'name.required' => translate('Product name is required!'),
            'name.unique' => translate('Product name must be unique!'),
            'category_id.required' => translate('category  is required!'),
            'product_code.required' => translate('Product code is required!'),
            'product_code.unique' => translate('Product code must be unique!'),
        ]);

        $p = $this->product->find($id);



        $img_names = json_decode($p->image);
        if (!empty($request->file('images'))) {
            foreach ($request->images as $img) {
                $image_data = Helpers::upload('product/', 'png', $img);
                $img_names[] = $image_data;
            }
            $image_data = json_encode($img_names);
            $p->image = $image_data;
        }


        $single_img_names = json_decode($p->single_image);
        if (!empty($request->file('single_image'))) {
            foreach ($request->single_image as $single_image) {
                $single_img_data = Helpers::upload('product/single/', 'png', $single_image);
                $single_img_names[] = $single_img_data;
            }
            $single_img_names = json_encode($single_img_names);
            $p->single_image = $single_img_names;
        }


        $p->name = $request->name[array_search('en', $request->lang)];
        $p->description = $request->description[array_search('en', $request->lang)];

        $p->category_id = $request->category_id;
        $p->product_code = $request->product_code;

        $p->unit_id = $request->unit_id;

        $p->maximum_order_quantity = $request->maximum_order_quantity;
        $p->status = $request->status ? $request->status : 0;
        $p->group_ids = json_encode($request->group_ids);
        //dd($p);
        $p->save();

        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                $this->translation->updateOrInsert(
                    [
                        'translationable_type'  => 'App\Model\Product',
                        'translationable_id'    => $p->id,
                        'locale'                => $key,
                        'key'                   => 'name'
                    ],
                    ['value'                 => $request->name[$index]]
                );
            }
            if ($request->description[$index] && $key != 'en') {
                $this->translation->updateOrInsert(
                    [
                        'translationable_type'  => 'App\Model\Product',
                        'translationable_id'    => $p->id,
                        'locale'                => $key,
                        'key'                   => 'description'
                    ],
                    ['value'                 => $request->description[$index]]
                );
            }
        }

        return response()->json([], 200);
    }

    public function warehouse_rate_insertupdate(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        Validator::make($request->all(), [
            'quantity' => 'required',
            'store_price' => 'required',
            'customer_price' => 'required',
            'unit_id' => 'required',
            'offer_price' => 'required',
        ]);
        $maxDiscount = NULL;
        $product_details = [];
        if ($request->quantity) {
            foreach ($request->quantity as $key => $qty) {
                if (isset($request->offer_price[$key])) {
                    $product_details[$key]['quantity']  = $qty;
                    $product_details[$key]['offer_price'] = $request->offer_price[$key];
                }
                if (isset($request->market_price[$key])) {
                    $product_details[$key]['market_price'] = $request->market_price[$key];
                }
                if (isset($request->margin[$key])) {
                    $product_details[$key]['margin'] = $request->margin[$key];
                }
                if (isset($request->approx_piece[$key])) {
                    $product_details[$key]['approx_piece'] = $request->approx_piece[$key];
                }
                if (isset($request->title[$key])) {
                    $product_details[$key]['title'] = $request->title[$key];
                }
                $discountPercentage = ($request->market_price[$key] - ($request->offer_price[$key] / $qty)) / $request->market_price[$key] * 100;
                $product_details[$key]['discount']  = number_format($discountPercentage, 2, '.', '');

                if (isset($request->per_unit_price[$key])) {
                    $product_details[$key]['per_unit_price'] = $request->per_unit_price[$key];
                }
                // if(isset($request->unit_id[$key])){
                //     $product_details[$key]['unit_id'] = $request->unit_id[$key];
                // }
            }

            $discounts = array_column($product_details, 'discount');
            if ($discounts) {
                $maxDiscount = max($discounts);
            }
            $productData = json_encode($product_details, true);
        }


        $authUser = auth('admin')->user();
        $row = $this->warehouse_products->where('product_id', $id)->where('warehouse_id', $authUser->warehouse_id)->first();
        // $row = $this->warehouse_products->find($id);
        if (!$row) {
            $row = $this->warehouse_products;
        }

        $row->default_unit = $request->default_unit;
        $row->warehouse_id = $authUser->warehouse_id;
        $row->product_id = $id;
        $row->avg_price = $request->avg_price;
        $row->customer_price = $request->customer_price;
        $row->store_price = $request->store_price;
        $row->product_details = @$productData ? @$productData : null;
        $row->discount_upto = @$maxDiscount;

        $row->save();


        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): \Illuminate\Http\RedirectResponse
    {
        $product = $this->product->find($request->id);
        foreach (json_decode($product['image'], true) as $img) {
            if (Storage::disk('public')->exists('product/' . $img)) {
                Storage::disk('public')->delete('product/' . $img);
            }
        }

        $flash_deal_products = FlashDealProduct::where('product_id', $product->id)->get();
        foreach ($flash_deal_products as $flash_deal_product) {
            $flash_deal_product->delete();
        }
        $product->delete();
        Toastr::success(translate('Product removed!'));
        return back();
    }

    /**
     * @param $id
     * @param $name
     * @return RedirectResponse
     */
    public function remove_image($id, $name): \Illuminate\Http\RedirectResponse
    {
        if (Storage::disk('public')->exists('product/' . $name)) {
            Storage::disk('public')->delete('product/' . $name);
        }


        $product = $this->product->find($id);
        $img_arr = [];
        // if (count(json_decode($product['images'])) < 2) {
        //     Toastr::warning('You cannot delete all images!');
        //     return back();
        // }

        foreach (json_decode($product['image'], true) as $img) {
            if (strcmp($img, $name) != 0) {
                $img_arr[] = $img;
            }
        }

        $this->product->where(['id' => $id])->update([
            'image' => json_encode($img_arr),
        ]);
        Toastr::success(translate('Image removed successfully!'));
        return back();
    }
    public function remove_single_image($id, $name): \Illuminate\Http\RedirectResponse
    {

        if (Storage::disk('public')->exists('product/single/' . $name)) {
            Storage::disk('public')->delete('product/single/' . $name);
        }


        $product = $this->product->find($id);
        $img_arr = [];
        // if (count(json_decode($product['images'])) < 2) {
        //     Toastr::warning('You cannot delete all images!');
        //     return back();
        // }

        foreach (json_decode($product['single_image'], true) as $img) {
            if (strcmp($img, $name) != 0) {
                $img_arr[] = $img;
            }
        }

        $this->product->where(['id' => $id])->update([
            'single_image' => json_encode($img_arr),
        ]);
        Toastr::success(translate('Image removed successfully!'));
        return back();
    }

    /**
     * @return Factory|View|Application
     */
    public function bulk_import_index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin-views.product.bulk-import');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function bulk_import_data(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $collections = (new FastExcel)->import($request->file('products_file'));
        } catch (\Exception $exception) {
            Toastr::error(translate('You have uploaded a wrong format file, please upload the right file.'));
            return back();
        }
        $col_key = ['name', 'description', 'price', 'tax', 'category_id', 'sub_category_id', 'discount', 'discount_type', 'tax_type', 'unit', 'total_stock', 'capacity', 'daily_needs'];
        foreach ($collections as $key => $collection) {

            foreach ($collection as $key => $value) {
                if ($key != "" && !in_array($key, $col_key)) {
                    Toastr::error('Please upload the correct format file.');
                    return back();
                }
            }
        }

        $data = [];
        foreach ($collections as $collection) {

            $data[] = [
                'name' => $collection['name'],
                'description' => $collection['description'],
                'image' => json_encode(['def.png']),
                'price' => $collection['price'],
                'variations' => json_encode([]),
                'tax' => $collection['tax'],
                'status' => 1,
                'attributes' => json_encode([]),
                'category_ids' => json_encode([['id' => (string)$collection['category_id'], 'position' => 0], ['id' => (string)$collection['sub_category_id'], 'position' => 1]]),
                'choice_options' => json_encode([]),
                'discount' => $collection['discount'],
                'discount_type' => $collection['discount_type'],
                'tax_type' => $collection['tax_type'],
                'unit' => $collection['unit'],
                'total_stock' => $collection['total_stock'],
                'capacity' => $collection['capacity'],
                'daily_needs' => $collection['daily_needs'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        DB::table('products')->insert($data);
        Toastr::success(count($data) . (translate(' - Products imported successfully!')));
        return back();
    }

    /**
     * @return Factory|View|Application
     */
    public function bulk_export_index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin-views.product.bulk-export-index');
    }

    /**
     * @param Request $request
     * @return StreamedResponse|string
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function bulk_export_data(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse|string
    {
        $start_date = $request->type == 'date_wise' ? $request['start_date'] : null;
        $end_date = $request->type == 'date_wise' ? $request['end_date'] : null;

        //dd($start_date, $end_date);

        $products = $this->product->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
            return $query->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date);
        })
            ->get();

        $storage = [];
        foreach ($products as $item) {
            $category_id = 0;
            $sub_category_id = 0;

            foreach (json_decode($item->category_ids, true) as $category) {
                if ($category['position'] == 1) {
                    $category_id = $category['id'];
                } else if ($category['position'] == 2) {
                    $sub_category_id = $category['id'];
                }
            }

            if (!isset($item['description'])) {
                $item['description'] = 'No description available';
            }

            if (!isset($item['capacity'])) {
                $item['capacity'] = 0;
            }

            $storage[] = [
                'name' => $item['name'],
                'description' => $item['description'],
                'price' => $item['price'],
                'tax' => $item['tax'],
                'category_id' => $category_id,
                'sub_category_id' => $sub_category_id,
                'discount' => $item['discount'],
                'discount_type' => $item['discount_type'],
                'tax_type' => $item['tax_type'],
                'unit' => $item['unit'],
                'total_stock' => $item['total_stock'],
                'capacity' => $item['capacity'],
                'daily_needs' => $item['daily_needs'],
            ];
        }
        return (new FastExcel($storage))->download('products.xlsx');
    }

    /**
     * @param Request $request
     * @return Factory|View|Application
     */
    public function limited_stock(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $stock_limit = $this->business_setting->where('key', 'minimum_stock_limit')->first()->value;
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->product->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%");
                }
            })->where('total_stock', '<', $stock_limit)->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->product->where('total_stock', '<', $stock_limit)->latest();
        }

        $products = $query->paginate(Helpers::getPagination())->appends($query_param);

        return view('admin-views.product.limited-stock', compact('products', 'search', 'stock_limit'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_variations(Request $request): \Illuminate\Http\JsonResponse
    {
        $product = $this->product->find($request['id']);
        return response()->json([
            'view' => view('admin-views.product.partials._update_stock', compact('product'))->render()
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_quantity(Request $request): \Illuminate\Http\RedirectResponse
    {
        $variations = [];
        $stock_count = $request['total_stock'];
        $product_price = $request['product_price'];
        if ($request->has('type')) {
            foreach ($request['type'] as $key => $str) {
                $item = [];
                $item['type'] = $str;
                $item['price'] = (abs($request['price_' . str_replace('.', '_', $str)]));
                $item['stock'] = abs($request['qty_' . str_replace('.', '_', $str)]);
                $variations[] = $item;
            }
        }

        $product = $this->product->find($request['product_id']);

        if ($stock_count >= 0) {
            $product->total_stock = $stock_count;
            $product->variations = json_encode($variations);
            $product->save();
            Toastr::success(translate('product_quantity_updated_successfully!'));
        } else {
            Toastr::warning(translate('product_quantity_can_not_be_less_than_0_!'));
        }
        return back();
    }
}
