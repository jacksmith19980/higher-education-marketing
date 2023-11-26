@php
if(!isset($params['value']['date'])){
    $params['value']['date'] = [];
}
@endphp
@if(array_key_exists('campus', $params['value']))
    @foreach($params['value']['campus'] as $key => $campus_id)
        <div id="{{$key}}" style="display: flex; flex-wrap: wrap;" class="col-md-12">
            <div class="col-md-12">
                <button type="button" class="close float-right" aria-label="Close" id="close-{{$key}}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="w-100"></div>

            <div class="col-md-6">
                @include('back.layouts.core.forms.select', [
                    'name'      => $field->name . '[campus][' . $key . ']',
                    'label'       => 'Campuses',
                    'class'       => 'ajax-form-field' ,
                    'required'    => true,
                    'attr'        => "onchange=app.searchCourses(this,'".route('courses.campus', $school) ."') data-field=".$field->id . " data-fieldName=".$field->name,
                    'value'       => $campus_id,
                    'placeholder' => "Select a Campus",
                    'data'        => $campuses
                ])
            </div>

            @php
                $courses = ApplicationHelpers::getCoursesList($campus_id, $field->id);
                $course_slug = $params['value']['courses'][$key];

            @endphp

            <div class="col-md-6">
                @include('back.layouts.core.forms.select', [
                    'name'      => $field->name . '[courses][' . $key . ']',
                    'label'     => 'Courses',
                    'class'     => 'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => "onchange=app.searchDatesAddons(this,'".route('courses.startDatesAndAddons', $school) ."') data-route=".route('date.addons', $school) . " data-field=".$field->id . " data-fieldName=".$field->name,
                    'value'     => $course_slug,
                    'data'      => $courses
                ])
            </div>

            <div class="col-md-12">
                <div class="start-date-{{$key}} row form-group">
                    @php
                        $course = \App\Repository\CourseRepository::bySlug($course_slug);
                        $datesData = ApplicationHelpers::dateDataHandler($course, $field);
                        //if(array_key_exists('course_slug', $params['value']['courses'])){
                    @endphp
                    @include('front.applications.application-layouts.oiart.courses.partials.dates', [
                                'datesData' => $datesData,
                                'course' => $course,
                                'field' => $field,
                                'hash' => $key,
                                'selected' => isset($params['value']['date'][$course_slug]) ? $params['value']['date'][$course_slug] : ''
                            ])
                    @php //} @endphp
                </div>
            </div>
            @if(array_key_exists('course_addons', $params['value']) && array_key_exists($course_slug, $params['value']['course_addons']) )
                <div class="col-md-12">
                    <div class="course-addons-{{$key}} row form-group">
                        @include('front.applications.application-layouts.oiart.courses.partials.addons', [
                                    'addonsData' => $course->addons()->get(),
                                    'course' => $course,
                                    'field' => $field,
                                    'selected' => $params['value']['course_addons'][$course_slug],
                                ])
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <div class="course-addons-{{$key}} row form-group">
                        @include('front.applications.application-layouts.oiart.courses.partials.addons', [
                                    'addonsData' => $course->addons()->get(),
                                    'course' => $course,
                                    'field' => $field,
                                ])
                    </div>
                </div>
            @endif

            @php
                if(isset($params['value']['date'][$course_slug])){
                    $date = \App\Tenant\Models\Date::findOrFail($params['value']['date'][$course_slug]);
            @endphp

            @if(array_key_exists('date_addons', $params['value']) && array_key_exists('addons', $date->properties) && !empty($date->properties['addons']) )
                <div class="col-md-12">

                    <div class="date-addons-{{$key}} row form-group">
                         @include('front.applications.application-layouts.oiart.courses.partials.dates-addons', [
                                'addons' => isset($date->properties['addons']) ? $date->properties['addons'] : '',
                                'course' => $course,
                                'date_id' => $date->id,
                                'field' => $field,
                                 'selected' => $params['value']['date_addons'][$course_slug],
                        ])

                    </div>
                </div>
            @else
                <div class="col-md-12">

                    <div class="date-addons-{{$key}} row form-group">
                         @include('front.applications.application-layouts.oiart.courses.partials.dates-addons', [
                                'addons' => isset($date->properties['addons']) ? $date->properties['addons'] : '',
                                'course' => $course,
                                'date_id' => $date->id,
                                'field' => $field,
                        ])
                    </div>
                </div>

            @endif
            @php } @endphp
        </div>
    @endforeach
@else
    @foreach($params['value']['courses'] as $key => $course_slug)
        <div id="{{$key}}" style="display: flex; flex-wrap: wrap;" class="col-md-12">
            <div class="col-md-12">
                <button type="button" class="close float-right" aria-label="Close" id="close-{{$key}}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="w-100"></div>

            @php
                $courses = ApplicationHelpers::getCoursesList(null, $field->id);
            @endphp

            <div class="col-md-6">
                @include('back.layouts.core.forms.select', [
                    'name'      => $field->name . '[courses][' . $key . ']',
                    'label'     => 'Courses',
                    'class'     => 'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => "onchange=app.searchDatesAddons(this,'".route('courses.startDatesAndAddons', $school) ."') data-route=".route('date.addons', $school) . " data-field=".$field->id . " data-fieldName=".$field->name,
                    'value'     => $course_slug,
                    'data'      => $courses
                ])
            </div>

            <div class="col-md-12">
                <div class="start-date-{{$key}} row form-group">
                    @php
                        $course = \App\Repository\CourseRepository::bySlug($course_slug);
                        $datesData = ApplicationHelpers::dateDataHandler($course, $field);
                    @endphp
                    @include('front.applications.application-layouts.oiart.courses.partials.dates', [
                                'datesData' => $datesData,
                                'course' => $course,
                                'field' => $field,
                                'hash' => $key,
                                'selected' => isset($params['value']['date']) ? (array_key_exists($course_slug, $params['value']['date']) ? $params['value']['date'][$course_slug] : '') : ''
                            ])
                </div>
            </div>
            @if(array_key_exists('course_addons', $params['value']) && array_key_exists($course_slug, $params['value']['course_addons']) )
                <div class="col-md-12">
                    <div class="course-addons-{{$key}} row form-group">
                        @include('front.applications.application-layouts.oiart.courses.partials.addons', [
                                    'addonsData' => $course->addons()->get(),
                                    'course' => $course,
                                    'field' => $field,
                                    'hash' => $key,
                                    'selected' => $params['value']['course_addons'][$course_slug],
                                ])
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <div class="course-addons-{{$key}} row form-group">
                        @include('front.applications.application-layouts.oiart.courses.partials.addons', [
                                    'addonsData' => $course->addons()->get(),
                                    'course' => $course,
                                    'field' => $field,
                                    'hash' => $key,
                                ])
                    </div>
                </div>
            @endif

            @php
                if (array_key_exists($course_slug, $params['value']['date'])){
                    $date = \App\Tenant\Models\Date::findOrFail($params['value']['date'][$course_slug]);
                } else {
                    $date = [];
                }
            @endphp

            @if($date && array_key_exists('addons', $date->properties) && !empty($date->properties['addons']) )
                <div class="col-md-12">

                    <div class="date-addons-{{$key}} row form-group">
                        @include('front.applications.application-layouts.oiart.courses.partials.dates-addons', [
                                    'addons' => $date->properties['addons'],
                                    'course' => $course,
                                    'field' => $field,
                                    'date_id' => $date->id,
                                    'hash' => $key,
                                    'selected' => $params['value']['date_addons'][$course_slug],
                                ])
                    </div>
                </div>
            @endif
        </div>
    @endforeach
@endif
<script>
    $(document).ready(function () {
        @if(array_key_exists('campus', $params['value']))
        @foreach($params['value']['campus'] as $key => $campus_id)
        $(document).on('click', '#close-{{$key}}', function () {
            console.log('close');
            $('#{{$key}}').remove();
        });
        @endforeach
        @else
        @foreach($params['value']['courses'] as $key => $course_id)
        $(document).on('click', '#close-{{$key}}', function () {
            console.log('close');
            $('#{{$key}}').remove();
        });
        @endforeach
        @endif
    });
</script>
