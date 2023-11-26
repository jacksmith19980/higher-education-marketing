<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<div class="row">
    <div class="col-md-5 m-0 p-0">
        <div id="video">
            @if($course->properties[])
                {!! $course->properties['video'] !!}
            @endif
        </div>
        <div id="start_dates" style="margin-top: 3%">
            <h4 style="color: #296BA1; margin-left: 3%;">Start Dates</h4>
            <br>
            @foreach($course->dates()->get() as $date)

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 px-lg-12 px-md-12">
                    <div class="list-item">
                        <div class="list-header date-item"
                             data-start="{{$date->properties['start_date']}}"
                             data-date="{{$date->key}}"
                             onclick="app.selectDate('{{$date->key}}' , '{{$course->id}}' , '{{$date->properties['date_price']}}' , '{{$date->properties['start_date']}}', '{{$date->properties['end_date']}}')"
                        >
                            <div class="flex-container">
                                <h4>{{ QuotationHelpers::formatDate($date->properties['start_date'], 'M Y') }}
                                    - {{ QuotationHelpers::formatDate($date->properties['end_date'], 'M Y') }}</h4>

                                @if(isset($date->properties['full']))
                                    <span class="badge badge-pill badge-success text-center d-inline">FULL</span>
                                @else
                                    <label class="checkbox-container">
                                        <span class="checkmark"></span>
                                    </label>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


            @endforeach
        </div>
    </div>
    <div class="col-md-7 m-0">
        <div class="mt-3">
            <h2>{{$course->title}}</h2>
            <br>

            <div>
                {!! $course->details !!}
            </div>
        </div>
        <div style="position:absolute; bottom:5px; right: 10px;">
            <button type="button" class="btn btn-danger waves-effect text-left"
                    data-dismiss="modal">Apply now
            </button>
        </div>
    </div>
</div>