
<h4 class="mb-3">{{__('Create Field')}} ({{ucwords(__($field_type))}})</h4>

@section('defaults')
        <input type="hidden" class="ajax-form-field" name="target" value="{{$target}}" />
        <input type="hidden" class="ajax-form-field" name="position" value="{{$position}}" />
@endsection

@switch($field_type)
        @case('program')
                @include('back.applications.new-builder.fields.program.form' , ['action' => 'create'])
        @break

        @case('course')
                @include('back.applications.new-builder.fields.course.form' , ['action' => 'create'])
        @break

        @case('loop')
                @include('back.applications.new-builder.fields.repeater.form' , ['action' => 'create' , 'target' => $target])
        @break

        @case('html')
                @include('back.applications.new-builder.fields.html.form' , ['action' => 'create'])
        @break

        @case('filesList')
                @include('back.applications.new-builder.fields.files-list.form' , ['action' => 'create'])
        @break

        @case('signature')
                @include('back.applications.new-builder.fields.signature.form' , ['action' => 'create'])
        @break

        @default
                @include('back.applications.new-builder.fields.general.form' , ['action' => 'create'])

@endswitch
