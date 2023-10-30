<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Warehouse;
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
        private Warehouse $warehouse
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
                    $q->orWhere('warehouse_name', 'like', "%{$value}%");
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
                $q->orWhere('warehouse_name', 'like', "%{$value}%");
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
        // "revise_open_time"
        // "revise_close_time" 
        $data = $request->revise_time;
// $reviseTimes = $data['revise_time'];
        
        foreach ($data['revise_open_time'] as $key => $openTime) {
            $closeTime = $data['revise_close_time'][$key];
            
            echo "Open Time: " . $openTime . " - Close Time: " . $closeTime . "<br>";
            $start_time = new DateTime($openTime);
            $duration = $start_time->diff(new DateTime($closeTime));
            if ($start_time < $closeTime) {
            echo  $duration->h.' hours '. $duration->i. ' minutes'. "<br>";
               
            }else{
                echo '-'.$duration->h.' hours '.  "<br>";

            }
        }

    
dd($data);
        
        $request->validate([
        // 'warehouse_name'  => 'required|unique:warehouses',
        // 'warehouse_code'  => 'required|unique:warehouses',
        // 'warehouse_address'  => 'required',
        // 'owner_name'  => 'required',
        // 'owner_number'  => 'required',
        // 'owner_second_number'  => 'required',
        // 'pin_code'  => 'required',
        // 'brn_number'  => 'required',
        // 'msme_number'  => 'required',
        // 'email'  => 'required|unique:warehouses,email',
        // 'title' => 'required',
        // 'warehouse_open_time'	  => 'required',
        // 'warehouse_close_time'  => 'required',	
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
        $warehouse->email =  $request->email == null ? null : $request->email[array_search('en', $request->lang)];
        $warehouse->title =  $request->title == null ? null : $request->title[array_search('en', $request->lang)];
        $warehouse->open_time =  $request->open_time == null ? null : $request->open_time[array_search('en', $request->lang)];
        $warehouse->close_time =  $request->close_time == null ? null : $request->close_time[array_search('en', $request->lang)];
        // $warehouse->user_id = $request->user_id[array_search('en', $request->lang)];
        // $warehouse->password = $request->password[array_search('en', $request->lang)];
        // $warehouse->map_location = $request->map_location[array_search('en', $request->lang)];
        // $warehouse->latitude = $request->latitude[array_search('en', $request->lang)];
        // $warehouse->longitude = $request->longitude[array_search('en', $request->lang)];
        // $warehouse->area_id = $request->area_id[array_search('en', $request->lang)];

        // $category = [];
    //     if ($request->revise_open_time != null) {
    //         $category = [
    //             'open_time' => $request->revise_open_time,
    //             'close_time' => $request->revise_close_time,
                
    //         ];
    //     }
    //     $warehouse->revise_open_time = json_encode($category);
    //    dd($warehouse->revise_open_time);
        $warehouse->revise_close_time = $request->revise_close_time == null ? null :  json_encode($request['revise_close_time']);
        $warehouse->delivery_open_time = $request->delivery_open_time == null ? null :  json_encode($request['delivery_open_time']);
        $warehouse->delivery_close_time = $request->delivery_close_time == null ? null :  json_encode($request['delivery_close_time']);
        $warehouse->order_cancel_open_time = $request->order_cancel_open_time == null ? null :  json_encode($request['order_cancel_open_time']);
        $warehouse->order_cancel_close_time = $request->order_cancel_close_time == null ? null :  json_encode($request['order_cancel_close_time']);
        $warehouse->pre_order_open_time = $request->pre_order_open_time == null ? null :  json_encode($request['pre_order_open_time']);
        $warehouse->pre_order_close_time = $request->pre_order_close_time == null ? null :  json_encode($request['pre_order_close_time']);
        $warehouse->save();
 
        //translation
        $data = [];
        // foreach ($request->lang as $index => $key) {
        //     if ($request->name[$index] && $key != 'en') {
        //         $data[] = array(
        //             'translationable_type' => 'App\Model\City',
        //             'translationable_id' => $city->id,
        //             'locale' => $key,
        //             'key' => 'name',
        //             'value' => $request->name[$index],
        //         );
        //     }
        // }
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
        $check_unique =  $this->warehouse->find($id);
        if(!$check_unique){
            $request->validate([
                     'name'  => 'required|unique:warehouses,name',
            'code'  => 'required|unique:warehouses,code',
            'email'  => 'required|unique:warehouses,email',
     ]);
    }
        $request->validate([
         
            'address'  => 'required',
            'owner_name'  => 'required',
            'owner_number'  => 'required',
            'owner_second_number'  => 'required',
            'pin_code'  => 'required',
            'brn_number'  => 'required',
            'msme_number'  => 'required',
            'title' => 'required',
            'open_time'	  => 'required',
            'close_time'  => 'required',	
            'user_id'	  => 'required',
            'password'  => 'required',
            'map_location'  => 'required',
            'latitude'	  => 'required',
            'longitude'  => 'required',
     ]);
    
     
 
    $unit = $this->warehouse->find($id);
    $warehouse->name = $request->name[array_search('en', $request->lang)];
    $warehouse->code = $request->code[array_search('en', $request->lang)];
    $warehouse->address = $request->address[array_search('en', $request->lang)];
    $warehouse->owner_name = $request->owner_name[array_search('en', $request->lang)];
    $warehouse->owner_number = $request->owner_number[array_search('en', $request->lang)];
    $warehouse->owner_second_number = $request->owner_second_number[array_search('en', $request->lang)];
    $warehouse->city_id = $request->city_id[array_search('en', $request->lang)];
    $warehouse->pin_code = $request->pin_code[array_search('en', $request->lang)];
    $warehouse->brn_number = $request->brn_number[array_search('en', $request->lang)];
    $warehouse->msme_number = $request->msme_number[array_search('en', $request->lang)];
    $warehouse->email = $request->email[array_search('en', $request->lang)];
    $warehouse->title = $request->title[array_search('en', $request->lang)];
    $warehouse->open_time = $request->open_time[array_search('en', $request->lang)];
    $warehouse->close_time = $request->close_time[array_search('en', $request->lang)];
    $warehouse->user_id = $request->user_id[array_search('en', $request->lang)];
    $warehouse->password = $request->password[array_search('en', $request->lang)];
    $warehouse->map_location = $request->map_location[array_search('en', $request->lang)];
    $warehouse->latitude = $request->latitude[array_search('en', $request->lang)];
    $warehouse->longitude = $request->longitude[array_search('en', $request->lang)];
    $warehouse->area_id = $request->area_id[array_search('en', $request->lang)];
        $city->save();
        // foreach ($request->lang as $index => $key) {
        //     if ($request->name[$index] && $key != 'en') {
        //         Translation::updateOrInsert(
        //             ['translationable_type' => 'App\Model\city',
        //                 'translationable_id' => $city->id,
        //                 'locale' => $key,
        //                 'key' => 'name'],
        //             ['value' => $request->name[$index]]
        //         );
        //     }
        // }
        Toastr::success( translate('Warehouse updated successfully!') );
        return redirect()->route('admin.city.add');

    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $city = $this->warehouse->find($request->id);
       
        if ($city->childes->count() == 0) {
            $city->delete();
            Toastr::success( translate('city removed!')  );
        } else {
            Toastr::warning( translate('Remove city first!') );
        }
        return back();
    }
}