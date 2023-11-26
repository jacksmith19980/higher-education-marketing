<label>Type of document</label>
<select
    class="form-control-lg select2 form-control custom-select {{ (isset($properties['class']))? $properties['class'] : ' '}}"
    name="{{$name}}_list" {{ (isset($properties['attr']))? $properties['attr'] : ' '}}
   
    @include('front.applications.application-layouts.oiart.partials._sync')
    onchange="app.toggleFileUploadList(this)"
    >

    @if(!$properties['label']['show'])
        <option value="" selected="selected">{{__($label)}}</option>
    @else
        <option value="" selected="selected">{{__('--Select--')}}</option>
    @endif

    @if ($data)

    @foreach ($data as $k=>$v)

    @if ( is_array($v) )

    <optgroup label="{{$k}}">

        @foreach ($v as $key => $item)

        <option value="{{$key}}" @selected($value,$key) {{ ' selected ="selected"' }} @endselected>{{__($item)}}</option>

        @endforeach

    </optgroup>

    @else

    <option value="{{$k}}" @selected($value,$k) {{ ' selected ="selected"' }} @endselected>{{__($v)}}</option>

    @endif

    @endforeach

    @endif

</select>



@isset ($properties['helper'])

<small id="{{$name}}" class="form-text text-info float-left helper-text">{{__($properties['helper'])}}
</small>

@endisset