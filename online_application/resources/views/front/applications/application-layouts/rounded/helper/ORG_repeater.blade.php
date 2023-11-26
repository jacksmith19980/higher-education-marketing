@php
    $fields = [];
    foreach ( $section->fields as $item) {
        if(in_array($item->name , $props['properties']['fields'])){
            $fields[$item->id] = $item;
        }
    }
    $order = $section->fields_order;
    uksort($fields, function($key1, $key2) use ( $order) {
        return ((array_search($key1, $order) > array_search($key2, $order)) ? 1 : -1);
    });
    @endphp
@php
    $numberofRepeats  = (isset($submission->data[ $props['name'] ])) ? $submission->data[ $props['name'] ] : 1 ;
    $max  = (isset($props['properties']['max'])) ? $props['properties']['max'] : 1000000000 ;
@endphp
    <input type="hidden" readonly class="repeater_count" name="{{$props['name']}}" value="{{$numberofRepeats}}" />

    <input type="hidden" readonly class="repeater_max" name="repeater_max_{{$props['name']}}" value="{{$max}}" />
    {{--  @dump($submission->data)  --}}
    <div class="col-md-12 {{($props['properties']['smart'])? 'smart-field ' : ''}}"
        @smart($props['properties']['smart'])
            @if (!isset($props['properties']['logic']['type']))
                @php
                    $ref_field= $props['properties']['logic']['field']
                @endphp
            @else
                @php
                $ref_field = ( in_array($props['properties']['logic']['type'] , ['checkbox'])) ? $props['properties']['logic']['field']."[]" : $props['properties']['logic']['field'];
                @endphp
            @endif

            @if(isset($params['order']))
                @php
                    $ref_field = $ref_field.'[' . $params['order'] . ']';
                @endphp
            @endif

            data-field="{{$props['name']}}"

            data-repeater-fields = "{{json_encode($props['properties']['fields'])}}"

            data-action="{{$props['properties']['logic']['action']}}"

            data-reference="{{$ref_field}}"

            data-operator="{{$props['properties']['logic']['operator']}}"

            @if (!is_array($props['properties']['logic']['value']))

                data-value="{{$props['properties']['logic']['value']}}"

            @else

                data-value="{{implode(",",$props['properties']['logic']['value'])}}"

            @endif

        @endsmart
        >
    @php
        for($i = 1 ; $i <= $numberofRepeats  ; $i++ ):

        //@dump($field)

    @endphp
    <div class="repeater_box box_{{$props['name']}}">
        <div class="row">
            @foreach ($fields as $repeaterField)
                @php
                    $name = $repeaterField->name.'[';
                    $name = isset($i) ? $name.$i.']' : $name . ']';

                    $params = [
                        'properties' => $repeaterField->properties,
                        'data'       => $repeaterField->data,
                        'label'      => $repeaterField->label,
                        'name'       => $name,
                        'repeater'   => $repeaterField->repeater,
                        'order'      => $i
                        ];
                        if(isset($preview)) {
                            if(isset($params['properties']['validation']['required'])) {
                                $params['properties']['validation']['required'] = null;
                            }
                        }
                @endphp
                {{--  @dump($params)  --}}


                @if ( isset($submission->data[$repeaterField->name][($i)] ) )

                    @php

                        $params['value'] = $submission->data[$repeaterField->name][($i )] ;

                    @endphp

                @else

                    @php

                        $params['value'] = '';

                    @endphp

                @endif



                @php

                        $params['contactType'] = (isset($repeaterField->properties['contactType'])) ?$repeaterField->properties['contactType'] : 'Lead';
                @endphp



                    @include('front.applications.application-layouts.'. $application->layout .'.'.$repeaterField->field_type.'.'.$repeaterField->properties['type'] , $params)





            @endforeach



            <div class="col-md-12 repeater_control">

                <button onclick="app.removeRepeatedFields(this , '{{$props['name']}}')" class="remove-box btn btn-danger float-right m-l-15 {{($i == 1) ? ' hidden' : '' }}">{{__('Remove')}}</button>



                <button onclick="app.repeatFields(this , '{{$props['name']}}' )" class="{{$props['properties']['class']}} field_repeater btn btn-success float-right">{{__($props['properties']['button']['text'])}}

                </button>

            </div>



            <div class="clearfix clear"></div>



        </div>

    </div>

    @php

        endfor;

    @endphp
<div class="repeated_boxes"></div>
</div>
