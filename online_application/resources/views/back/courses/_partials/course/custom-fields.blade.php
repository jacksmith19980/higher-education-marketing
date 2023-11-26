
 @if (isset($course->properties['customfields']))
<table id="course_custum_fields" class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
    <thead>
        <tr>
            <th>{{__('Title')}}</th>
            <th>{{__('Slug')}}</th>
            <th>{{__('Type')}}</th>
            <th>{{__('Value')}}</th>
            <th></th>
        </tr>
    </thead>

    <tbody>

            @foreach ($course->properties['customfields'] as $field => $value)
                    @php
                        $cfield = App\Tenant\Models\CustomField::where('slug', $field)->first();
                    @endphp
                <tr>
                    <td>
                        <span style="font-size: 12px">
                            {{$cfield->name}}
                        </span>
                    </td>
                    <td>
                        <span style="font-size: 12px">
                            {{$cfield->slug}}
                        </span>
                    </td>
                    <td>
                        <span style="font-size: 12px">
                            {{$cfield->field_type}}
                        </span>
                    </td>
                    <td>
                        <span style="font-size: 12px">
                            {{implode(", ", $cfield->data['values'])}}
                        </span>
                    </td>
                    <td>
                        @php
                        $buttons = [
                            ["text"=>"Edit", "icon"=>"icon-pencil", "class"=>"", "url"=>"javascript:editCourseCustomFields($cfield->id)"]
                        ]
                        @endphp
                        <div class="btn-group more-optn-group float-right">
                            <button type="button"
                            class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt flat-btn"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                            <div class="dropdown-menu">
                                @foreach ($buttons as $button)
                                        <a class="dropdown-item {{$button['class']}}" href="{{$button['url']}}">
                                            <i class="{{$button['icon']}}"></i> <span  class="icon-text">{!!__($button['text'])!!}</span>
                                        </a>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach

    </tbody>
</table>
 @else
    @include('back.students._partials.student-no-results')
@endif
<script>
    $('#course_custum_fields').DataTable({
        "searching": false,
        "lengthChange": false,
        "columnDefs": [
            {
                "targets": 4,
                "orderable": false
            }
        ]
    });
</script>
