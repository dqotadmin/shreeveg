<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Store;
use App\Model\Translation;
use App\Model\City;
use App\Model\Warehouse;
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

class StoreController extends Controller
{
    public function __construct(
        private Store $store,
        private City $city
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
            $stores = $this->store->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%");
                    $q->orWhere('description', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $stores = $this->store;
        }
        $stores = $stores->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.store.index', compact('stores', 'search'));
    }


    function create(Request $request): View|Factory|Application
    {
        $stores = $this->store->orderBy('id','desc')->first();
        if($stores && $stores->id < 9){
            $prevId = ($stores && $stores->id)?'0'.$stores->id+1:'01';
        }else{
            $prevId = ($stores && $stores->id)?$stores->id+1:'01';

        }
       return view('admin-views.store.add',compact('prevId'));

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
        $stores = $this->store->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('title', 'like', "%{$value}%");
            }
        })->get();
        return response()->json([
            'view' => view('admin-views.store.store', compact('stores'))->render()
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
            'warehouse_id' => 'required',
            'owner_id' => 'required',
            'city_id' => 'required',
            'code' => 'required|unique:stores',
        ],[
            'warehouse_id.required'=>translate('Warehouse is required'),
            'owner_id.required'=>translate('Owner name is required'),
            'city_id.required'=>translate('City Name is required'),
        ]);

      
        //into db
        $store = $this->store;
        $store->name = $request->name == null ? null : $request->name;
        $store->code = $request->code == null ? null : $request->code;
        $store->address = $request->address == null ? null : $request->address;
        $store->warehouse_id = $request->warehouse_id == null ? null : $request->warehouse_id;
        $store->owner_id = $request->owner_id == null ? null : $request->owner_id;
        $store->city_id = $request->city_id == null ? null : $request->city_id;
        $store->pin_code = $request->pin_code == null ? null : $request->pin_code;
        $store->brn_number = $request->brn_number == null ? null : $request->brn_number;
        $store->msme_number = $request->msme_number == null ? null : $request->msme_number;
        $store->shop_licence = $request->shop_licence == null ? null : $request->shop_licence;
        $store->title = $request->title == null ? null : $request->title;

        $store->latitude = $request->latitude == null ? null : $request->latitude;
        $store->longitude = $request->longitude == null ? null : $request->longitude;
        $store->coverage = $request->coverage == null ? null : $request->coverage;
        $store->document = $request->has('document') ? Helpers::update('store/', $store->document, 'png', $request->file('document')) : $store->document;
        $store->save();
       

        Toastr::success(translate('unit Added Successfully!') );
        return redirect()->route('admin.store.index');

    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function edit($id): View|Factory|Application
    {
        $stores = $this->store->find($id);
        return view('admin-views.store.edit', compact('stores'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): RedirectResponse
    {
        $store = $this->store->find($request->id);
        $store->status = $request->status;
        $store->save();
        Toastr::success(translate('Store status updated!'));
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
            'warehouse_id' => 'required',
            'owner_id' => 'required',
            'city_id' => 'required',
            'name' => 'required',
            'code' => [
                'required',
                    Rule::unique('stores')->ignore($id),
            ]
        ],[
            'warehouse_id.required'=>translate('Warehouse  is required'),
            'owner_id.required'=>translate('Owner name is required'),
            'city_id.required'=>translate('City Name is required'),
        ]);

      

        $store = $this->store->find($id);
        $store->name = $request->name == null ? null : $request->name;
        $store->code = $request->code == null ? null : $request->code;
        $store->address = $request->address == null ? null : $request->address;
        $store->warehouse_id = $request->warehouse_id == null ? null : $request->warehouse_id;
        $store->owner_id = $request->owner_id == null ? null : $request->owner_id;
        $store->city_id = $request->city_id == null ? null : $request->city_id;
        $store->pin_code = $request->pin_code == null ? null : $request->pin_code;
        $store->brn_number = $request->brn_number == null ? null : $request->brn_number;
        $store->msme_number = $request->msme_number == null ? null : $request->msme_number;
        $store->shop_licence = $request->shop_licence == null ? null : $request->shop_licence;
        $store->title = $request->title == null ? null : $request->title;

        $store->latitude = $request->latitude == null ? null : $request->latitude;
        $store->longitude = $request->longitude == null ? null : $request->longitude;
        $store->coverage = $request->coverage == null ? null : $request->coverage;
        $store->document = $request->has('document') ? Helpers::update('store/', $store->document, 'png', $request->file('document')) : $store->document;
       
        $store->save();
        
        Toastr::success( translate('Store updated successfully!') );
        return redirect()->route('admin.store.index');

    }
    public function get_city($stateId = null){
            $city = $this->city->whereStatus('1')->get();
          
        return response()->json(['city' => $city]);
    }
    public function get_warehouse($cityId=null){
        $warehouse = Warehouse::query();
        $store_city_id = $this->store->where('status',1)->count();

        if($store_city_id < 9){
            $prevId = ($store_city_id)?'0'.$store_city_id+1:'01';
        }else{
            $prevId = ($store_city_id)? $store_city_id+1:'01';

        }
        if ($cityId) {
            $warehouse = $warehouse->where('status',1)->where('city_id', $cityId)->get();
            
            if ($warehouse->isEmpty()) {
                return response()->json(['message' => 'Warehouses not found for the given city.']);
            }
        } else {
            return response()->json(['message' => 'City ID is not provided.']);
        }
        return response()->json(['warehouse' => $warehouse, 'prevId' => $prevId]);
        
    
    }
   

    /** 
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $store = $this->store->find($request->id);
        $store->update(['deleted_by' => auth('admin')->user()->id]);
       
        if ($store) {
            $store->delete();
            Toastr::success( translate('Store removed!')  );
        } else {
            Toastr::warning( translate('Store not removed!') );
        }
        return back();
    }
}