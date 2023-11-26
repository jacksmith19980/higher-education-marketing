<h4 class="mb-3">{{__('Edit Field')}} ({{ucwords(__($field->label))}})</h4>
@switch($field_type)
        @case('program')
                @include('back.applications.new-builder.fields.program.form' , ['action' => 'edit'])
        @break

        @case('course')
                @include('back.applications.new-builder.fields.course.form' , ['action' => 'edit'])
        @break

        @case('loop')
                @include('back.applications.new-builder.fields.repeater.form' , ['action' => 'edit'])
        @break

        @case('html')
                @include('back.applications.new-builder.fields.html.form' , ['action' => 'edit'])
        @break

        @case('filesList')
                @include('back.applications.new-builder.fields.files-list.form' , ['action' => 'edit'])
        @break

        @case('signature')
                @include('back.applications.new-builder.fields.signature.form' , ['action' => 'edit'])
        @break

        @default
                @include('back.applications.new-builder.fields.general.form' , ['action' => 'edit'])

@endswitch
