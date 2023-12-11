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
use PHPUnit\TextUI\Help;
use Symfony\Component\Console\Helper\Helper;

class PurchaseWarehouseOrderController extends Controller
{

    protected $mTable;
    protected $view_folder;
    protected $pmodule;

    public function __construct()
    {
        $this->view_folder = 'admin-views.purchase-warehouse-order';
        $this->mTable = '\App\Model\PurchaseWarehouseOrder';
    }

    /**
     * @return JsonResponse
     */

    function index(Request $request)
    {

        $query_param = [];
        $search = $request['search'];
        $role = auth('admin')->user()->admin_role_id;
        $rows = $this->mTable::query();
        if ($role == 8) {
            $rows->where('broker_id', auth('admin')->user()->id);
        } elseif($role == 1) {
            $rows->get();
        }else
        {
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
        $rows = $rows->orderBy('id', 'desc')->paginate(Helpers::getPagination())->appends($query_param);

        return view($this->view_folder . '.index', compact('rows', 'search', 'role'));
    }

    public function destroy($id)
    {
        $this->mTable::find($id)->delete();
        Toastr::success(translate('order remved'));
        return back();
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
            foreach($wh_orders as $wh_order){
                $product_id = $wh_order->product_id;
                $qty = $wh_order->qty;
                $warehouseProduct = Helpers::warehouseProductData($product_id);
                
                if ($warehouseProduct && $warehouseProduct->total_stock > 0) {
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
}
