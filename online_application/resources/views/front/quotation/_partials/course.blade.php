<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.date-picker',
        [
            'name'      => 'start_date',
            'label'     => 'Start Date' ,
            'class'     => 'datepicker startDate' ,
            'required'  => true,
            'attr'      => '',
            'value'     => '',
            'data'      => '',
        ])
    </div>

    <div class="col-md-6">
          @include('back.layouts.core.forms.date-picker',
        [
            'name'      => 'end_date',
            'label'     => 'End Date' ,
            'class'     => 'datepicker endDate' ,
            'required'  => true,
            'attr'      => 'disabled',
            'value'     => '',
            'data'      => '',
        ])
    </div>
    
</div>

<div class="row">
    <div class="col-md-12">
          @include('back.layouts.core.forms.select',
        [
            'name'        => 'accommodation',
            'label'       => 'Accommodation' ,
            'class'       => 'select2' ,
            'required'    => false,
            'attr'        => '',
            'value'       => '',
            'placeholder' => 'Select Accommodation',
            'data'        => QuotationHelpers::getAccommodationOption($settings['quotation']),
        ])
    </div>
</div>


<div class="row">
    <div class="col-md-12">
          @include('back.layouts.core.forms.select',
        [
            'name'        => 'transfer',
            'label'       => 'Transfer Options' ,
            'class'       => 'select2' ,
            'required'    => false,
            'attr'        => '',
            'value'       => '',
            'placeholder' => 'Select Accommodation',
            'data'        => QuotationHelpers::getTransferOption($settings['quotation']),
        ])
    </div>
</div>

<script>
    $(document).ready(function() {
        var startDay = {{ $settings['quotation']['week_start'] }};
        var nextStartDate = moment().weekday( Number(startDay + 7) ).format("YYYY-MM-DD");
      
        $('.datepicker.startDate').datepicker({
            autoClose: true,
            startDate: new Date(),
            daysOfWeekDisabled: "{{QuotationHelpers::getDisabledDays( [$settings['quotation']['week_start']] )}}",
            daysOfWeekHighlighted: "{{ $settings['quotation']['week_start'] }}",
            orientation : "bottom",
            autoclose : true,
            format : "yyyy-mm-dd",
            weekStart : 1,
            startDate : nextStartDate,
        }).on("changeDate", function(e) {
            
            // Get the End Date
            var startDate = e.date; 
            var courseMinDays = {{QuotationHelpers::getCourseMinDays($course->properties['number_of_weeks'])}};

            var nextEndDate = moment(startDate, "YYYY-MM-DD").add( (courseMinDays - 1) , 'days').format("YYYY-MM-DD");
          
            if($('.datepicker.endDate').is(":disabled")){
                $('.datepicker.endDate').removeAttr('disabled').datepicker({
                    autoClose: true,
                    startDate: new Date(),
                    daysOfWeekDisabled: "{{QuotationHelpers::getDisabledDays( [$settings['quotation']['week_end']] )}}",
                    daysOfWeekHighlighted: "{{ $settings['quotation']['week_end'] }}",
                    orientation : "bottom",
                    autoclose : true,
                    format : "yyyy-mm-dd",
                    weekStart : 1,
                    startDate : nextEndDate,
                }).on("changeDate", function(e) {
                    $('.GetAPriceButton').removeAttr('disabled');
            });

            }else{

                $('.datepicker.endDate').datepicker('update' , nextEndDate ).datepicker('setStartDate' , nextEndDate );

            }
        });
    });
</script>