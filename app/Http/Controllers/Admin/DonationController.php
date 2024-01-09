<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\AdminRole;
use App\Model\City;
use App\Model\BankDetail;
use App\Model\Category;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class DonationController extends Controller
{

    protected $mTable;
    protected $view_folder;
    protected $pmodule;
    protected $user;
    protected  $store_product;
    protected  $warehouse_product;

    public function __construct()
    {
        $this->view_folder = 'admin-views.donations';
        $this->mTable = '\App\Model\Donation';
        $this->store_product = '\App\Model\StoreProduct';
        $this->warehouse_product = '\App\Model\WarehouseProduct';
        $this->user = auth('admin')->user();
    }

    /**
     * @return JsonResponse
     */

    function index(Request $request)
    {

        $query_param = [];
        $search = $request['search'];
        $user = auth('admin')->user();

        $rows = $this->mTable::query();

        if (($user->store_id) > 0) {
            $wahrehouseId = $user->Store->warehouse_id;
            $store_id = $user->store_id;
            $rows->where('store_id', $user->store_id);
        } else {
            $wahrehouseId = $user->warehouse_id;
            $rows->where('warehouse_id', $user->warehouse_id);
        }



        // if ($user->admin_role_id == 6) {
        //     $rows->where('store_id', $user->store_id);
        // } elseif ($user->admin_role_id == 3) {
        //     $rows->where('warehouse_id', $user->warehouse_id);
        // }

        if ($request->has('search') && $search) {
            $key = explode(' ', $request['search']);
            $rows->whereHas('productDetail', function ($q1) use ($search) {
                $q1->where('name', 'like', "%{$search}%")
                    ->orWhere('product_code', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            })->orWhereHas('warehouseDetail', function ($q1) use ($search) {
                $q1->where('name', 'like', "%{$search}%");
            })->orWhereHas('storeDetail', function ($q1) use ($search) {
                $q1->where('name', 'like', "%{$search}%");
            });
            $query_param = ['search' => $request['search']];
        }

        $rows = $rows->orderBy('id', 'desc')->paginate(Helpers::getPagination())->appends($query_param);
        return view($this->view_folder . '.index', compact('rows', 'search', 'user'));
    }

    public function create(Request $request)
    {
        $store_id = '';
        $user = auth('admin')->user();
        if (($user->store_id) > 0) {
            $wahrehouseId = $user->Store->warehouse_id;
            $store_id = $user->store_id;
        } else {
            $wahrehouseId = $user->warehouse_id;
        }
        //dd($wahrehouseId, $store_id, $user->id);
        $categories = \App\Model\WarehouseCategory::where('warehouse_id', $wahrehouseId)->whereStatus('1')->get();
        return view($this->view_folder . '.create', compact('categories', 'wahrehouseId', 'store_id', 'user'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        try {
            DB::beginTransaction();
            $request->validate([
                'product_id/*' => 'required',
                'qty/*' => 'required'
            ]);

            $user = auth('admin')->user();

            if ($request->product_id) {
                foreach ($request->product_id as $key => $productId) {
                    if (isset($request->qty[$key])) {
                        // dump($productId, $request->warehouse_id);
                        $row = new $this->mTable;

                        $row->product_id = $productId;

                        $row->quantity =  $request->qty[$key];
                        $row->warehouse_id = $request->warehouse_id;
                        if ($request->store_id) {
                            $row->store_id = @$request->store_id;
                            \App\Model\StoreProduct::where('store_id', $request->store_id)->where('product_id', $productId)->decrement('total_stock', $request->qty[$key]);
                        } else {
                            \App\Model\WarehouseProduct::where('warehouse_id', $request->warehouse_id)->where('product_id', $productId)->decrement('total_stock', $request->qty[$key]);
                        }

                        $row->creator_id = $user->id;
                        $row->save();
                        //dd($row);
                    }
                }
            }


            DB::commit();
            Toastr::success(translate('data Inserted Successfully!'));
            return redirect()->route('admin.store.donations.index');
        } catch (\Exception $e) {
            DB::rollback();
            $msg = $e->getMessage();
            //dd($msg);
            \Session::flash('warning', $msg);
            return redirect()->back()->withInput();
        }
    }
}
