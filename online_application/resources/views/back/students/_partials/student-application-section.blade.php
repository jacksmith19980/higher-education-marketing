
<div class="card m-0">
    <div class="card-header py-0 pl-0 pr-1 pt-2" id="apph_pInfo-{{$section->id}}-{{$submission->application->id}}">
       <div class="d-flex justify-content-between mb-0 btn-toggler align-items-center "
             data-toggle="collapse" data-target="#app_pInfo-{{$section->id}}-{{$submission->application->id}}" aria-expanded="true"
             aria-controls="app_pInfo">
             <h4>{{ ($section->properties['label'])? $section->properties['label'] : $section->title }}</h4>
             <i class="mdi mdi-plus text-primary"></i>
       </div>
    </div>

    <div id="app_pInfo-{{$section->id}}-{{$submission->application->id}}" class="collapse" aria-labelledby="apph_pInfo"
       data-parent="#accordionExample">
       <div class="card-body p-0">
            <table class="mb-0 table-hover table compressed-table" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>
                <tbody>
                    @foreach ($section->fields as $field)
                        <tr>
                            <td class="title">{{$field->label}}</td>
                            <td>

                                @isset($submission->data[$field->name])
                                    @include('back.students._partials.student-application-field' , ['value' => $submission->data[$field->name], 'type' => $field->field_type])
                                @endisset
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
