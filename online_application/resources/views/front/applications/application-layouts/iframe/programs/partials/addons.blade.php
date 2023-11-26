<div class="col-md-12">Program Addons</div>

@foreach($addonsData as $addons)
    <div class="col-md-6">
        <div class="custom-control  custom-checkbox" style="margin-top: 7px;">
            <input class="custom-control-input" name="{{$field->name}}[program_addons][]"
                   id="{{$addons['addon_options']}}" value="{{$addons['addon_options']}}" type="checkbox"
                   data-program="{{$program->slug}}"
                   data-syncroute="{{route('cart.addonsProgram', $school)}}"
                   onchange="app.addonsProgram(this)"
            @if(isset($selected))
                {{in_array($addons['addon_options'], $selected)? ' checked' : ' '}}
            @endif
            >
            <label class="custom-control-label" for="{{$addons['addon_options']}}">{{$addons['addon_options']}}</label></div>
    </div>
@endforeach