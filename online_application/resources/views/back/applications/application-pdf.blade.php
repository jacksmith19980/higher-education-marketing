@extends('back.layouts.pdf')

@section('page-title')
| {{ $application->title }}
@endsection


@section('content')

    <div class="half left">
        <div class="logo" style="width: 30%;">
            {!! SchoolHelper::renderSchoolLogo( optional( request()->tenant()) , $settings ) !!}
        </div>
    </div>

    <div class="half right" style="text-align:right">
        <strong>{{__('Application Form')}}</strong>
    </div>

    <div class="clear"></div>

    @php
        $uploadedImgs = [];
    @endphp

    @foreach ($application->sections_order as $sectionId)

        @php
            $section = $application->sections->filter(function($item) use ($sectionId) {
                return $item->id == $sectionId;
            })->first();
        @endphp

        <div class="card">
            <div class="card-header">
                <h4 class="text-white">{{$section->title}}</h4>
            </div>
            <div class="card-body">
                @if(is_array($section->fields_order))
                    @foreach($section->fields_order as $fieldId)

                        @php
                            $field = $section->fields->filter(function($item) use ($fieldId) {
                                return $item->id == $fieldId;
                            })->first();
                        @endphp

                        @if (isset($field) && in_array($field->field_type, ['field' , 'file'] ) )
                        <p>
                            <strong>- {{SubmissionHelpers::extractFieldLabel($field)}} </strong>
                        </p>
                        @endif

                    @endforeach
                @endif
            </div>
        </div>

    @endforeach

@endsection
