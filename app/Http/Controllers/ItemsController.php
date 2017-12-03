<?php

namespace App\Http\Controllers;
use Auth;

use Input;
use App\Items;
use App\ItemsCats;
use App\Cats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemsController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    /*
     * Associate an items_id with a cats_id in the items_cats join table
     */
    public function updateItemCat(Request $request)
    {
        $request->validate([
            'cats_id' => 'integer',
            'items_id' => 'integer',
            'items_cats_id' => 'integer'
        ]);
        $catsId = $request->cats_id;
        $itemsId = $request->items_id;
        $itemsCatsId = $request->items_cats_id;
        $itemsCats = new ItemsCats();
        if ($itemsCatsId == 0) {
            ItemsCats::create(['cats_id' => $catsId, 'items_id' => $request->items_id]);
        } else {
            $itemsCats->where('id', $itemsCatsId)->update(['items_id' => $itemsId, 'cats_id' => $catsId]);
        }

        return response()->json(array($catsId,$itemsId));

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

        $cats = new Cats();
        $catsArr = $cats->get();

        $itemsIdArr = array();
        foreach ($items as $item) {
            $itemsIdArr[] = $item->id;
        }
//        $itemsCats = new ItemsCats();
        $selectedCatsArr = array();
        if (count($itemsIdArr)) {
            $selectedCatsArr = DB::table('items_cats')->whereIn('items_id', $itemsIdArr)->get();
        }

        return view(
            'items.index',
            compact('items', 'sort', 'search', 'catsArr', 'selectedCatsArr')
        );
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
        $uniqueTitleValidation = '';
        if (trim(strtolower($request->title_old)) != trim(strtolower($request->title))) {
            $uniqueTitleValidation = '|unique:items';
        }
        $request->validate([
            'title' => 'required|min:3|max:30|regex:/^[a-zA-Z0-9_ -]+$/' . $uniqueTitleValidation,
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
