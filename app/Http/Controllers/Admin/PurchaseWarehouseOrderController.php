<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\AdminRole;
use App\Model\City;
use App\Model\BankDetail;
use App\Model\Category;
use App\Model\WarehouseProduct;

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
use PHPUnit\TextUI\Help;
use Symfony\Component\Console\Helper\Helper;

class PurchaseWarehouseOrderController extends Controller
{

    protected $mTable;
    protected $view_folder;
    protected $pmodule;
    protected $warehouse_products;

    public function __construct()
    {
        $this->view_folder = 'admin-views.purchase-warehouse-order';
        $this->mTable = '\App\Model\PurchaseWarehouseOrder';
        $this->warehouse_products = '\App\Model\WarehouseProduct';
    }


    function index(Request $request)
    {
        $date = today();
        $query_param = [];
        $search = $request['search'];
        $role = auth('admin')->user()->admin_role_id;
        $rows = $this->mTable::query();
        if ($role == 8) {
            $rows->where('broker_id', auth('admin')->user()->id);
        } elseif ($role == 1) {
            $rows->get();
        } else {
            $rows->where('warehouse_id', auth('admin')->user()->warehouse_id);
        }
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $rows->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('invoice_number', 'like', "%{$value}%");
                    $q->orWhere('gstin_number', 'like', "%{$value}%");
                    $q->orWhere('status', 'like', "%{$value}%");
                }
            })->orWhereHas('warehouseDetail', function ($q1) use ($search) {
                $q1->where('name', 'like', "%{$search}%");
            });
            $query_param = ['search' => $request['search']];
        }

        $today = Carbon::today()->toDateString();

        $rows = $rows->whereDate('created_at', $today)
            ->orderBy('id', 'desc')
            ->paginate(Helpers::getPagination())
            ->appends($query_param);
        return view($this->view_folder . '.index', compact('rows', 'search', 'role'));
    }

    public function destroy($id)
    {
        $status =  $this->mTable::find($id);
        if ($status == 'Pending') {
            $this->mTable::find($id)->delete();
            Toastr::success(translate('order remved'));
            return back();
        } else {
            Toastr::error(translate('order not remved'));
            return back();
        }
    }


    public function show(Request $request, $id)
    {
        $role = auth('admin')->user()->admin_role_id;
        $row = $this->mTable::find($id);
        return view($this->view_folder . '.show', compact('row', 'role'));
    }



    public function wh_receiver_update_status(Request $request, $id)
    {
        try {


            DB::beginTransaction();
            $role = auth('admin')->user()->admin_role_id;
            $row = $this->mTable::find($id);
            $row->status = $request->status;
            if ($role == 8) {
                $row->broker_comments = $request->broker_comments;
            } else {
                $row->warehouse_comments = $request->warehouse_comments;
            }


            $row->save();
            if ($role == 5) {
                $wh_orders = $row->purchaseWarehouseOrderDetail;
                // dd($wh_orders);
                foreach ($wh_orders as $wh_order) {
                    $product_id = $wh_order->product_id;
                    $qty = $wh_order->qty;
                    $warehouseProduct = Helpers::warehouseProductData($product_id);

                    if ($warehouseProduct && $warehouseProduct->total_stock >= 0) {
                        $warehouseProduct->increment('total_stock', $qty);
                    }
                }
            }
            DB::commit();
            Toastr::success(translate('status updated Successfully!'));
            return redirect()->route('admin.purchase-warehouse-order.index');
        } catch (\Exception $e) {
            DB::rollback();
            $msg = $e->getMessage();
            dd($msg);
            \Session::flash('warning', $msg);
            return redirect()->back()->withInput();
        }
    }

    public function getPriceUpdate(Request $request)
    {
        if ($request->type == 'store_price') {
            return view($this->view_folder . '.store_price_update');
        }
        return view($this->view_folder . '.avg_price_update');
    }

    // public function stock_update(Request $request)
    // {
    //     if ($request->type == 'store_price') {
    //         return view($this->view_folder . '.store_price_update');
    //     }
    //     return view($this->view_folder . '.avg_price_update');
    // }

    public function postPriceUpdate(Request $request)
    {
        //dd($request->all());
        $authUser = auth('admin')->user();
        foreach ($request->product_id as $key => $product_id) {
            $existRow = WarehouseProduct::where('product_id', $product_id)->where('warehouse_id', $authUser->warehouse_id)->first();

            if (!$existRow) {
                $existRow = new WarehouseProduct;
                $existRow->product_id = $product_id;
                $existRow->warehouse_id = $authUser->warehouse_id;
                $existRow->save();
            }

            if (isset($request->product_id[$key])) {
                $existRow['product_id']  = $product_id;
            }

            if (isset($request->margin[$key]) && !empty($request->margin[$key])) {

                if ($request->type == 'store_price') {
                    if (isset($request->store_price[$key])) {

                        $existRow['store_price'] = $request->store_price[$key];
                    }
                    $existRow['store_price_updated_date'] = date('Y-m-d H:i:s');
                } else {

                    if (isset($request->avg_price[$key])) {
                        $existRow['avg_price'] = $request->avg_price[$key];
                    }
                    $existRow['avg_price_updated_date'] = date('Y-m-d H:i:s');
                }
            }

            $existRow->save();
        }
        return redirect()->back();
    }
    public function product_price_updateOld(Request $request)
    {

        $authUser = auth('admin')->user();
        foreach ($request->product_id as $key => $product_id) {
            $existRow = WarehouseProduct::where('product_id', $product_id)->where('warehouse_id', $authUser->warehouse_id)->first();
            // dump($existRow->productDetail->name);


            if (!$existRow) {
                $existRow = new WarehouseProduct;
                $existRow->product_id = $product_id;
                $existRow->warehouse_id = $authUser->warehouse_id;
                $existRow->save();
            }

            if (isset($request->product_id[$key])) {
                $existRow['product_id']  = $product_id;
            }
            if (isset($request->customer_price[$key])) {
                $existRow['customer_price'] = $request->customer_price[$key];
            }
            if (isset($request->store_price[$key])) {
                $existRow['store_price'] = $request->store_price[$key];
            }
            if (isset($request->avg_price[$key])) {
                $existRow['avg_price'] = $request->avg_price[$key];
            }
            if ($existRow->product_details) {

                $product_details = json_decode($existRow->product_details, true);
                // dd($product_details);
                if (count($product_details) > 0) {
                    foreach ($product_details as $skey => $product_detail) {
                        // dd($product_detail);
                        $qty = $product_detail['quantity'];
                        $discount = $product_detail['discount'];
                        $newArr[$skey]['quantity'] =  $qty;
                        $newArr[$skey]['discount'] = $discount;
                        $newArr[$skey]['approx_piece'] = $product_detail['approx_piece'];
                        $newArr[$skey]['title'] = $product_detail['title'];
                        $newArr[$skey]['offer_price'] =  $qty * ($request->customer_price[$key] - ($request->customer_price[$key] * $discount / 100));
                        $newArr[$skey]['market_price'] = $qty * $request->customer_price[$key];
                    }


                    $existRow['product_details'] = json_encode($newArr, true);
                }
            }
            $existRow->save();
            //  dd('final',$existRow);   

        }
        return redirect()->back();
    }
}
