@extends('layouts.adminlayout')

@section('content')

<div class="container" id="tablegrid">

    @include('layouts.partials.tasksnav')

    @include('layouts.partials.errors')

    <form action="{{ route('tasks.store') }}" method="post">

        {{ csrf_field() }}

        <input type="text" size="30" name="title" placeholder="Title">
        <input type="text" size="60" name="description" placeholder="Description">


        <input class="btn btn-primary" type="submit" value="Add Task">

    </form>

    <form action="{{ route('tasks.index') }}" method="get">
        <h2 style="float:left;">Tasks</h2>
        <div style="float:left;padding-left:20px;margin-top:20px;">
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
    echo '<a class="nav-link ' . $descActive . '" href="/tasks?sort=desc' . $searchQStr . '">Alpha Desc</a>';
    echo '</li>';

    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $ascActive . '" href="/tasks?sort=asc' . $searchQStr . '">Alpha Asc</a>';
    echo '</li>';

    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $newActive . '" href="/tasks?sort=new' . $searchQStr . '">Newest First</a>';
    echo '</li>';

    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $oldActive . '" href="/tasks?sort=old' . $searchQStr . '">Oldest First</a>';
    echo '</li>';

    echo '</ul>';

    @endphp

    <div style="clear:both;"></div>
    <table border="0" cellpadding="4" cellspacing="4">
        @foreach( $tasks as $task )
            <form action="{{ route('tasks.update', $task) }}" method="post">
            <input type="hidden" name="on_page" value="{{$tasks->currentPage()}}">
            {{ csrf_field() }}
            <tr>
                <td>
                    <input type="text" size="30" name="title" value="{{ $task->title }}">
                </td>
                <td>
                    <input type="text" size="60" name="title" value="{{ $task->description }}">
                </td>
                <td>
                    <button class="btn btn-primary" name="edit">Submit Edit</button>
                </td>
                <td>
                    <a class="btn btn-danger" href="{{ route('tasks.delete', $task )}}" onclick="return confirm('Really delete?');">Delete</a>

                </td>
            </tr>
            </form>
        @endforeach
    </table>

    {!! $tasks->appends(['sort' => $sort, 'search' => $search])->render() !!}

@php
echo "<pre>";

print_r(DB::getQueryLog());
print_r([$sort, $search]);
echo "</pre>";
@endphp

</div>
@endsection