<div class="col-12 static">
    <div class="card new-card card-hover">

        <div class="card-body">
            <div class="card-header">
                <div class="d-md-flex justify-content-between pl-md-4">
                    <h4 class="card-title mb-3">{{__('Total Applications')}}</h4>
                    <div class="d-md-flex justify-content-between pl-md-2">
                        <div class="button-set is-small">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-light active" onclick="timeClicked('day')">
                                    <input type="radio" name="options" id="day" checked> {{__('Day')}}
                                </label>
                                <label class="btn btn-light" onclick="timeClicked('month')">
                                    <input type="radio" name="options" id="Month"> {{__('Month')}}
                                </label>
                                <label class="btn btn-light" onclick="timeClicked('year')">
                                    <input type="radio" name="options" id="Year"> {{__('Year')}}
                                </label>
                            </div>

                            {{-- <button type="button" class="btn btn-primary ml-4">
                                <i--}} {{-- class="icon-calender"></i>
                            </button>--}}
                            {{-- <button type="button" class="btn btn-primary ml-3">
                                <i--}} {{-- class=" icon-cloud-download"></i>
                            </button>--}}
                        </div>
                        &nbsp;
                        <div id="calContainer">
                            <div class="input-group mr-3">
                                <input id="calendarRanges" type="text"
                                    class="form-control calendarRanges shawCalRanges">
                                <div class="input-group-append">
                                    <span class="input-group-text btn-primary">
                                        <span class="icon-calender"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="card-content">
                <div id="spline-chart"></div>
            </div>

        </div>
    </div>
</div>
