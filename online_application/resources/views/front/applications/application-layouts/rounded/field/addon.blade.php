@if ($params['repeater'] != null )
    @php
        $properties['class'] = $properties['class'] . ' repeated_field';
    @endphp
@endif

<input type="hidden" name="application" value="{{ $application->id }}">
<input type="hidden" name="submission" value="{{ $submission->id }}">

<div class="field_wrapper {{($properties['smart'])? 'smart-field ' : ''}} col-md-{{$properties['wrapper']['columns']}} col-sm-12 col-xs-12 {{$properties['wrapper']['class']}}" {{ (!$properties['show'])? 'data-hidden=true' : ' ' }}


@smart($properties['smart'])

@if (!isset($properties['logic']['type']))
    @php
        $ref_field= $properties['logic']['field']
    @endphp

@else

    @php
        $ref_field = ( in_array($properties['logic']['type'] , ['checkbox']) ) ? $properties['logic']['field']."[]" : $properties['logic']['field'];
    @endphp

@endif

    @if(isset($params['order']))
    @php
        $ref_field = $ref_field.'[' . $params['order'] . ']';
    @endphp
    @endif
data-field="{{$name}}"

data-action="{{$properties['logic']['action']}}"

data-reference="{{$ref_field}}"

data-operator="{{$properties['logic']['operator']}}"

@if (!is_array($properties['logic']['value']))

    data-value="{{$properties['logic']['value']}}"

@else

    data-value="{{implode(",",$properties['logic']['value'])}}"

@endif

@endsmart

>
@if($properties['label']['show'])

    <label for="{{$name}}">{!!  __($properties['label']['text']) !!}

        @if(isset($properties['validation']['required']))

            <span class="text-danger">*</span>

        @endif

    </label><br />

@endif
@foreach ($data as $val=>$lab)

    <div class="form-check form-check-inline d-block {{$properties['class']}}">
        <div class="custom-control custom-checkbox">

            @php
                $is_checked = '';
            @endphp

            @if (isset($value) && is_array($value))

                @if ( in_array( $val , $value ) || array_key_exists($val, $value))

                    @php
                        $is_checked = 'checked';
                    @endphp

                @endif

            @elseif(!is_array($value))

                @if ($value == $val )

                    @php
                        $is_checked = 'checked';
                    @endphp

                @endif


            @endif

            <input type="checkbox" class="custom-control-input {{ (isset($properties['class']))? $properties['class'] : ' '}}" name="{{$name}}{{( count($data) > 1 ) ? '['.$val.']' : ''}}" id="{{$name}}_{{$val}}" value="{{$lab['price']}}" {{$is_checked}}
                onchange="app.addons(this, `{{$lab['label']}}`, {{$lab['price']}})"
                data-syncroute="{{route('cart.addons', $school)}}"
                    @include('front.applications.application-layouts.gbsg.partials._validation-messages')

            >

            <label class="custom-control-label" for="{{$name}}_{{$val}}">{!! __($lab['label']) !!} - {{$settings['school']['default_currency']}}{{number_format($lab['price'])}}</label>
        </div>
    </div>
@endforeach

<div class="error_{{$name}} error_container"></div>

@isset ($properties['helper'])

    <small id="{{$name}}" class="float-left form-text text-info helper-text">

        {{$properties['helper']}}

    </small>

@endisset



@if ($errors->has($name))

    <span class="invalid-feedback" role="alert">

					            <strong>{{ $errors->first($name) }}</strong>

					        </span>

    @endif
    </div>


