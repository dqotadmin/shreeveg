<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\AdminRole;
use App\Model\Store;
use App\Model\City;
use App\Model\BankDetail;

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

class AdminUserController extends Controller
{
    public function __construct(
        private Admin $admin,
        private AdminRole $admin_role,
        private Store $store,
    ) {
    }

    /**
     * @return JsonResponse
     */
    public function restaurant_data(): \Illuminate\Http\JsonResponse
    {
        $new_order = DB::table('user_warehouse_orders')->where(['checked' => 0])->count();
        return response()->json([
            'success' => 1,
            'data' => ['new_order' => $new_order]
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function settings(): View|Factory|Application
    {
        return view('admin-views.settings');
    }
    function index(Request $request)
    {
       // dd(auth('admin')->user()->admin_role_id);
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $admins = $this->admin->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%");
                    $q->orWhere('phone', 'like', "%{$value}%");
                    $q->orWhere('email', 'like', "%{$value}%");
                    $q->orWhere('l_name', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        }else{
            $admins = $this->admin->orderBy('id', 'desc')->where('admin_role_id', $request->role_id);
        }
        if(auth('admin')->user()->admin_role_id == 3){
            if( $request->role_id == 8){
                $admins = $this->admin->orderBy('id', 'desc')->where('admin_role_id', $request->role_id)->paginate(Helpers::getPagination())->appends($query_param);
            }elseif( $request->role_id == 6){
                $admins = $this->admin->orderBy('id', 'desc')->where('admin_role_id', $request->role_id)->paginate(Helpers::getPagination())->appends($query_param);
                $warehouse_id = '';
                foreach($admins as $admin){
                    $warehouse_id =   $admin->Store->warehouse_id;
                }
                $stores = $this->store->orderBy('id', 'desc')->where('warehouse_id', $warehouse_id)->pluck('id');
                
                $admins = $this->admin->orderBy('id', 'desc')->whereIn('store_id', $stores)->where('admin_role_id', $request->role_id)->paginate(Helpers::getPagination())->appends($query_param);

            }else{
                $admins = $this->admin->orderBy('id', 'desc')->where('warehouse_id',auth('admin')->user()->warehouse_id)->where('admin_role_id', $request->role_id)->paginate(Helpers::getPagination())->appends($query_param);
            }
                $role = $this->admin_role->where('id', $request->role_id)->first();
        }else{
            $admins = $admins->paginate(Helpers::getPagination())->appends($query_param);
            $role = $this->admin_role->where('id', $request->role_id)->first();
        }
        
        return view('admin-views.warehouse-admin.index', compact('admins', 'role'));
    }

    public function user_management_create(Request $request): View|Factory|Application
    {

        $role = $this->admin_role->where('id', $request->role_id)->first();
        $user = auth('admin')->user();
        return view('admin-views.warehouse-admin.add', compact('role', 'user'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|max:255|unique:admins',
            'password' => 'required|same:confirm_password|min:8',
            'phone' => 'required',
        ], [
            'f_name.required' => 'First name is required!',
            'l_name.required' => 'Last name is required!',
            'email.required' => translate('Email is required!'),
            'email.unique' => translate('Email must be unique')
        ]);

        if (!empty($request->file('image'))) {
            $image_name = Helpers::upload('admin/warehouse/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }
        //into db
        $admin = $this->admin;
        $admin->f_name = $request->f_name;
        $admin->l_name = $request->l_name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->city_id = $request->city_id;
        $admin->state_id = $request->state_id;
        $admin->image = $image_name;
        if ($request->warehouse_id) {
            $admin->warehouse_id = $request->warehouse_id;
        }
        if ($request->store_id) {
            $admin->store_id = $request->store_id;
        }
        $admin->password = bcrypt($request['password']);

        $admin->admin_role_id = $request->admin_role_id;
        $admin->save();

        $adminId = $admin->id;
        $bankDetail = new BankDetail([
            'user_id' => $adminId,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'bank_name' => $request->bank_name,
            'ifsc_code' => $request->ifsc_code,
            'upi_id' => $request->upi_id,
            'upi_number' => $request->upi_number,
            // Add other fields as needed
        ]);
        $bankDetail->save();
        Toastr::success(translate($request->name . ' Inserted Successfully!'));
        return redirect()->route('admin.warehouse-admin', ['role_id' => $request->admin_role_id]);
    }

    public function edit(Request $request, $id)
    {
        $admins = $this->admin->with('bankDetail')->find($id);

        $role = $this->admin_role->where('id', $request->role_id)->first();
        $user = auth('admin')->user();
        return view('admin-views.warehouse-admin.edit', compact('admins', 'role','user'));
    }

    public function status(Request $request): RedirectResponse
    {

        $admin = $this->admin->find($request->id);
        $admin->status = $request->status;
        $admin->save();
        Toastr::success(translate('Admin status updated!'));
        return back();
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'phone' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->ignore($id),
            ]
        ], [
            'f_name.required' => 'First name is required!',
            'l_name.required' => 'Last name is required!',
        ]);
        if (!empty($request->file('image'))) {
            $image_name = Helpers::upload('admin/warehouse/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }
        $admin = $this->admin->find($id);
        $admin->f_name = $request->f_name;
        $admin->l_name = $request->l_name;
        $admin->phone = $request->phone;
        $admin->email = $request->email;
        $admin->image = $image_name;
        if ($request->warehouse_id) {
            $admin->warehouse_id = $request->warehouse_id;
        }
        if ($request->store_id) {
            $admin->store_id = $request->store_id;
        }
        $admin->save();
        $bankDetail = BankDetail::first();
        if ($id == $bankDetail->user_id) {
            $bankDetail->user_id = $id;
            $bankDetail->account_number = $request->account_number;
            $bankDetail->account_holder = $request->account_holder;
            $bankDetail->bank_name = $request->bank_name;
            $bankDetail->ifsc_code = $request->ifsc_code;
            $bankDetail->upi_id = $request->upi_id;
            $bankDetail->upi_number = $request->upi_number;
            $bankDetail->save();
        } else {
            // Handle the case where the BankDetail record with the given ID is not found
            // You might want to throw an exception or return an error response
        }
        Toastr::success(translate($request->name . ' updated successfully!'));
        return redirect()->route('admin.warehouse-admin', ['role_id' => $request->admin_role_id]);
    }

    function store_index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $admins = $this->admin->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%");
                    $q->orWhere('phone', 'like', "%{$value}%");
                    $q->orWhere('email', 'like', "%{$value}%");
                    $q->orWhere('l_name', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $admins = $this->admin->orderBy('id', 'desc')->where('admin_role_id', '6');
        }
        $admins = $admins->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.warehouse-admin.store_index', compact('admins'));
    }
    function wh_worker_index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $admins = $this->admin->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%");
                    $q->orWhere('phone', 'like', "%{$value}%");
                    $q->orWhere('email', 'like', "%{$value}%");
                    $q->orWhere('l_name', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $admins = $this->admin->orderBy('id', 'desc')->where('admin_role_id', '4');
        }
        $admins = $admins->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.warehouse-admin.customer_index', compact('admins'));
    }
    function store_sales_person_index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $admins = $this->admin->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%");
                    $q->orWhere('phone', 'like', "%{$value}%");
                    $q->orWhere('email', 'like', "%{$value}%");
                    $q->orWhere('l_name', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $admins = $this->admin->orderBy('id', 'desc')->where('admin_role_id', '7');
        }
        $admins = $admins->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.warehouse-admin.area_index', compact('admins'));
    }
    function wh_receiver_index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $admins = $this->admin->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%");
                    $q->orWhere('phone', 'like', "%{$value}%");
                    $q->orWhere('email', 'like', "%{$value}%");
                    $q->orWhere('l_name', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $admins = $this->admin->orderBy('id', 'desc')->where('admin_role_id', '5');
        }
        $admins = $admins->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.warehouse-admin.delivery_index', compact('admins'));
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settings_update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ], [
            'f_name.required' => 'First name is required!',
            'l_name.required' => 'Last name is required!',
        ]);

        $admin = $this->admin->find(auth('admin')->id());

        if ($request->has('image')) {
            $image_name = Helpers::update('admin/', $admin->image, 'png', $request->file('image'));
        } else {
            $image_name = $admin['image'];
        }

        $admin->f_name = $request->f_name;
        $admin->l_name = $request->l_name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->image = $image_name;
        $admin->save();
        Toastr::success(translate('Admin updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settings_password_update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'password' => 'required|same:confirm_password|min:8',
            'confirm_password' => 'required',
        ]);

        $admin = $this->admin->find(auth('admin')->id());
        $admin->password = bcrypt($request['password']);
        $admin->save();
        Toastr::success(translate('Admin password updated successfully!'));
        return back();
    }
    public function getCities($stateId)
    {
        $cities = City::where('state_id', $stateId)->get();
        return response()->json(['cities' => $cities]);
    }


    public function delete(Request $request): RedirectResponse
    {
        $admin = $this->admin->find($request->id);
        $admin->delete();
        Toastr::success(translate('Warehouse admin remved'));
        return back();
    }
}