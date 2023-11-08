<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Warehouse;
use App\Model\Category;
use App\Model\WarehouseCategory;
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
use DateTime;
class WarehouseController extends Controller
{
    public function __construct(
        private Warehouse $warehouse,
        private Category $category,
        private WarehouseCategory $warehouse_categories

    ){}

    /**
     * @param Request $request 
     * @return Application|Factory|View
     */
    function index(Request $request): View|Factory|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $warehouses = $this->warehouse->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                    $q->orWhere('code', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $warehouses = $this->warehouse;
        }
        $warehouses = $warehouses->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.warehouse.index', compact('warehouses', 'search'));
    }
    function create(Request $request): View|Factory|Application
    {
        $warehouses = $this->warehouse->orderBy('id','desc')->first();
        if($warehouses->id < 9){
            $prevId = ($warehouses && $warehouses->id)?'0'.$warehouses->id+1:1;
        }else{
            $prevId = ($warehouses && $warehouses->id)?$warehouses->id+1:1;

        }
        
       return view('admin-views.warehouse.add',compact('prevId'));

    }
  

    /**
     * @param Request $request
     * @return Application|Factory|View
     */


   
 
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $key = explode(' ', $request['search']);
        $warehouses = $this->warehouse->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('code', 'like', "%{$value}%");
            }
        })->get();
        return response()->json([
            'view' => view('admin-views.warehouse.index', compact('warehouses'))->render()
        ]);
    }

    /**
     * @return Factory|View|Application
     */
  
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    function store(Request $request): RedirectResponse
    {  
       
        $revise = [];
        // echo '<pre>';
        foreach($request->revise_time_open as $revicekey => $opentime){
            if(isset($request->revise_time_close[$revicekey])){
                $revise[$revicekey]['open']  = $opentime;
                $revise[$revicekey]['close'] = $request->revise_time_close[$revicekey];
            }
        }
        $revise_time = json_encode($revise,true); //[{"open":"13:00","close":"13:30"},{"open":"14:00","close":"14:30"},{"open":"15:00","close":"10:00"}]


        $deliver = [];
        foreach($request->delivery_open_time as $deliveryKey => $delivery_open){
            if(isset($request->delivery_close_time[$deliveryKey])){
                $deliver[$deliveryKey]['open'] = $delivery_open;
                $deliver[$deliveryKey]['close'] = $request->delivery_close_time[$deliveryKey];
            }
        }
        $delivery_time = json_encode($deliver,true);

        $order_cancel = [];
        foreach($request->order_cancel_open_time as $orderCancelKey => $order_cancel_opentime){
            if(isset($request->order_cancel_close_time[$orderCancelKey])){
                $order_cancel[$orderCancelKey]['open'] = $order_cancel_opentime;
                $order_cancel[$orderCancelKey]['close'] = $request->order_cancel_close_time[$orderCancelKey];
            }
        }
        $order_cancel_time = json_encode($order_cancel,true);

        $pre_order = [];
        foreach($request->pre_order_close_time as $preOrderKey => $pre_order_opentime){
            if(isset($request->pre_order_close_time[$preOrderKey])){
                $pre_order[$preOrderKey]['open'] = $pre_order_opentime;
                $pre_order[$preOrderKey]['close'] = $request->pre_order_close_time[$preOrderKey];
            }
        }
        $pre_order_time = json_encode($pre_order,true);
     
        $request->validate([
        // 'name'  => 'required|unique:warehouses',
        // 'code'  => 'required|unique:warehouses',
        // 'address'  => 'required',
        // 'owner_name'  => 'required',
        // 'owner_number'  => 'required',
        // 'owner_second_number'  => 'required',
        // 'pin_code'  => 'required',
        // 'brn_number'  => 'required',
        // 'msme_number'  => 'required',
        // 'email'  => 'required|unique:warehouses,email',
        // 'title' => 'required',
        // 'open_time'	  => 'required',
        // 'close_time'  => 'required',	
        // 'user_id'	  => 'required',
        // 'password'  => 'required',
        // 'map_location'  => 'required',
        // 'latitude'	  => 'required',
        // 'longitude'  => 'required',
 
     
        ]);
 
        //into db
        $warehouse = $this->warehouse;

        $warehouse->name = $request->name == null ? null : $request->name[array_search('en', $request->lang)];
        $warehouse->code =  $request->code == null ? null : $request->code[array_search('en', $request->lang)];
        $warehouse->address =  $request->address == null ? null : $request->address[array_search('en', $request->lang)];
        $warehouse->owner_name =  $request->owner_name == null ? null : $request->owner_name[array_search('en', $request->lang)];
        $warehouse->owner_number =  $request->owner_number == null ? null : $request->owner_number[array_search('en', $request->lang)];
        $warehouse->owner_second_number =  $request->owner_second_number == null ? null : $request->owner_second_number[array_search('en', $request->lang)];
        $warehouse->city_id =  $request->city_id == null ? null : $request->city_id[array_search('en', $request->lang)];
        $warehouse->pin_code =  $request->pin_code == null ? null : $request->pin_code[array_search('en', $request->lang)];
        $warehouse->brn_number =  $request->brn_number == null ? null : $request->brn_number[array_search('en', $request->lang)];
        $warehouse->msme_number =  $request->msme_number == null ? null : $request->msme_number[array_search('en', $request->lang)];
        $warehouse->coverage =  $request->coverage == null ? null : $request->coverage[array_search('en', $request->lang)];
        $warehouse->title =  $request->title == null ? null : $request->title[array_search('en', $request->lang)];
        $warehouse->open_time =  $request->open_time == null ? null : $request->open_time[array_search('en', $request->lang)];
        $warehouse->close_time =  $request->close_time == null ? null : $request->close_time[array_search('en', $request->lang)];

        $warehouse->latitude = $request->latitude[array_search('en', $request->lang)];
        $warehouse->longitude = $request->longitude[array_search('en', $request->lang)];
  
        $warehouse->revise_time =  $revise_time == null ? null :   $revise_time;
        $warehouse->delivery_time =  $delivery_time == null ? null :   $delivery_time;
        $warehouse->order_cancel_time =  $order_cancel_time == null ? null :  $order_cancel_time;
        $warehouse->pre_order_time =  $pre_order_time == null ? null :  $pre_order_time;
        $warehouse->save();
 
        //translation
        $data = [];
    
        if (count($data)) {
            Translation::insert($data);
        }

        Toastr::success(translate('Warehouse Added Successfully!') );
        return back();
    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function edit($id): View|Factory|Application
    {
        $warehouses = $this->warehouse->withoutGlobalScopes()->with('translations')->find($id);
        return view('admin-views.warehouse.edit', compact('warehouses'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): RedirectResponse
    {
        $city = $this->warehouse->find($request->id);
        $city->status = $request->status;
        $city->save();
        Toastr::success(translate('Warehouse status updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // $check_unique =  $this->warehouse->find($id);
        $revise = [];
        foreach($request->revise_time_open as $revicekey => $opentime){
            if(isset($request->revise_time_close[$revicekey])){
                $revise[$revicekey]['open']  = $opentime;
                $revise[$revicekey]['close'] = $request->revise_time_close[$revicekey];
            }
        }
        $revise_time = json_encode($revise,true); //[{"open":"13:00","close":"13:30"},{"open":"14:00","close":"14:30"},{"open":"15:00","close":"10:00"}]


        $deliver = [];
        foreach($request->delivery_open_time as $deliveryKey => $delivery_open){
            if(isset($request->delivery_close_time[$deliveryKey])){
                $deliver[$deliveryKey]['open'] = $delivery_open;
                $deliver[$deliveryKey]['close'] = $request->delivery_close_time[$deliveryKey];
            }
        }
        $delivery_time = json_encode($deliver,true);

        $order_cancel = [];
        foreach($request->order_cancel_open_time as $orderCancelKey => $order_cancel_opentime){
            if(isset($request->order_cancel_close_time[$orderCancelKey])){
                $order_cancel[$orderCancelKey]['open'] = $order_cancel_opentime;
                $order_cancel[$orderCancelKey]['close'] = $request->order_cancel_close_time[$orderCancelKey];
            }
        }
        $order_cancel_time = json_encode($order_cancel,true);

        $pre_order = [];
        foreach($request->pre_order_close_time as $preOrderKey => $pre_order_opentime){
            if(isset($request->pre_order_close_time[$preOrderKey])){
                $pre_order[$preOrderKey]['open'] = $pre_order_opentime;
                $pre_order[$preOrderKey]['close'] = $request->pre_order_close_time[$preOrderKey];
            }
        }
        $pre_order_time = json_encode($pre_order,true);
        // print_r($revise_time); echo'<br>';echo'<br>';
        // print_r($delivery_time);echo'<br>';echo'<br>';
        // print_r($order_cancel_time);echo'<br>';echo'<br>';
        // print_r($pre_order_time);echo'<br>';echo'<br>';
            $request->validate([
                'address'  => 'required',
                'owner_name'  => 'required',
                'brn_number'  => 'required',
                'msme_number'  => 'required',
                'open_time'	  => 'required',
                'close_time'  => 'required',	
            ]);
            $warehouse = $this->warehouse->find($id);
    
 
        $warehouse->name = $request->name == null ? null : $request->name[array_search('en', $request->lang)];
        $warehouse->code =  $request->code == null ? null : $request->code[array_search('en', $request->lang)];
        $warehouse->address =  $request->address == null ? null : $request->address[array_search('en', $request->lang)];
        $warehouse->owner_name =  $request->owner_name == null ? null : $request->owner_name[array_search('en', $request->lang)];
        $warehouse->city_id =  $request->city_id == null ? null : $request->city_id[array_search('en', $request->lang)];
        $warehouse->pin_code =  $request->pin_code == null ? null : $request->pin_code[array_search('en', $request->lang)];
        $warehouse->brn_number =  $request->brn_number == null ? null : $request->brn_number[array_search('en', $request->lang)];
        $warehouse->msme_number =  $request->msme_number == null ? null : $request->msme_number[array_search('en', $request->lang)];
        $warehouse->coverage =  $request->coverage == null ? null : $request->coverage[array_search('en', $request->lang)];
        $warehouse->title =  $request->title == null ? null : $request->title[array_search('en', $request->lang)];
        $warehouse->open_time =  $request->open_time == null ? null : $request->open_time[array_search('en', $request->lang)];
        $warehouse->close_time =  $request->close_time == null ? null : $request->close_time[array_search('en', $request->lang)];
   
        $warehouse->latitude =  $request->latitude == null ? null : $request->latitude[array_search('en', $request->lang)];
        $warehouse->longitude =  $request->longitude == null ? null : $request->longitude[array_search('en', $request->lang)];
  
        $warehouse->revise_time =  $revise_time == null ? null :   $revise_time;
        $warehouse->delivery_time =  $delivery_time == null ? null :   $delivery_time;
        $warehouse->order_cancel_time =  $order_cancel_time == null ? null :  $order_cancel_time;
        $warehouse->pre_order_time =  $pre_order_time == null ? null :  $pre_order_time;
       
        $warehouse->save();
        Toastr::success( translate('Warehouse updated successfully!') );
        return redirect()->route('admin.warehouse.add');

        
    }


    public function wh_assign_category_page($id): View|Factory|Application
    {
        $categories = $this->category->where('parent_id',0)->get();
        $wh_assign_categories = $this->warehouse_categories->where('warehouse_id',$id)->get();
        $wh_assign_id = $this->warehouse_categories->find($id);
        $warehouses = $this->warehouse->withoutGlobalScopes()->with('translations')->find($id);
        return view('admin-views.warehouse.assign_categories', compact('warehouses','categories','wh_assign_categories','wh_assign_id'));
    }

    function wh_assign_category_store(Request $request): RedirectResponse
{
    $request->validate([
        'margin' => 'required',
        'category_id' => 'required',
        'category_order' => 'required',
    ]);

    if (!empty($request->margin) && !empty($request->category_id) && !empty($request->category_order)) {
        $data = $request->all();

        foreach ($data as &$array) {
            if (is_array($array)) {
                $array = array_filter($array, function ($value) {
                    return $value !== null;
                });
            }
        }

        $data = array_filter($data, function ($value) {
            return !empty($value);
        });

        if (isset($data["category_order"]) && is_array($data["category_order"])) {
            $data["category_order"] = array_values($data["category_order"]);
        }

        if (isset($data["margin"]) && is_array($data["margin"])) {
            $data["margin"] = array_values($data["margin"]);
        }
        $data["status"] = $request->status;

        foreach ($data['category_id'] as $key => $cat) {
            $row = $this->warehouse_categories->firstOrNew(['warehouse_id' => $data['warehouse_id'], 'category_id' => $cat]);
            $row->warehouse_id =  isset($data["warehouse_id"]) ? $data['warehouse_id'] : '';
            $row->category_id = isset($cat) ? $cat : '';
            $row->category_order = $data["category_order"][$key] ?? 0;
            $row->margin = $data["margin"][$key];
            
            $row->status = isset($data["status"][$key]) ? $data["status"][$key] : 1;

        $row->save();
        }
    }

    Toastr::success(translate('Warehouse Categories Added Successfully!'));
    return back();
}
  

    public function wh_assign_category_status(Request $request): RedirectResponse
    {
        $status = $this->warehouse_categories->where('warehouse_id',$request->id)->where('category_id',$request->catid)->first();
        if($status){
            $status->status = $request->status;
            $status->save();
            Toastr::success(translate('Warehouse category status updated!'));
            return back();
        }else{
            Toastr::error(translate('Warehouse category status not updated!'));
            return back();
        }
   
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $warehouse = $this->warehouse->find($request->id);
       
            $warehouse->delete();
            Toastr::success( translate('warehouse removed!')  );
            return back();
    }
}  