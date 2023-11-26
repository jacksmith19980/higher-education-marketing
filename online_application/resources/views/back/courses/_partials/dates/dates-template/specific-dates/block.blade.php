
<div class="mt-3 card card-border" data-key="{{$key}}">
    <div class="card-header">
        <div class="d-flex no-block align-items-center justify-content-center card-title-wrapper">
            <h4 class="card-title d-block">{{$date->properties['start_date']}} to {{$date->properties['end_date']}}</h4>
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

    <div class="mt-3 card-text pa-3">
        <div class="mb-2 row pa-3">
            <div class="col-md-12">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td style="width:20%;"><strong class="d-block">{{__('Start Date')}}</strong></td>
                            <td>{{$date->properties['start_date']}}</td>
                        </tr>

                        <tr>
                            <td style="width:20%;"><strong class="d-block">{{__('End Date')}}</strong></td>
                            <td>{{$date->properties['end_date']}}</td>
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
        <div class="col-md-12">

            <div class="">
                <h5 class="pt-2 pb-2">{{__('Addons')}}</h5>
                <div></div>
            </div>

            @if (isset($date->properties['addons']))

            <ul class="pb-5 list-group list-group-full">

                @foreach ($date->properties['addons'] as $addonKey => $addon)
                <li class="list-group-item" data-key="{{$addonKey}}">
                    @include('back.courses._partials.dates.dates-template.specific-dates.addon' , [
                    'course' => $course,
                    'addon' => $addon,
                    'dateKey' => $key,
                    'addonKey' => $addonKey
                    ])
                </li>

                @endforeach

            </ul>
            @endif
        </div>
    </div>
</div>
