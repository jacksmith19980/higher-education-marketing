<div class="row options_group_transfer">
    <div class="col-md-3 offset-9 m-b-20">
        <button type="button" class="btn btn-success btn-block"
                onclick="app.addElements(this)" data-action="classroomSlot.createRepeater" data-container="#slots_wrapper">
            <i class="fa fa-plus"></i> Add Time Slot
        </button>
    </div>
</div>

<div class="row options_group_transfer d-flex" id="slots_wrapper">
    @if(isset($classroom))
        @foreach($days as $day)
            @php
                $keys = array_keys(array_column($classroom->classroomSlots->toArray(), 'day'), $day);
            @endphp

            @include('back.classrooms._partials.slot-repeater-edit-row', [
                    'week_days' => $day,
                    'keys' => $keys
                ])

        @endforeach
    @endif
</div>

@if(isset($keys) && count($classroom->classroomSlots) > 5)
    <div class="row options_group_transfer">
        <div class="col-md-3 offset-9 m-b-20">
            <button type="button" class="btn btn-success btn-block"
                    onclick="app.addElements(this)" data-action="classroomSlot.createRepeater" data-container="#slots_wrapper">
                <i class="fa fa-plus"></i> Add Time Slot
            </button>
        </div>
    </div>
@endif