<?php

namespace App\Http\Controllers;
use Auth;

use Input;
use App\Cat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        DB::enableQueryLog();
        $request->validate([
            'search' => 'nullable|min:3|max:255|regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        $search = $request->search;
        $sort = $request->sort;
        $cat = new Cat();
        if ($sort == 'old') {
            $cat = $cat->orderBy('created_at', 'asc');
        } elseif ($sort == 'asc') {
            $cat = $cat->orderBy('title', 'asc');
        } elseif ($sort == 'desc') {
            $cat = $cat->orderBy('title', 'desc');
        } else {
            $cat = $cat->orderBy('created_at', 'desc');
        }
        if (!empty($search)) {
            $cat = $cat->where("title", "like", "%" . $search . "%");
        }

        $cats = $cat->paginate(3);

        return view('cat.index', compact('cats', 'sort', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cat.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|unique:cat|max:30|regex:/^[a-zA-Z0-9_ -]+$/',
            'description' => 'nullable|regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        Cat::create(['title' => $request->title,'description' => $request->description]);
        return redirect(route('cat.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cat  $cat
     * @return \Illuminate\Http\Response
     */
    public function show(Cat $cat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cat  $cat
     * @return \Illuminate\Http\Response
     */
    public function edit(Cat $cat)
    {
        return view('cat.edit', compact('cat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cat  $cat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cat $cat)
    {
        $request->validate([
            'title' => 'required|min:3|unique:cat|max:30|regex:/^[a-zA-Z0-9_ -]+$/',
            'description' => 'nullable|regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        $cat->title = $request->title;
        $cat->description = $request->description;
        $cat->update();
        $page = $request->input('on_page');
        if (empty($page)) {
            $arr = array();
        } else {
            $arr = ['page' => $page];
        }

        return redirect()->route('cat.index', $arr);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cat  $cat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cat $cat)
    {
        $cat->delete();
        return redirect(route('cat.index'));
    }
}
