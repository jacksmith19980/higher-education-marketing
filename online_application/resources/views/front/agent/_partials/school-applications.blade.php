<div class="col-lg-6">
    <div class="card">
        <div class="card-body">
            <div class="d-md-flex align-items-center">
                <div>
                    <h4 class="card-title">{{$school->name}} Applications</h4>
                    <h5 class="card-subtitle">List Of Avaiable Applications</h5>
                </div>
                <div class="ml-auto d-flex no-block align-items-center">
                    <!-- <div class="dl">
                        <select class="custom-select">
                            <option value="0" selected="">Monthly</option>
                            <option value="1">Daily</option>
                            <option value="2">Weekly</option>
                            <option value="3">Yearly</option>
                        </select>
                    </div> -->
                </div>
            </div>
            <div class="table-responsive">
                <table class="table no-wrap v-middle">
                    <thead>
                        <tr class="border-0">
                            <th class="border-0">{{__('Applications')}}</th>
                            <th class="border-0">{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                            @if ($applications->count())
                                @foreach ($applications as $application)
                                <tr>
                                   
                                    <td>
                                        <div class="d-flex no-block align-items-center">
                                            <div class="">
                                                <h5 class="m-b-0 font-16 font-medium">{{$application->title}}</h5>
                                                <span>{{$application->description}}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="blue-grey-text  text-darken-4 font-medium"><a href="#">{{__('Submit Application')}}</a>
                                    </td>
                                </tr>
                            @endforeach
                            
                            @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
