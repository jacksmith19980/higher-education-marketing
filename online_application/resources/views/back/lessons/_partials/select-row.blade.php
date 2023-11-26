<div>
    <span class="mr-5">
        <input type="checkbox" @if(isset($target)) target="{{ $target }}" @endif name="multi-select" value="{{$item->id}}" onchange="app.selectRow(this)" />
    </span>

</div>
