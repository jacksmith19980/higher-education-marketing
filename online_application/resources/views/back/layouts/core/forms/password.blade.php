<div class="form-group">

    @label($label)

    <label for="{{$name}}">{{ __($label) }}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</label>

    @endlabel

    @if (isset($hint_before) || isset($hint_after))

            <div class="input-group-prepend">

    @endif

        @if (isset($hint_before))

            <span class="input-group-text" id="basic-addon1">{{$hint_before}}</span>

        @endif


    <input id="{{$name}}"
    	   type="password"
    	   class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }} {{$class}} form-control-lg"

            name="{{$name}}"

            @if($value)
                    value="{{ $value }}"
               @else
                    value="{{ old($name) }}"
            @endif

    	   @if (isset($placeholder))
            placeholder="{{$placeholder}}"
           @endif

           {{ $required ? ' required' : '' }}

           {{ $attr }}

           >

           @if (isset($hint_after))

            <span class="input-group-text" id="basic-addon1">{{__($hint_after)}}</span>

           @endif


    @if (isset($hint_before) || isset($hint_after))
        </div>
    @endif

    @isset ($helper)
        <small id="{{$name}}" class="form-text text-info float-left helper-text">{{__($helper)}}</small><br />
    @endisset

    @if ($errors->has($name))
        @foreach ($errors->get($name) as $message)
            <span class="invalid-feedback" role="alert" style="display:block">
                <strong>{{ $message }}</strong>
            </span>
        @endforeach
    @endif


</div>
