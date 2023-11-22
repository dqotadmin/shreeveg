<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\City;
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

class CityController extends Controller
{
    public function __construct(
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
            $cities = $this->city->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('city', 'like', "%{$value}%")
                    ->orWhere('city_code', 'like', "%{$value}%")
                    ->orWhereHas('states',function($q2) use ($value){
                        $q2->where('name', 'like', "%{$value}%");
                    });
                    }
            });

            $query_param = ['search' => $request['search']];
        } else {
            $cities = $this->city;
        }
        $cities = $cities->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.city.index', compact('cities', 'search'));
    }
    function create(Request $request): View|Factory|Application
    {
       return view('admin-views.city.add');

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
        $cities = $this->city->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('city', 'like', "%{$value}%");
            }
        })->get();
        return response()->json([
            'view' => view('admin-views.city.city', compact('cities'))->render()
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
            'city' => 'required|unique:cities',
            'state_id' => 'required',
        ]);

            if (strlen($request->city) > 20) {
                toastr::error(translate('City is too long!'));
                return back();
            }

        //into db
        $city = $this->city;
        $city->city = $request->city;
        $city->city_code = $request->city_code;
        $city->state_id = $request->state_id;
        $city->save();


        Toastr::success(translate('City Added Successfully!') );
        return redirect()->route('admin.city.add');
    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function edit($id): View|Factory|Application
    {
        $cities = $this->city->withoutGlobalScopes()->with('translations')->find($id);
        return view('admin-views.city.edit', compact('cities'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): RedirectResponse
    {
        $city = $this->city->find($request->id);
        $city->status = $request->status;
        $city->save();
        Toastr::success(translate('city status updated!'));
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
            'city' =>'required|unique:cities,city,'.$request->id,
            'state_id' => 'required',
     ]);

     
        if (strlen($request->city) > 20) {
            toastr::error(translate('city is too long!'));
            return back();
    }

        $city = $this->city->find($id);
        $city->city = $request->city;
        $city->city_code = $request->city_code;
        $city->state_id = $request->state_id;
        $city->save();
       
        Toastr::success( translate('city updated successfully!') );
        return redirect()->route('admin.city.add');

    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $city = $this->city->find($request->id);
       
        if ($city) {
            $city->delete();
            Toastr::success( translate('city removed!')  );
        } else {
            Toastr::warning( translate('Remove city first!') );
        }
        return back();
    }
}
