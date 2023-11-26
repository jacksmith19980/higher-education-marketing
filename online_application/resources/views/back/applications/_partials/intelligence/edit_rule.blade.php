@if (!empty($logic))
<div class="fields-wrapper list-group">
    <div class="list-group-item list-group-item-action flex-column align-items-start field-row">
        <div class="d-flex w-100 justify-content-between p-l-10 p-r-10">


            @foreach ( $logic as $key=>$value)

            <input type="hidden" class="ajax-form-field" name="properties[logic_{{$key}}]" @if (is_array($value))
                value="{{ implode(" ,", $value)}}" @else value="{{ $value}}" @endif>
            @endforeach




            <h6 class="mb-1 text-gray">{{ucwords($logic['action'])}} if "{{$logic['field']}}"
                {{ucwords($logic['operator'])}}

                <span class="text-info">

                    (

                    @if (is_array($logic['value']))

                    {{ implode(", " , $logic['value']) }}

                    @else

                    {{ $logic['value'] }}

                    @endif

                    )

                </span>

            </h6>



            <span class="action-icons">



                <a href="javascript:void(0)" class="action-button" onclick="app.resetSmartFieldLogic(this)"
                    data-placement="left" data-toggle="tooltip" title="" data-original-title="Delete">

                    <i class="ti-trash text-danger app-action-icons"></i>

                </a>



            </span>

        </div>
    </div>
</div>
@endif
