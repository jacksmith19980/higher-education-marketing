<div class="card card-border mt-3" data-key="{{$key}}">
    <div class="card-header">
        <div class="d-flex no-block align-items-center justify-content-center card-title-wrapper">
            <h4 class="card-title d-block">{{$date->properties['date']}} - {{$date->properties['start_time']}} to {{$date->properties['end_time']}}</h4>
            <div class="ml-auto">

                <a href="javascript:void(0)" class="btn btn-light" onclick="app.editCourseProp(this)"
                   data-title="Edit Course Date" data-route="{{ route('date.edit' , [
                                        'course' => $course ,
                                        'date'   => $date
                                    ])}}">
                    <i class="ti-pencil"></i>
                </a>

                <a href="javascript:void(0)" class="btn btn-light" onclick="app.deleteCourseProp(this)"
                   data-delete-route="{{ route('date.delete' , ['course' => $course , 'date' => $date])}}">
                    <i class="ti-trash"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="card-text mt-3 pa-3">
        <div class="row mb-2 pa-3">
            <div class="col-md-12">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <td style="width:20%;"><strong class="d-block">{{__('Date')}}</strong></td>
                        <td>{{$date->properties['date']}}</td>
                    </tr>

                    <tr>
                        <td style="width:20%;"><strong class="d-block">{{__('Start Time')}}</strong></td>
                        <td>{{$date->properties['start_time']}}</td>
                    </tr>

                    <tr>
                        <td style="width:20%;"><strong class="d-block">{{__('End Time')}}</strong></td>
                        <td>{{$date->properties['end_time']}}</td>
                    </tr>

                    <tr>
                        <td style="width:20%;"><strong class="d-block">{{__('Price')}}</strong></td>
                        <td>{{$date->properties['date_price']}}{{$settings['school']['default_currency']}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <hr />
        </div>
    </div>
</div>