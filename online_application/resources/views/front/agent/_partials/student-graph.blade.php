<div class="card bg-white">
    <div class="card-body px-0 py-0">
        <div class="table-responsive">
            <div class="row" id="datatableNewFilter">
                <div class="col-md-6 col-sm-4 col-xs-12" id="lenContainer">
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12" id="calContainer">
                     <div class="input-group mr-3">
{{--                        <input id="calendarRanges" type="text" class="form-control calendarRanges">--}}
{{--                        <div class="input-group-append">--}}
{{--                            <span class="input-group-text">--}}
{{--                                <span class="ti-calendar"></span>--}}
{{--                            </span>--}}
{{--                        </div>--}}
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12" id="filterContainer">
                </div>
            </div>
            <!-- <div id="ndt_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer"> -->

                <table id="ndt" class="table table-bordered new-table nowrap display">
                    <thead>
                        <tr>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Application')}}</th>
                            <th>{{__('Student Stage')}}</th>
                            <th>{{__('Updated')}}</th>
                            <th>{{__('Created')}}</th>
                            <th class="control-column"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('front.agent._partials.students' , $students)
                    </tbody>
                </table>
            <!-- </div> -->
        </div>

        <!-- <div class="table-responsive">
            <div id="ndt_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                <div class="row" id="datatableNewFilter">
                    <div class="col-md-6 col-sm-4 col-xs-12" id="lenContainer">
                    </div>
                    <div class="col-md-3 col-sm-4 col-xs-12" id="calContainer">
                         <div class="input-group mr-3">
                            <input id="calendarRanges" type="text" class="form-control calendarRanges">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <span class="ti-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4 col-xs-12" id="filterContainer">
                    </div>
                </div> -->
               <!-- <div id="reportrange" class="mr-md-3"
                  style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">

                  <span></span>
                  <button type="button" class="btn btn-primary"><i class="ti-calendar"></i></button>
               </div> -->

           <!-- <table id="ndt" class="table display new-table nowrap dataTable no-footer dtr-inline">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Application</th>
                        <th>Payment Status</th>
                        <th>Student Stage</th>
                        <th>Updated</th>
                        <th>Created</th>
                        <th class="control-column"></th>
                    </tr>
                </thead>
                <tbody>
                  {{--  @include('front.agent._partials.students' , $students) --}}
                </tbody>
            </table> -->
        </div>
    </div>
   <!--  </div>
</div> -->



