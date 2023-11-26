<div class="m-0 card">

    <div class="py-0 pt-2 pl-0 pr-1 ">

        <div class="mb-0 d-flex justify-content-between btn-toggler align-items-center" data-toggle="collapse"
        data-target="#CourseProps" aria-expanded="false" aria-controls="app_pInfo">

        <h5>{{__("Course's Custom Properties")}}</h5>
        <i class="mdi mdi-plus text-primary"></i>
        </div>

    </div>

    <div id="CourseProps" class="collapse" aria-labelledby="apph_pInfo" data-parent="#accordionExample" style="">
        <div class="p-0 card-body">

            @if (isset($customFields['courses']))

                @foreach ($customFields['courses'] as $customField)
                    @include('back.applications.new-builder.custom-field' , [
                    'customField'  => $customField
                ])
                @endforeach

            @else

                @include('back.applications.new-builder.no-custom-field' , ['object' => 'courses'])

            @endif


        </div>
    </div>
</div>
