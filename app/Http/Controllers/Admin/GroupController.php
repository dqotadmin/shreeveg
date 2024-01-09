<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Banner;
use App\Model\Category;
use App\Model\Product;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class GroupController extends Controller
{
    protected $mTable;
    protected $view_folder;

    public function __construct()
    {
        $this->view_folder = 'admin-views.groups';
        $this->mTable = '\App\Model\Group';
    }

    /**
     * @param Request $request
     * @return Factory|View|Application
     */
    function index(Request $request): View|Factory|Application
    {

        $query_param = [];
        $search = $request['search'];
        $rows = $this->mTable::query();

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $rows = $rows->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                    $q->orWhere('description', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }
        $rows = $rows->orderBy('id', 'desc')->paginate(Helpers::getPagination())->appends($query_param);
        return view($this->view_folder . '.index', compact('rows', 'search'));
    }



    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|max:255',
            'image' => 'required',
        ], [
            'name.required' => translate('group name is required'),
            'image.required' => translate('Image is required'),
        ]);

        $row = new $this->mTable;
        $row->name = $request->name;
        $row->description = $request->description;
        if ($request->file('image')) {
            $row->image = Helpers::upload('groups/', 'png', $request->file('image'));
        }

        $row->save();
        Toastr::success(translate('Banner added successfully!'));
        return back();
    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function edit($id): View|Factory|Application
    {
        $row = $this->mTable::find($id);
        return view($this->view_folder . '.edit', compact('row'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): RedirectResponse
    {
        $row = $this->mTable::find($request->id);
        $row->status = $request->status;
        $row->save();
        Toastr::success(translate('group status updated!'));
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
            'name' => 'required|max:255',
        ], [
            'name.required' => 'name is required!',
        ]);

        $row = $this->mTable::find($id);
        $row->name = $request->name;
        $row->description = $request->description;

        $row->image = $request->has('image') ? Helpers::update('groups/', $row->image, 'png', $request->file('image')) : $row->image;
        $row->save();
        Toastr::success(translate('group updated successfully!'));
        return redirect()->route('admin.business-settings.groups.index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $row = $this->mTable::find($request->id);
        if (Storage::disk('public')->exists('groups/' . $row['image'])) {
            Storage::disk('public')->delete('groups/' . $row['image']);
        }
        $row->delete();
        Toastr::success(translate('group removed!'));
        return back();
    }
}
