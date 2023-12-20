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

class BrokerRateListController extends Controller
{
    // public function __construct(
    //     private Category $category,
    //     private AdminRole $admin_role,
    // ) {
    // }

    protected $mTable;
    protected $view_folder;
    protected $pmodule;

    public function __construct()
    {
        $this->view_folder = 'admin-views.broker';
        $this->mTable = '\App\Model\BrokerRateList';
    }

    /**
     * @return JsonResponse
     */

    function index(Request $request)
    {

        $query_param = [];
        $search = $request['search'];
        $user_id = auth('admin')->user()->id;

        $rows = $this->mTable::where('admin_id', $user_id);
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $rows->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }
        $rows = $rows->orderBy('id', 'desc')->paginate(Helpers::getPagination())->appends($query_param);

        return view($this->view_folder . '.index', compact('rows', 'search'));
    }

    public function destroy($id)
    {
        $this->mTable::find($id)->delete();
        Toastr::success(translate('rate list remved'));
        return back();
    }


    public function create(Request $request)
    {
        $categories = Category::whereHas('products')->whereStatus(1)->orderBy('name', 'asc')->where('deleted_at',null)->get();
        // $categories = Category::whereStatus(1)->orderBy('name', 'asc')->get();
        return view($this->view_folder . '.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'title' => 'required',
                'product_id/*' => 'required',
                'rate/*' => 'required',
                'available_qty/*' => 'required'
            ]);

            //into db
            $row = new $this->mTable;
            $row->admin_id = auth('admin')->user()->id;
            $row->title = $request->title;
            $row->date_time = date('Y-m-d H:i:s');

            if ($row->save()) {
                if ($request->rate) {
                    foreach ($request->rate as $key => $rate) {
                        if (isset($request->available_qty[$key])) {
                            $detailRow = new \App\Model\BrokerRateListDetail();
                            $detailRow['broker_rate_list_id'] = $row->id;;
                            $detailRow['product_id'] = $request->product_id[$key];
                            $detailRow['rate'] = $rate;
                            $detailRow['available_qty'] = $request->available_qty[$key];
                            $detailRow['unit'] = $request->unit[$key];
                            $detailRow->save();
                        }
                    }
                }
            }
            DB::commit();
            Toastr::success(translate($request->title . ' Inserted Successfully!'));
            return redirect()->route('admin.broker-rate-list.index');
        } catch (\Exception $e) {
            DB::rollback();
            $msg = $e->getMessage();
            \Session::flash('warning', $msg);
            return redirect()->back()->withInput();
        }
    }

    public function show(Request $request, $id)
    {
        $row = $this->mTable::find($id);
        return view($this->view_folder . '.show', compact('row'));
    }

    public function wh_receiver_rate_list(Request $request)
    {

        $query_param = [];
        $search = $request['search'];
        $user_id = auth('admin')->user()->id;
      
        $rows_old = $this->mTable::query()->get();
        $latestIds = $this->mTable:: query()->selectRaw('MAX(id) as id ,admin_id')->groupBy('admin_id')->pluck('id')->toArray();
       
        $rows = $this->mTable::whereIn('id',$latestIds)->get();
        // dd($rows_old, $rows);
        //$rows = $rows->orderBy('id', 'desc')->get();
       
        return view($this->view_folder . '.rate_list', compact('rows'));
    }

    public function wh_receiver_post_order(Request $request)
    {
        //dd($request->all());
        try {
            DB::beginTransaction();
            $user = auth('admin')->user();
            $broker_rate_list = $this->mTable::find($request->broker_rate_list_id);
            $table = new \App\Model\PurchaseWarehouseOrder();
            $table->warehouse_id = $user->warehouse_id;
            $table->created_by_id = $user->id;
            $table->broker_id = $broker_rate_list->admin_id;
            $table->invoice_number = rand(1000, 9999);
            $table->gstin_number = rand(1000, 9999);
            $table->purchase_date = date('Y-m-d');
            $table->save();

            $finalPrice = 0;
            if ($request->products) {
                foreach ($request->products as $key => $product) {
                    if (isset($request->order_qty[$key])) {
                        $brokerProduct = \App\Model\BrokerRateListDetail::where('broker_rate_list_id', $broker_rate_list->id)->where('product_id', $product)->first();
                        $tableDetail = new \App\Model\PurchaseWarehouseOrderDetail();
                        $tableDetail->purchase_warehouse_order_id = $table->id;
                        $tableDetail->product_id = $product;
                        $tableDetail->qty =  $request->order_qty[$key];
                        $tableDetail->unit_name = $brokerProduct->unit;
                        $tableDetail->price_per_unit = $brokerProduct->rate;
                        $totalPrice = $request->order_qty[$key] * $brokerProduct->rate;
                        $tableDetail->total_price =  $totalPrice;
                        $tableDetail->save();
                        $finalPrice += $totalPrice;
                    }
                }
            }
            $table->total_purchase_amt = $finalPrice;
            $table->save();
            DB::commit();
            Toastr::success(translate(' Order placed Successfully!'));
            return redirect()->route('admin.purchase-warehouse-order.index');
        } catch (\Exception $e) {
            DB::rollback();
            $msg = $e->getMessage();
            \Session::flash('warning', $msg);
            return redirect()->back()->withInput();
        }
    }
}
