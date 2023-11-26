<div class="tab-pane fade show" id="dates" role="tabpanel" aria-labelledby="pills-dates-tab">
    <div class="card-body">

        @if ($course->dates()->count())

        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-success mt-2 right pull-right float-right" data-template="false" data-route="{{route('date.create' , $course)}}"
                data-title="Add Course's Date" onclick="app.addCourseDate(this)">
                Add Date
                </button>
                <div class="clear"></div>
            </div>
            <div class="col-md-12 dates-blocks">
                    @foreach ($course->dates()->get() as $date)
                          @include('back.courses._partials.dates.dates-template.'.$course->properties['dates_type'].'.block' , ['date' => $date , 'key' => $date->key])
                    @endforeach
            </div>
        </div>

        @else
            <div class="col-md-12 dates-blocks">
            </div>
            <div class="alert alert-warning">
                <strong>{{__('No Dates Found')}}</strong>
                <span class="d-block">{{__("there are none! You didn't add any dates for this course yet!")}}</span>
                    <button class="btn btn-success mt-2 right pull-right float-right"
                        data-template="false"
                        data-route="{{route('date.create' , $course)}}"
                        data-title="Add Course's Date"
                        onclick="app.addCourseDate(this)"
                    >{{__('Add Date')}}</button>
                    <div class="clear"></div>
            </div>
        @endif

    </div>


</div>
