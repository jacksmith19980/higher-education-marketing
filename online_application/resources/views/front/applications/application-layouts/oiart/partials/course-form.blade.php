@php
    $courses = [];
    $campuses = \App\Tenant\Models\Campus::all()->pluck('title', 'id')->toArray();
    $hash = md5(time());
@endphp
<div id="{{$hash}}" style="display: flex; flex-wrap: wrap;" class="col-md-12">

    <div class="col-md-12">
        <button type="button" class="close float-right" aria-label="Close" id="close-{{$hash}}">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="w-100"></div>

@if($field->properties['showCampus'] && count($campuses) > 1)

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'campus[' . $hash . ']',
            'label'     => 'Campuses',
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => "data-field=".$field->id,
            'value'     => '',
            'placeholder' => "Select a Campus",
            'data' => $campuses
        ])
    </div>
@else
    @php
        if(empty($field->data)) {
            $courses = \App\Tenant\Models\Course::all()->pluck('title', 'slug')->toArray();
        }
        else {
            $courses = $field->data;
        }
    @endphp
@endif

<div class="col-md-6">
    @include('back.layouts.core.forms.select',
    [
        'name'      => 'courses[' . $hash . ']',
        'label'     => 'Courses',
        'class'     => 'ajax-form-field',
        'required'  => true,
        'attr'      => '',
        'value'     => '',
        'data'      => $courses
    ])
</div>

<div class="col-md-12">
    <div class="start-date-{{$hash}} row form-group">
    </div>
</div>

<div class="col-md-12">
    <div class="course-addons-{{$hash}} row form-group">
    </div>
</div>

<div class="col-md-12">
    <div class="date-addons-{{$hash}} row form-group">
    </div>
</div>
</div>

<script>
    $(document).ready(function () {
        $(".select2").select2();

        $('select[name="campus[{{$hash}}]"]').on('select2:select', function (e) {
            cleanDivsContainers();
            $('select[name="courses[{{$hash}}]"] option[value]').remove();
            $('select[name="courses[{{$hash}}]"]').select2({
                ajax: {
                    url: `{{route('courses.campus', $school)}}`,
                    type: "get",
                    dataType: 'json',
                    data: {
                        campus: $('select[name="campus[{{$hash}}]"]').val(),
                        element: {{$field->id}}
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
        });

        $('select[name="courses[{{$hash}}]"]').on('select2:select', function (e) {
            cleanDivsContainers();
            getStartDatesAndAddonsFromCourse(e);
        });

        function getStartDatesAndAddonsFromCourse(e) {
            $.ajax({
                url: `{{route('courses.startDatesAndAddons', $school)}}`,
                type: "get",
                dataType: 'json',
                data: {
                    course: e.params.data.id
                },
                statusCode: {
                    404: function() {
                        console.log('page not found')
                    }
                }
            }).done(function(data) {
                getStratDates(data.dates, e.params.data.id);
                getCourseAddons(data.addons, e.params.data.id);
            });
        }

        function getStratDates(start_dates, course) {
            if(Object.keys(start_dates).length > 0) {
                var radio_title = '<div class="col-md-12">Dates</div>';
                var radio = '';
                $.each(start_dates, function (key, value) {
                    radio += startDateHtml(key, value, course);
                });
                $('.start-date-{{$hash}}').append(radio_title + radio);
            }
        }

        function startDateHtml(key, value, course){
            return '<div class="col-md-6"><div class="custom-control custom-radio" style="margin-top: 7px;">' +
                '<input class="custom-control-input" data-hash="{{$hash}}" data-course="' + course + '" onclick="app.dateSelected(this)"  value="' + value.id + '" name="date[' + course + '][]" id="' + value.id + '" type="radio">' +
                '<label class="custom-control-label" for="' + value.id + '">' + dateToShow(value) + '</label></div></div>';
        }

        function dateToShow(value){
            switch (value.date_type) {
                case 'specific-dates':
                    return '' + dateFormat(value.properties.start_date) + ' - ' + dateFormat(value.properties.end_date);
                    break;
                case 'single-day':
                    return dateFormat(value.properties.date + ' ' + value.properties.start_time, 'dddd, MMMM Do YYYY HH:mm A') + ' - ' + dateFormat(value.properties.date + ' ' + value.properties.end_time, 'HH:mm A');
                    break;
                default:
                    return dateFormat(value.properties.start_date);
                    break;
            }
        }

        function dateFormat(date, format = 'dddd, MMMM Do YYYY'){
            return moment(date).format(format);
        }

        function getCourseAddons(addons, course) {
            if(Object.keys(addons).length > 0) {
                var checkbox_title = '<div class="col-md-12">Course Addons</div>';
                var checkbox = '';
                $.each(addons, function (key, value) {
                    checkbox += addonsHtml(key, value, course);
                });
                $('.course-addons-{{$hash}}').append(checkbox_title + checkbox);
            }
        }

        function addonsHtml(key, value, course){
            return'<div class="col-md-6"><div class="custom-control  custom-checkbox" style="margin-top: 7px;">' +
                '<input class="custom-control-input" value="' + value.id + '" name="course_addons[' + course + '][]" id="' + value.id + '" type="checkbox">' +
                '<label class="custom-control-label" for="' + value.id + '">' + value.title + '</label></div></div>';
        }

        $(document).on('click', 'input[name="date-{{$hash}}"]', function() {
            $('.date-addons-{{$hash}}').empty();
            getDatesAddons(this.value);
        });

        function getDatesAddons(value) {
            $.ajax({
                url: `{{route('date.addons', $school)}}`,
                type: "get",
                dataType: 'json',
                data: {
                    date: value
                },
                statusCode: {
                    404: function() {
                        console.log('page not found')
                    }
                }
            }).done(function(data) {
                showDateAddons(data);
            });
        }

        function showDateAddons(addons) {
            console.log(addons);
            if(Object.keys(addons).length > 0) {
                var showDate_checkbox_title = '<div class="col-md-12">Date Addons</div>';
                var showDate_checkbox = '';
                $.each(addons, function (key, value) {
                    showDate_checkbox += dateAddonsHtml(key, value);
                });

                $('.date-addons-{{$hash}}').append(showDate_checkbox_title + showDate_checkbox);
            }
        }

        function dateAddonsHtml(key, value){
            return '<div class="col-md-6"><div class="custom-control  custom-checkbox" style="margin-top: 7px;">' +
                '<input class="custom-control-input" id="' + key + '" value="' + value.price + '" type="checkbox">' +
                '<label class="custom-control-label" for="' + key + '">' + value.title + '</label></div></div>';
        }

        function cleanDivsContainers() {
            $('.start-date-{{$hash}}').empty();
            $('.course-addons-{{$hash}}').empty();
            $('.date-addons-{{$hash}}').empty();
        }

        $(document).on('click', '#close-{{$hash}}', function(){
            console.log('{{$hash}}');
            $('#{{$hash}}').remove();
        });
    });
</script>