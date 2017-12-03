@extends('layouts.adminlayout')

@section('content')

<div class="container" id="tablegrid">

    @include('layouts.partials.adminnav')

    @include('layouts.partials.errors')

    <form action="{{ route('items.store') }}" method="post">

        {{ csrf_field() }}

        <h2 class="sectionTitle">Add</h2>
        <div class="sectionForm">
        <input type="text" size="30" name="title" placeholder="Title">
        <input type="text" size="60" name="description" placeholder="Description">
        <input class="btn btn-primary" type="submit" value="Add Item">
        </div>

    </form>

    <div style="clear:both;"> </div>

    <form action="{{ route('items.index') }}" method="get">
        <h2 class="sectionTitle">Edit</h2>
        <div class="sectionForm">
            <input type='text' name='search' size='20' placeholder='Search' value="{{ $search }}">
            <input type='submit' value='Search' class='btn btn-primary'>
        </div>
    </form>

    @php

    $searchQStr = '';
    if (!empty($search)) {
        $searchQStr = "&search=" . urlencode($search);
    }
    $ascActive = '';
    $descActive = '';
    $newActive = '';
    $oldActive = '';
    if ($sort == 'asc') {
        $ascActive = 'activeLink';
    } elseif ($sort == 'old') {
        $oldActive = 'activeLink';
    } elseif ($sort == 'new') {
        $newActive = 'activeLink';
    } else {
        $descActive = 'activeLink';
    }

    echo '<ul style="padding-left:20px;margin-top:20px;" class="nav nav-pills">';

    echo '<li class="nav-item" style="margin-top:10px;font-weight:bold;">Sort:</li>';

    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $descActive . '" href="/items?sort=desc' . $searchQStr . '">Alpha Desc</a>';
    echo '</li>';

    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $ascActive . '" href="/items?sort=asc' . $searchQStr . '">Alpha Asc</a>';
    echo '</li>';

    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $newActive . '" href="/items?sort=new' . $searchQStr . '">Newest First</a>';
    echo '</li>';

    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $oldActive . '" href="/items?sort=old' . $searchQStr . '">Oldest First</a>';
    echo '</li>';

    echo '</ul>';

    @endphp

    <div style="clear:both;"></div>
    <table border="0" cellpadding="4" cellspacing="4">
        @foreach( $items as $item )
            <form id="form_{{ $item->id }}" action="{{ route('items.update', $item) }}" method="post">
            <input type="hidden" name="on_page" value="{{$items->currentPage()}}">
            {{ csrf_field() }}
            <tr>
                <td>
                    <input type="text" size="30" name="title" value="{{ $item->title }}">
                    <input type="hidden" size="30" name="title_old" value="{{ $item->title }}">
                </td>
                <td>
                    <input type="text" size="60" name="description" value="{{ $item->description }}">
                </td>
                <td>
                    <button class="btn btn-primary" name="edit">Submit Edit</button>
                </td>
                <td>
                    <a class="btn btn-danger" href="{{ route('items.delete', $item )}}" onclick="return confirm('Really delete?');">Delete</a>

                </td>
            </tr>
            <tr>
                <td class="tdTitle">Parent Categories</td>
                <td colspan="2">
                {{--item_cat_id is the primary key in items_cats join table--}}
                @if (empty($selectedCatsArr))
                    @include('layouts.partials.catsdd', [
                        'items_cats_id' => 0,
                        'catsArr' => $catsArr,
                        'selectedCatsId' => 0,
                        'itemsId' => $item->id
                    ])
                @else
                    @foreach($selectedCatsArr as $i => $selectedCat)
                        @include('layouts.partials.catsdd', [
                            'items_cats_id' => $selectedCat->id,
                            'catsArr' => $catsArr,
                            'selectedCatsId' => $selectedCat->cats_id,
                            'itemsId' => $item->id
                        ])
                    @endforeach
                @endif
                </td>
            </tr>
            </form>
        @endforeach
    </table>

    {!! $items->appends(['sort' => $sort, 'search' => $search])->render() !!}

@php
echo "<pre>";

print_r(DB::getQueryLog());
print_r([$sort, $search]);
echo "</pre>";
@endphp

</div>
@endsection