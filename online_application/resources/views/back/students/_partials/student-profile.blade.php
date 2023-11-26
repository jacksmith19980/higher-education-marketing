<div id="nav-profile" class="tab-pane fade show active" role="tabpanel" aria-labelledby="nav-profile">

    @php
        $attrs = $student->only(['first_name', 'last_name','email','address','city','country']);
    @endphp

    <table class="table mb-0 table-hover compressed-table" data-tablesaw-mode="swipe" data-tablesaw-sortable="" data-tablesaw-sortable-switch="" data-tablesaw-minimap="" data-tablesaw-mode-switch="">
        <tbody>

            @foreach ($attrs as $field => $value)

                    @php
                        $fieldDetails = [
                            'editable'  => true,
                            'value'     => $value ? $value :  '' ,
                        ];
                    @endphp

                    <tr>
                    <td class="title" style="width:30%">{{ __(Str::title(str_replace('_', ' ', $field))) }}</td>
                    <td>
                        <span class="{{ ($fieldDetails['editable'] ) ? 'editable editable-click' : '' }}"
                        data-placement="top"
                        data-name="student"
                        data-field="{{$field}}"
                        data-value="{{ $fieldDetails['value'] }}"
                        data-url="{{route('student.quick-edit.source' , ['student' => $student , 'source' => 'student' , 'field' => $field ])}}">
                            {{ $fieldDetails['value'] }}
                        </span>
                    </td>
                    </tr>
            @endforeach

            @foreach ($customFields as $field)

                @if( !isset($field['custom_field'])  || !in_array($field['custom_field'], ['prorgam' , 'course']))

                    @php
                        $fieldDetails = [
                            'editable'  => true,
                            'value'     => isset($student->properties['customfields'][$field['slug']]) ? $student->properties['customfields'][$field['slug']]:  '' ,
                        ];
                    @endphp

                    <tr>
                    <td class="title" style="width:30%">{{$field['name']}}</td>
                    <td>
                        <span class="{{ ( $fieldDetails['editable'] ) ? 'editable editable-click' : '' }}"
                        data-placement="top"
                        data-name="{{$field['slug']}}"
                        data-value="{{ $fieldDetails['value'] }}"
                        data-url="{{route('student.quick-edit.source' , ['student' => $student , 'source' => $field['slug']])}}">
                            {{ $fieldDetails['value'] }}
                        </span>
                    </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
