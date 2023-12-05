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
            $warehouses = $this->warehouse->where('deleted_at', null)->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                    $q->orWhere('code', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $warehouses = $this->warehouse->where('deleted_at', null);
        }
        $warehouses = $warehouses->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.warehouse.index', compact('warehouses', 'search'));
    }
    function create(Request $request): View|Factory|Application
    {
        $warehouses = $this->warehouse->orderBy('id','desc')->first();
        if(!empty($warehouses) && ($warehouses->id < 9)){
            $prevId = ($warehouses && $warehouses->id)?'0'.$warehouses->id+1:1;
        }else{
            $prevId = ($warehouses && $warehouses->id)?$warehouses->id+1:1;

        }
        
       return view('admin-views.warehouse.add',compact('prevId'));

    }

    public function get_code($city_code=null){
        $warehouse = Warehouse::query();
        $wh_city_id = $this->warehouse->where('city_id', $city_code)->count();

        if($wh_city_id < 9){
            $prevId = ($wh_city_id)? '0'.($wh_city_id+1):'01';
        }else{
            $prevId = ($wh_city_id)? $wh_city_id+1:'01';

        }
        return response()->json(['warehouse' => $warehouse, 'prevId' => $prevId]);
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
       
        $request->validate([
            'name'  => 'required|unique:warehouses',
            'code'  => 'required|unique:warehouses',
            'address'  => 'required',
            'brn_number'  => 'required',
            'msme_number'  => 'required',
        ]); 
             $revise = $deliver =  $order_cancel =  $pre_order = [];
        // echo '<pre>';
        if($request->delivery_open_time){
            foreach($request->delivery_open_time as $deliveryKey => $delivery_open){
                if(isset($request->delivery_close_time[$deliveryKey])){
                    $deliver[$deliveryKey]['open'] = $delivery_open;
                    $deliver[$deliveryKey]['close'] = $request->delivery_close_time[$deliveryKey];
                    $deliver[$deliveryKey]['hide_option_before'] = $request->hide_option_before[$deliveryKey];
                }
            }
            $delivery_time = json_encode($deliver,true);
        }
      
        if($request->pre_order_open_time){
         
            foreach($request->pre_order_open_time as $preOrderKey => $pre_order_opentime){
                if(isset($request->pre_order_close_time[$preOrderKey])){
                    $pre_order[$preOrderKey]['open'] = $pre_order_opentime;
                    $pre_order[$preOrderKey]['close'] = $request->pre_order_close_time[$preOrderKey];
                }
            }
            $pre_order_time = json_encode($pre_order,true);
        } 
        //into db
        $warehouse = $this->warehouse;

        $warehouse->name = $request->name == null ? null : $request->name;
        $warehouse->code =  $request->code == null ? null : $request->code;
        $warehouse->address =  $request->address == null ? null : $request->address;
        $warehouse->city_id =  $request->city_id == null ? null : $request->city_id;
        $warehouse->brn_number =  $request->brn_number == null ? null : $request->brn_number;
        $warehouse->msme_number =  $request->msme_number == null ? null : $request->msme_number;
        $warehouse->coverage =  $request->coverage == null ? null : $request->coverage;
        $warehouse->open_time =  $request->open_time == null ? null : $request->open_time;
        $warehouse->close_time =  $request->close_time == null ? null : $request->close_time;

        $warehouse->latitude = $request->latitude;
        $warehouse->longitude = $request->longitude;
        $warehouse->delivery_time =  isset($delivery_time) ? $delivery_time :null ;
        $warehouse->pre_order_time =  isset($pre_order_time) ?  $pre_order_time : null;
        $warehouse->save();
 
        Toastr::success(translate('Warehouse Added Successfully!') );
        return redirect()->route('admin.warehouse.list');
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

    public function subcategories($id): View|Factory|Application
    {
        $categories = $this->category->where('parent_id',$id)->get();
        return view('admin-views.warehouse.sub_category', compact('categories'));
    }
    public function get_subcategories($sub_cat_id)
    {
        $subcategories = $this->category->where('parent_id',$sub_cat_id)->get();
        return response()->json(['subcategories' => $subcategories]);
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
        $request->validate([
            'address'  => 'required',
            'brn_number'  => 'required',
            'msme_number'  => 'required',
            'open_time'	  => 'required',
            'close_time'  => 'required',	
        ]);
            // $check_unique =  $this->warehouse->find($id);
        $revise = [];
        $revise = $deliver =  $order_cancel =  $pre_order = [];
        // echo '<pre>';
        
        if($request->delivery_open_time){
            foreach($request->delivery_open_time as $deliveryKey => $delivery_open){
                if(isset($request->delivery_close_time[$deliveryKey])){
                    $deliver[$deliveryKey]['open'] = $delivery_open;
                    $deliver[$deliveryKey]['close'] = $request->delivery_close_time[$deliveryKey];
                    $deliver[$deliveryKey]['hide_option_before'] = $request->hide_option_before[$deliveryKey];
                }
            }
            $delivery_time = json_encode($deliver,true);
        }
       
        if($request->pre_order_open_time){
         
            foreach($request->pre_order_open_time as $preOrderKey => $pre_order_opentime){
                if(isset($request->pre_order_close_time[$preOrderKey])){
                    $pre_order[$preOrderKey]['open'] = $pre_order_opentime;
                    $pre_order[$preOrderKey]['close'] = $request->pre_order_close_time[$preOrderKey];
                }
            }
            $pre_order_time = json_encode($pre_order,true);
        } 
          
            $warehouse = $this->warehouse->find($id);
            $warehouse->name = $request->name == null ? null : $request->name;
            $warehouse->code =  $request->code == null ? null : $request->code;
            $warehouse->address =  $request->address == null ? null : $request->address;
            $warehouse->city_id =  $request->city_id == null ? null : $request->city_id;
            $warehouse->brn_number =  $request->brn_number == null ? null : $request->brn_number;
            $warehouse->msme_number =  $request->msme_number == null ? null : $request->msme_number;
            $warehouse->coverage =  $request->coverage == null ? null : $request->coverage;
            $warehouse->open_time =  $request->open_time == null ? null : $request->open_time;
            $warehouse->close_time =  $request->close_time == null ? null : $request->close_time;
    
            $warehouse->latitude = $request->latitude;
            $warehouse->longitude = $request->longitude;
            $warehouse->delivery_time =  isset($delivery_time) ? $delivery_time :null ;
            $warehouse->pre_order_time =  isset($pre_order_time) ?  $pre_order_time : null;
       
        $warehouse->save();
        Toastr::success( translate('Warehouse updated successfully!') );
        return redirect()->route('admin.warehouse.list');

        
    }


    public function wh_assign_category_page($id): View|Factory|Application
    {
        $categories = $this->category->where('deleted_at',null)->where('parent_id',0)->get();
        $wh_assign_categories = $this->warehouse_categories->where('warehouse_id',$id)->get();
        $wh_assign_id = $this->warehouse_categories->find($id);
        $warehouses = $this->warehouse->withoutGlobalScopes()->with('translations')->find($id);
        return view('admin-views.warehouse.assign_categories_list', compact('warehouses','categories','wh_assign_categories','wh_assign_id'));
    }
    
    public function wh_assign_category_page_create($id): View|Factory|Application
    {
        $categories = $this->category->where('deleted_at',null)->where('parent_id',0)->get();
        $wh_assign_categories = $this->warehouse_categories->where('warehouse_id',$id)->get();
        $wh_assign_id = $this->warehouse_categories->find($id);
        $warehouses = $this->warehouse->withoutGlobalScopes()->with('translations')->find($id);
        $categories = $this->category->get();
        $options = Helpers::getCategoryDropDown($categories);
        return view('admin-views.warehouse.assign_categories_create', compact('warehouses','categories','wh_assign_categories','wh_assign_id','options'));
    }

    function wh_assign_category_store(Request $request): RedirectResponse
    {
        $warehouse_id = $request->warehouse_id;
        $request->validate([
            // 'margin' => 'required',
            'category_id' => 'required',
            'category_order' => 'required',
        ]);
        $this->warehouse_categories->where('warehouse_id',$request->warehouse_id)->delete();
        if (  !empty($request->category_id) && !empty($request->category_order)) {
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
            }else{
        return back();

            }

        //     if (isset($data["margin"]) && is_array($data["margin"])) {
        //         $data["margin"] = array_values($data["margin"]);
        //     }else{
        // return back();

        //     }
            $data["status"] = $request->status;

            foreach ($data['category_id'] as $key => $cat) {
                $row = $this->warehouse_categories->firstOrNew(['warehouse_id' => $data['warehouse_id'], 'category_id' => $cat]);
                $row->warehouse_id =  isset($data["warehouse_id"]) ? $data['warehouse_id'] : '';
                $row->category_id = isset($cat) ? $cat : '';
                $row->category_order = $data["category_order"][$key] ?? '';
                // $row->margin = $data["margin"][$key] ?? '';
                
                $row->status = isset($data["status"][$key]) ? $data["status"][$key] : 1;

            $row->save();
            }
        }

        Toastr::success(translate('Warehouse Categories Added Successfully!'));
        return redirect()->route('admin.warehouse.wh-assign-category-page',[$warehouse_id]);
        
    }
  

    public function wh_assign_category_status(Request $request): RedirectResponse
    {
        $status = $this->warehouse_categories->where('id',$request->id)->first();
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
        $warehouse->update(['deleted_by' => auth('admin')->user()->id]);
       
            $warehouse->delete();
            Toastr::success( translate('warehouse removed!')  );
            return back();
    }
  
}  