<table id="course_programs" class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
    <thead>
        <tr>
            <th>{{__('Program')}}</th>
            <th>{{__('Code')}}</th>
            <th>{{__('Campus')}}</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        @if ($course)
            @foreach ($course->programs as $program)
                <tr data-program-id="{{$program->id}}">

                    <td>
                        <span style="font-size: 14px">{{$program->title}}</span>
                    </td>
                    <td>
                        <span style="font-size: 12px">
                            {{$program->slug}}
                        </span>
                    </td>
                    <td>
                        @foreach ($program->campuses as $campus)
                            <span style="font-size: 12px" class="badge badge-primary">
                                {{$campus->title}}
                            </span>
                        @endforeach
                    </td>
                    <td>

                        @php
                        $buttons = [
                            [
                                "text"   =>"Edit",
                                "icon"   =>"icon-pencil",
                                "class"  =>"",
                                'url'    =>"javascript:void(0)",
                                "attr"   =>"onclick=editCourseProgram($program->id)"
                            ]
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
        @endif
    </tbody>
</table>

<script>
    $('#course_programs').DataTable({
        "searching": false,
        "lengthChange": false,
        "columnDefs": [
            {
                "targets": 3,
                "orderable": false
            }
        ]
    });
</script>
