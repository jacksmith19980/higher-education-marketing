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

    <input type="hidden" class="repeater_count" name="{{$props['name']}}" value="{{$numberofRepeats}}" />

    <input type="hidden" class="repeater_max" name="repeater_max_{{$props['name']}}" value="{{$max}}" />

    @php
        for($i = 1 ; $i <= $numberofRepeats ; $i++ ):
    @endphp

    

    <div class="col-md-12 repeater_box box_{{$props['name']}}">

        <div class="row">

            @foreach ($fields as $field)
                @php
                    $name = $field->name.'[';
                    $name = isset($i) ? $name.$i.']' : $name . ']';  

                    $params = [
                        'properties' => $field->properties,
                        'data'       => $field->data,
                        'label'      => $field->label,
                        'name'       => $name,
                        'repeater'   => $field->repeater,
                        'order'      => $i   
                        ];
                        if(isset($preview)) {
                            if(isset($params['properties']['validation']['required'])) {
                                $params['properties']['validation']['required'] = null;
                            }
                        }
                @endphp        

                

                @if ( isset($submission->data[$field->name][($i)] ) )

                    @php

                        $params['value'] = $submission->data[$field->name][($i)] ;

                    @endphp

                @else 

                    @php

                        $params['value'] = '';

                    @endphp   

                @endif



                @php

                        $params['contactType'] = (isset($field->properties['contactType'])) ?$field->properties['contactType'] : 'Lead';
                @endphp

                @include('front.applications.application-layouts.'. $application->layout .'.'.$field->field_type.'.'.$field->properties['type'] , $params)

                

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