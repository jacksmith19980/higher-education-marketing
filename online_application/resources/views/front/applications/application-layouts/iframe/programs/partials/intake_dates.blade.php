<div class="col-md-12">Dates</div>
@foreach($datesData as $date)
    <div class="col-md-6">
        <div class="custom-control custom-radio" style="margin-top: 7px;">

            <input class="custom-control-input" data-field="{{$field->id}}"
                   data-program="{{$program->slug}}"
                   data-registration-fees="{{(isset($program->properties['program_registeration_fee'])) ? $program->properties['program_registeration_fee'] : 0 }}"
                   value="{{$date}}" name="{{$field->name}}[date]" id="{{$date}}" type="radio"
                   data-syncroute="{{route('cart.programDate', $school)}}"
                   onchange="app.syncProgramDateOnCart(this)"
            @if(isset($selected))
                {{($selected == $date)? ' checked' : ' '}}
                    @endif
            >

            <label class="custom-control-label" for="{{$date}}">
                {{iconv('latin5', 'utf-8', \App\Helpers\Date\DateHelpers::translateDate($date))}}
            </label>
        </div>
    </div>
@endforeach