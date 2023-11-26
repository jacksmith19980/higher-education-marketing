<div class="row m-0 p-0">
    @if(isset($campus->properties['video']) || isset($campus->properties['campus_location']) || isset($campus->properties['featured_image']))
        <div class="col-md-5 m-0 p-0">
            @if(isset($campus->properties['video']) && $campus->properties['video'] != null)
                <div id="video">
                    <iframe width="450" height="300" src="{{$campus->properties['video']}}"
                            frameborder="0"
                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            @elseif(isset($campus->properties['featured_image']) && $campus->properties['featured_image'] != null && is_array($campus->properties['featured_image']))
                <div id="image">
                    <img width="450" height="300" src="{{Storage::disk('s3')->temporaryUrl($campus->properties['featured_image']['path'], \Carbon\Carbon::now()->addMinutes(5))}}"
                         alt="{{Storage::disk('s3')->temporaryUrl($campus->properties['featured_image']['name'], \Carbon\Carbon::now()->addMinutes(5))}}">
                </div>
            @endif
            @if($campus->properties['campus_location'])
                <div id="map">
                    <iframe width="100%" height="300px" id="gmap_canvas" src="https://maps.google.com/maps?q=
            {!! urlencode($campus->properties['campus_location']) !!}&t=&z=13&ie=UTF8&iwloc=&output=embed"
                            frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                </div>
            @endif
        </div>
    <div class="col-md-7 m-0">
    @else
    <div class="col-md-12 m-0">
        @endif
        <div class="mt-3">
            <button type="button" class="close ma-3" data-dismiss="modal" aria-hidden="true">×</button>
            <h2 class="vaa-title mb-3">{{$campus->title}} Campus</h2>
            <div>
                {!! isset($campus->properties['short_description']) ? $campus->properties['short_description'] : '' !!}
            </div>

            <hr>

            <div>
                {!! $campus->details !!}
            </div>
        </div>
        <div class="row">
            <div class="col-12 ">
                <div class="m-4">
                    <a href="javascript:void(0)"
                       class="btn btn-accent-1 waves-effect text-right float-right pull-right "
                       onclick="app.dismissModal('campus', '{{ route('assistants.show' , [
                        'school'            => $school,
                        'assistantBuilder'  => $assistantBuilder,
                        'step'              => $step
                        ]) }}',
                       {{$campus->id}}
                               )"
                    >{{__('Select')}}
                    </a>

                    <div class="clear clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
