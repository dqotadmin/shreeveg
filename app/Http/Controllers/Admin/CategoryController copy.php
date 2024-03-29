<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
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

class CategoryController extends Controller
{
    public function __construct(
        private Category $category
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    function index(Request $request): View|Factory|Application
    {
        $query_param = [];
        $search = $request['search'];
        $categories = $this->category;
        if(!empty($request->parent_id)){
            $categories = $categories->where(['parent_id'=>$request->parent_id]);
        }else{
            $categories = $categories->where(['parent_id'=>0]);
        }

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $categories = $categories->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                    $q->orWhere('category_code', 'like', "%{$value}%");
              
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $categories = $categories;
        }

        $categories = $categories->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.category.index', compact('categories', 'search'));
    }

    function create(Request $request): View|Factory|Application
    {
        $query_param = [];
        $search = $request['search'];

        //Category Dropdown
        $categories = $this->category->get();
        $options = Helpers::getCategoryDropDown($categories);

        return view('admin-views.category.add', compact('options', 'search'));

    }

    

    function sub_create(Request $request): View|Factory|Application
    {
        return view('admin-views.category.sub-add');

    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */


   

    function sub_index($id): View|Factory|Application
    {
        // $category = $this->category->withoutGlobalScopes()->with('translations')->find($id);
        // return view('admin-views.category.edit', compact('category'));
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $categories = $this->category->with(['parent'])->where(['position' => 1])
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            $categories = $this->category->with(['parent'])->where(['position' => 1]);
        }
        $categories = $categories->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.category.sub-index', compact('categories', 'search'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $key = explode(' ', $request['search']);
        $categories = $this->category->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->get();
        return response()->json([
            'view' => view('admin-views.category.partials._table', compact('categories'))->render()
        ]);
    }

    /**
     * @return Factory|View|Application
     */
    function sub_sub_index(): View|Factory|Application
    {
        return view('admin-views.category.sub-sub-index');
    }

    /**
     * @return Factory|View|Application
     */
    function sub_category_index(): View|Factory|Application
    {
        return view('admin-views.category.index');
    }

    /**
     * @return Factory|View|Application
     */
    function sub_sub_category_index(): View|Factory|Application
    {
        return view('admin-views.category.index');
    }
 
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    function store(Request $request): RedirectResponse
    {
        $request->validate([
            'category_type' => 'required',
            'name' => 'required|unique:categories,name',
            'category_code' => 'required|unique:categories',
           
        ]);
       
        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(translate('Category name is too long!'));
                return back();
            }
        }
        //uniqueness check
        $parent_id = $request->parent_id ?? 0;
        $all_category = $this->category->where(['parent_id' => $parent_id])->pluck('name')->toArray();

        if (in_array($request->name[0], $all_category)) {
            Toastr::error(translate(($request->parent_id == null ? 'Category' : 'Sub_category') . ' already exists!'));
            return back();
        }

        //image upload
        if (!empty($request->file('image'))) {
            $image_name = Helpers::upload('category/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }
        //into db
        $category = $this->category;

        $category->name = @$request->name[array_search('en', $request->lang)];
        $category->hn_name = @$request->hn_name[array_search('en', $request->lang)];
        
        $category->title_silver = @$request->title_silver[array_search('en', $request->lang)];
        $category->title_gold = @$request->title_gold[array_search('en', $request->lang)];
        $category->title_platinum = @$request->title_platinum[array_search('en', $request->lang)];

        $category->image = $image_name;
        $category->category_code = $request->category_code;
        $category->parent_id = $request->parent_id == null ? 0 : $request->parent_id;
        $category->position = $request->position;
        $category->status = 1;
        dd( $category);
        $category->save();

        //translation
        $data = [];
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                $data[] = array(
                    'translationable_type' => 'App\Model\Category',
                    'translationable_id' => $category->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                );
            }
            if ($request->title_silver[$index] && $key != 'en') {
                $data[] = array(
                    'translationable_type' => 'App\Model\Category',
                    'translationable_id' => $category->id,
                    'locale' => $key,
                    'key' => 'title_silver',
                    'value' => $request->title_silver[$index],
                );
            }
            if ($request->title_gold[$index] && $key != 'en') {
                $data[] = array(
                    'translationable_type' => 'App\Model\Category',
                    'translationable_id' => $category->id,
                    'locale' => $key,
                    'key' => 'title_gold',
                    'value' => $request->title_gold[$index],
                );
            }
            if ($request->title_platinum[$index] && $key != 'en') {
                $data[] = array(
                    'translationable_type' => 'App\Model\Category',
                    'translationable_id' => $category->id,
                    'locale' => $key,
                    'key' => 'title_platinum',
                    'value' => $request->title_platinum[$index],
                );
            }
        }
        if (count($data)) {
            Translation::insert($data);
        }

        Toastr::success($request->parent_id == 0 ? translate('Category Added Successfully!') : translate('Sub Category Added Successfully!'));
        return redirect()->route('admin.category.list');
    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function edit($id): View|Factory|Application
    {
        
        $category = $this->category->withoutGlobalScopes()->with('translations')->find($id);
        //Category Dropdown
        $categories = $this->category->where('id','!=',$category['id'])->get();
        $options = Helpers::getCategoryDropDown($categories, 0, 0, $category['parent_id']);
        
        return view('admin-views.category.edit', compact('category','options'));
    }

    public function sub_edit($id): View|Factory|Application
    {
        $category = $this->category->withoutGlobalScopes()->with('translations')->find($id);
        return view('admin-views.category.sub-edit', compact('category'));
    }

    public function update_unit(Request $request):  \Illuminate\Http\JsonResponse
    {    
        $id = $request->id;
        $sub_unit_title = $request->sub_unit_title;
        $category = $this->category->find($id);
        $category->sub_unit_title = $sub_unit_title;
        $category->save();
        if($category->save()){
        return response()->json(['message' => translate('Unit updated Successfully')]);
        }else{
        return response()->json(['message' => translate('Unit Not updated!')]);

        }
    }
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): RedirectResponse
    {
        $category = $this->category->find($request->id);
        $category->status = $request->status;
        $category->save();
        Toastr::success($category->parent_id == 0 ? translate('Category status updated!') : translate('Sub Category status updated!'));
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
            'category_type' => 'required',
            //'name' => 'required|unique:categories',
            'name' =>'required|unique:categories,name,'.$request->id,
            //'category_code' => 'required|unique:categories',
            'category_code' =>'required|unique:categories,category_code,'.$request->id,
            'title_silver' => 'required',
            'title_gold' => 'required',
            'title_platinum' => 'required',
            
        ]);

        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(translate('Category name is too long!'));
                return back();
            }
        }

        //uniqueness check
        $parent_id = $request->parent_id ?? 0;
        $all_category = $this->category->where(['parent_id' => $parent_id])->where('id','!=',$request->id)->pluck('name')->toArray();

        if (in_array($request->name[0], $all_category)) {
            Toastr::error(translate(($request->parent_id == null ? 'Category' : 'Sub_category') . ' already exists!'));
            return back();
        }
            

        //into db
        $category = $this->category->find($id);
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->title_silver = $request->title_silver[array_search('en', $request->lang)];
        $category->title_gold = $request->title_gold[array_search('en', $request->lang)];
        $category->title_platinum = $request->title_platinum[array_search('en', $request->lang)];

        $category->image = $request->has('image') ? Helpers::update('category/', $category->image, 'png', $request->file('image')) : $category->image;
        $category->category_code = $request->category_code;
        $category->parent_id = $request->parent_id == null ? 0 : $request->parent_id;
        $category->position = $request->position;
        $category->status = 1;
        
        $category->save();
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\Category',
                     'translationable_id' => $category->id,
                     'locale' => $key,
                     'key' => 'name'
                    ],
                    ['value' => $request->name[$index]]
                );                 
            }
            if ($request->title_silver[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\Category',
                     'translationable_id' => $category->id,
                     'locale' => $key,
                     'key' => 'title_silver'
                    ],
                    ['value' => $request->title_silver[$index]]
                );
            }
            if ($request->title_gold[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\Category',
                     'translationable_id' => $category->id,
                     'locale' => $key,
                     'key' => 'title_gold'
                    ],
                    ['value' => $request->title_gold[$index]]
                );
            }
            if ($request->title_platinum[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\Category',
                     'translationable_id' => $category->id,
                     'locale' => $key,
                     'key' => 'title_platinum'
                    ],
                    ['value' => $request->title_platinum[$index]]
                );
            }
        }

        Toastr::success($category->parent_id == 0 ? translate('Category updated successfully!') : translate('Sub Category updated successfully!'));
        return redirect()->route('admin.category.list');


    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $category = $this->category->find($request->id);
        if (Storage::disk('public')->exists('category/' . $category['image'])) {
            Storage::disk('public')->delete('category/' . $category['image']);
        }
        if ($category->childes->count() == 0) {
            $category->delete();
            Toastr::success($category->parent_id == 0 ? translate('Category removed!') : translate('Sub Category removed!'));
        } else {
            Toastr::warning($category->parent_id == 0 ? translate('Remove subcategories first!') : translate('Sub Remove subcategories first!'));
        }
        return back();
    }
}