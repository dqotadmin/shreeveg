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
            $cityareas = $this->cityarea->where(['position' => 0])->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('area', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $cityareas = $this->cityarea->where(['position' => 0]);
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
            'latitude_code' => 'required',
            'longitude_code' => 'required',
            'radius' => 'required',
        ]);

        foreach ($request->area as $name) {
            if (strlen($name) > 30) {
                toastr::error(translate('Area is too long!'));
                return back();
            }
        }
        //into db
        $cityarea = $this->cityarea;
        $cityarea->city_id = $request->city_id[array_search('en', $request->lang)];
        $cityarea->area = $request->area[array_search('en', $request->lang)];
        $cityarea->latitude_code = $request->latitude_code[array_search('en', $request->lang)];
        $cityarea->longitude_code = $request->longitude_code[array_search('en', $request->lang)];
        $cityarea->radius = $request->radius[array_search('en', $request->lang)];
        $cityarea->position = $request->position;
        $cityarea->save();

        //translation
        $data = [];
        // foreach ($request->lang as $index => $key) {
        //     if ($request->name[$index] && $key != 'en') {
        //         $data[] = array(
        //             'translationable_type' => 'App\Model\cityarea',
        //             'translationable_id' => $cityarea->id,
        //             'locale' => $key,
        //             'key' => 'name',
        //             'value' => $request->name[$index],
        //         );
        //     }
        // }
        if (count($data)) {
            Translation::insert($data);
        }

        Toastr::success(translate('cityarea Added Successfully!') );
        return back();
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
        Toastr::success(translate('cityarea status updated!'));
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
            'area' => 'required|unique:city_areas',
            'latitude_code' => 'required',
            'longitude_code' => 'required',
            'radius' => 'required',
     ]);

     
     foreach ($request->area as $name) {
        if (strlen($name) > 10) {
            toastr::error(translate('area is too long!'));
            return back();
        }
    }

        $cityarea = $this->cityarea->find($id);
        $cityarea->city_id = $request->city_id[array_search('en', $request->lang)];
        $cityarea->area = $request->area[array_search('en', $request->lang)];
        $cityarea->latitude_code = $request->latitude_code[array_search('en', $request->lang)];
        $cityarea->longitude_code = $request->longitude_code[array_search('en', $request->lang)];
        $cityarea->radius = $request->radius[array_search('en', $request->lang)];
        $cityarea->position = $request->position;
        $cityarea->save();
        // foreach ($request->lang as $index => $key) {
        //     if ($request->name[$index] && $key != 'en') {
        //         Translation::updateOrInsert(
        //             ['translationable_type' => 'App\Model\cityarea',
        //                 'translationable_id' => $cityarea->id,
        //                 'locale' => $key,
        //                 'key' => 'name'],
        //             ['value' => $request->name[$index]]
        //         );
        //     }
        // }
        Toastr::success( translate('cityarea updated successfully!') );
        return redirect()->route('admin.area.add');

    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $cityarea = $this->cityarea->find($request->id);
       
        if ($cityarea->childes->count() == 0) {
            $cityarea->delete();
            Toastr::success( translate('cityarea removed!')  );
        } else {
            Toastr::warning( translate('Remove subcityareas first!') );
        }
        return back();
    }
}
