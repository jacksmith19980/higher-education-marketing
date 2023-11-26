<div class="form-group">

    @label($label)

        <label for="{{$name}}">{{ __($label) }}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</label>

    @endlabel
    <input type="hidden" id="{{$name}}" {{ $required ? ' required' : '' }} name="{{$name}}" class="lookup-field ajax-form-field">

    <div class="inputs">
        <i class="fa fa-search ml-2 mr-2"></i>

        <input  @if(isset($validator_url))
        validator-url="{{route($validator_url)}}" @endif type="text"

            class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }} {{$class}} form-control-lg lookup-field-search"



            @if(isset($placeholder))
                placeholder="{{ __($placeholder) }}"
            @endif
            {{$attr }}

            data-field-id="{{$name}}"

            onkeypress="app.lookupAction(this,'{{$action}}','{{json_encode($data)}}')"
            >

        @if (isset($hint_after))

            <span class="input-group-text" id="basic-addon1">{{__($hint_after)}}</span>

        @endif
    </div>

    <div id="{{$name}}_results" class="lookup-results-container" style="display:none"></div>

</div>
