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

class PurchaseStoreOrderController extends Controller
{

    protected $mTable;
    protected $view_folder;
    protected $pmodule;

    public function __construct()
    {
        $this->view_folder = 'admin-views.purchase-store-order';
        $this->mTable = '\App\Model\PurchaseStoreOrder';
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
        if ($user->admin_role_id == 6) {
            $rows->where('store_id', $user->store_id);
        } elseif ($user->admin_role_id == 3) {
            $rows->where('warehouse_id', $user->warehouse_id);
        }

        if ($request->has('search') && $search) {
            $key = explode(' ', $request['search']);
            $rows->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('invoice_number', 'like', "%{$value}%");
                    $q->orWhere('title', 'like', "%{$value}%");
                    $q->orWhere('gstin_number', 'like', "%{$value}%");
                    $q->orWhere('status', 'like', "%{$value}%");
                }
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

    public function destroy($id)
    {
        $this->mTable::find($id)->delete();
        Toastr::success(translate('order remved'));
        return back();
    }

    public function create(Request $request)
    {
        $user = auth('admin')->user();
        $wahrehouseId = $user->Store->warehouse_id;
        $categories = \App\Model\WarehouseCategory::where('warehouse_id', $wahrehouseId)->whereStatus('1')->get();

        return view($this->view_folder . '.create', compact('categories', 'wahrehouseId'));
    }

    public function store(Request $request)
    {
        try {
            //DB::beginTransaction();
            $request->validate([
                'title' => 'required',
                'product_id/*' => 'required',
                'rate/*' => 'required',
                'available_qty/*' => 'required'
            ]);

            $user = auth('admin')->user();
            $row = new $this->mTable;
            $row->warehouse_id = $user->Store->warehouse_id;
            $row->store_id = $user->Store->id;
            $row->title = $request->title;
            $row->invoice_number = rand(1000, 9999);
            $row->gstin_number = rand(1000, 9999);
            $row->purchase_date = date('Y-m-d H:i:s');
            $row->created_by_id = $user->id;
            $row->store_comments = $request->store_comments;
            if ($row->save()) {
                $finalPrice = 0;
                if ($request->product_id) {
                    foreach ($request->product_id as $key => $productId) {
                        if (isset($request->qty[$key])) {
                            $storeProduct = \App\Model\WarehouseProduct::find($productId);
                            $tableDetail = new \App\Model\PurchaseStoreOrderDetail();
                            $tableDetail->purchase_store_order_id = $row->id;
                            $tableDetail->product_id = $productId;
                            $tableDetail->qty =  $request->qty[$key];
                            $tableDetail->unit_name = $request->unit[$key];
                            $tableDetail->price_per_unit = $storeProduct->store_price;
                            $totalPrice = $request->qty[$key] * $storeProduct->store_price;
                            $tableDetail->total_price =  $totalPrice;
                            $tableDetail->save();
                            $finalPrice += $totalPrice;
                        }
                    }
                }
            }
            $row->total_purchase_amt = $finalPrice;
            $row->save();
            //DB::commit();
            Toastr::success(translate($request->title . ' Inserted Successfully!'));
            return redirect()->route('admin.store.purchase-store-orders.index');
        } catch (\Exception $e) {
            // DB::rollback();
            $msg = $e->getMessage();
            dd($msg);
            \Session::flash('warning', $msg);
            return redirect()->back()->withInput();
        }
    }
    public function show(Request $request, $id)
    {
        $user = auth('admin')->user();
        $row = $this->mTable::find($id);

        return view($this->view_folder . '.show', compact('row', 'user'));
    }



    public function updateStatus(Request $request, $id)
    {

        try {
            DB::beginTransaction();
            $role = auth('admin')->user()->admin_role_id;
            $row = $this->mTable::find($id);
            if ($request->status == 'Received' && $row->status != 'Delivered') {
                Toastr::error(translate('order not delivered till now!'));
                return redirect()->back()->withInput();
            }

            $row->status = $request->status;
            if ($role == 8) {
                $row->broker_comments = $request->broker_comments;
            } else {
                $row->warehouse_comments = $request->warehouse_comments;
            }

            $row->save();

            DB::commit();
            Toastr::success(translate('status updated Successfully!'));
            return redirect()->route('admin.store.purchase-store-orders.index');
        } catch (\Exception $e) {
            DB::rollback();
            $msg = $e->getMessage();
            \Session::flash('warning', $msg);
            return redirect()->back()->withInput();
        }
    }
}
