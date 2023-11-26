<div class="p-0 m-0 row">
    <div class="p-0 m-0 col-md-5">
        @if(isset($program->properties['video']))
            <div id="video">
                <iframe width="450" height="300" src="{{ $videoUrl }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            </div>
        @elseif(isset($program->properties['featured_image']) && $program->properties['featured_image'] != null && is_array($program->properties['featured_image']))
            <div id="image">
                <img width="450" height="300" src="{{Storage::disk('s3')->temporaryUrl($program->properties['featured_image']['path'], \Carbon\Carbon::now()->addMinutes(5))}}"
                    alt="{{Storage::disk('s3')->temporaryUrl($program->properties['featured_image']['name'], \Carbon\Carbon::now()->addMinutes(5))}}">
            </div>
        @endif
        <div id="start_dates" style="margin-top: 3%">
            <h4 class="start-date-heading">{{__('Start Dates')}}</h4>
            <br>
            @foreach($program->properties['start_date'] as $key => $start_date)
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 px-lg-12 px-md-12">
                    <div class="list-item list-start-date">
                        <div class="list-header date-item"
                             data-date="{{$key}}"
                             data-program-start="{{$start_date}}"
                             onclick="app.selectProgramDate('{{$program->id}}', '{{$start_date}}', '{{$program->properties['end_date'][$key]}}' , '{{$program->properties['date_schudel'][$key]}}'  , this)"
                        >
                            <div class="flex-container">
                                <h4>{{ QuotationHelpers::formatDate($start_date, 'M Y') }}
                                    - {{ QuotationHelpers::formatDate($program->properties['end_date'][$key], 'M Y') }}
                                    @if($schedule_time = isset($schedule[$program->properties['date_schudel'][$key]]) ? $schedule[$program->properties['date_schudel'][$key]] : null)
                                        <span class="schedule">({{ $schedule_time }} )</span>
                                    @else
                                        <span class="schedule">({{$program->properties['date_schudel'][$key] }})</span>
                                    @endif
                                </h4>

                                @if(isset($date->properties['full']))
                                    <span class="text-center badge badge-pill badge-success d-inline">FULL</span>
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
    <div class="m-0 col-md-7">
        <div class="mt-3">

            <button type="button" class="close ma-3" data-dismiss="modal" aria-hidden="true">×</button>

            <h2 class="mb-3 vaa-title">{{$program->title}}</h2>

            <div>
                {!! $program->details !!}
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-12 ">
        <div class="m-4">
            <a href="javascript:void(0)"
               class="float-right text-right btn btn-accent-1 waves-effect pull-right "
               onclick="app.dismissModal('apply', '{{ route('assistants.show' , [
                            'school'            => $school,
                            'assistantBuilder'  => $assistantBuilder,
                            'step'              => $step
                            ]) }}')">
                {{__('Select')}}
            </a>

            <div class="clearfix clear"></div>
        </div>
    </div>
</div>
