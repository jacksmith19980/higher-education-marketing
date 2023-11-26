@foreach ($submission->application->sections as $section)
    
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="2" class="bg-light">
                    <h5 class="title d-block" style="margin-bottom: 0;">
                        <span class="ti-angle-double-right" style="font-size:50%"></span>
                        {{ ($section->properties['label'])? $section->properties['label'] : $section->title }}</h5>
                </th>
            </tr>
        </thead>
        <tbody>


            @foreach ($section->fields as $field)
                @if(!in_array($field->field_type , $excludes))  
                <tr>
                    <td style="width:30%;padding:0.5rem"><strong>{{$field->label}}</strong></td>
                    <td style="padding:0.5rem 1rem;width:67%">
                        @include('back.students._partials.student-application-field' , 
                            [
                                'value' => isset($submission->data[$field->name]) ? $submission->data[$field->name] : null,
                                'type' => $field->field_type,
                                'field' => $field,
                                'submission' => $submission
                            ])
                    </td>

                   
                </tr>
                @endif
            @endforeach


        </tbody>
    </table>
</div>



@endforeach