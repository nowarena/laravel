<select class="catsDD" data-itemscatsid="{{ $items_cats_id }}" data-itemsid="{{ $itemsId }}">
    <option value="">Select Category</option>
    @foreach($catsArr as $cat)
        <option value="{{ $cat->id }}"
        @if ($cat->id == $selectedCatsId)
                selected
        @endif
        > {{$cat->title}}</option>
    @endforeach
</select>
