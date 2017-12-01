<?php

namespace App\Http\Controllers;
use Auth;

use Input;
use App\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemsController extends Controller
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
        $items = new Items();
        if ($sort == 'old') {
            $items = $items->orderBy('created_at', 'asc');
        } elseif ($sort == 'asc') {
            $items = $items->orderBy('title', 'asc');
        } elseif ($sort == 'desc') {
            $items = $items->orderBy('title', 'desc');
        } else {
            $items = $items->orderBy('created_at', 'desc');
        }
        if (!empty($search)) {
            $items = $items->where("title", "like", "%" . $search . "%");
        }

        $items = $items->paginate(3);

        return view('items.index', compact('items', 'sort', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('items.create');
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
            'title' => 'required|min:3|unique:items|max:30|regex:/^[a-zA-Z0-9_ -]+$/',
            'description' => 'nullable|regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        Items::create(['title' => $request->title,'description' => $request->description]);
        return redirect(route('items.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Items  $items
     * @return \Illuminate\Http\Response
     */
    public function show(Items $items)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Items  $items
     * @return \Illuminate\Http\Response
     */
    public function edit(Items $items)
    {
        return view('items.edit', compact('items'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Items  $items
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Items $items)
    {
        $request->validate([
            'title' => 'required|min:3|unique:items|max:30|regex:/^[a-zA-Z0-9_ -]+$/',
            'description' => 'nullable|regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        $items->title = $request->title;
        $items->description = $request->description;
        $items->update();
        $page = $request->input('on_page');
        if (empty($page)) {
            $arr = array();
        } else {
            $arr = ['page' => $page];
        }

        return redirect()->route('items.index', $arr);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Items  $items
     * @return \Illuminate\Http\Response
     */
    public function destroy(Items $items)
    {
        $items->delete();
        return redirect(route('items.index'));
    }
}
