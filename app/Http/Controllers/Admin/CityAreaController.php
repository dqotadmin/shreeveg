<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\CityArea;
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
class CityAreaController extends Controller
{
    public function __construct(
        private CityArea $cityarea
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
            $cityareas = $this->cityarea->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('area', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $cityareas = $this->cityarea;
        }
        $cityareas = $cityareas->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.city-area.index', compact('cityareas', 'search'));
    }
    function create(Request $request): View|Factory|Application
    {
       return view('admin-views.city-area.add');

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
        $cityareas = $this->cityarea->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('area', 'like', "%{$value}%");
            }
        })->get();
        return response()->json([
            'view' => view('admin-views.city-area.cityarea', compact('cityareas'))->render()
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
            'area' => 'required|unique:city_areas',
          
        ]);

            if (strlen($request->area) > 30) {
                toastr::error(translate('Area Name is too long!'));
                return back();
            }
        //into db
        $cityarea = $this->cityarea;
        $cityarea->city_id = $request->city_id;
        $cityarea->area = $request->area;
        $cityarea->pincode = $request->pincode;
        // $cityarea->latitude_code = $request->latitude_code;
        // $cityarea->longitude_code = $request->longitude_code;
        // $cityarea->radius = $request->radius;
        $cityarea->save();

        Toastr::success(translate('Area Added Successfully!') );
        return redirect()->route('admin.area.list');

    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function edit($id): View|Factory|Application
    {
        $cityareas = $this->cityarea->withoutGlobalScopes()->with('translations')->find($id);
        return view('admin-views.city-area.edit', compact('cityareas'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): RedirectResponse
    {
        $cityarea = $this->cityarea->find($request->id);
        $cityarea->status = $request->status;
        $cityarea->save();
        Toastr::success(translate('Area status updated!'));
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
            'area' =>    ['required',
            Rule::unique('city_areas')->ignore($id)
             ],
            //  'latitude_code' => 'required',
            // 'longitude_code' => 'required',
            // 'radius' => 'required',
     ]);

     
        if (strlen($request->area) > 20) {
            toastr::error(translate('Area is too long!'));
            return back();
        }

        $cityarea = $this->cityarea->find($id);
        $cityarea->city_id = $request->city_id;
        $cityarea->area = $request->area;
        $cityarea->pincode = $request->pincode;
        // $cityarea->latitude_code = $request->latitude_code;
        // $cityarea->longitude_code = $request->longitude_code;
        // $cityarea->radius = $request->radius;
        $cityarea->save();
     
        Toastr::success( translate('Area updated successfully!') );
        return redirect()->route('admin.area.list');

    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $cityarea = $this->cityarea->find($request->id);
       
        if ($cityarea) {
            $cityarea->delete();
            Toastr::success( translate('Area removed!')  );
        } 
        return back();
    }
}
