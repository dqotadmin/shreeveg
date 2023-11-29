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
        $categories = Category::whereHas('products')->whereStatus(1)->orderBy('name', 'asc')->get();
        // $categories = Category::whereStatus(1)->orderBy('name', 'asc')->get();
        return view($this->view_folder . '.create', compact('categories'));
    }

    public function store(Request $request)
    {
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

        Toastr::success(translate($request->name . ' Inserted Successfully!'));
        return redirect()->route('admin.broker-rate-list.index');
    }

    public function show(Request $request, $id)
    {
        $row = $this->mTable::find($id);
        return view($this->view_folder . '.show', compact('row'));
    }

    function wh_receiver_rate_list(Request $request)
    {

        $query_param = [];
        $search = $request['search'];
        $user_id = auth('admin')->user()->id;

        $rows = $this->mTable::query()->get();

        //$rows = $rows->orderBy('id', 'desc')->get();

        return view($this->view_folder . '.rate_list', compact('rows'));
    }
}
