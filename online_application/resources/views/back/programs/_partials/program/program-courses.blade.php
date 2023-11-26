
<table id="index_table" class="table new-table nowrap display table-bordered">
    <thead>
        <tr>
            <th>{{__('Courses')}}</th>
            <th>{{__('Program')}}</th>
            <th>{{__('Code')}}</th>
            <th>{{__('Campuses')}}</th>
            <th>ID</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        @if ($program->courses)
            @foreach ($program->courses as $course)
                <tr data-course-id="{{$course->id}}">
                    <td>{{$course->title}}</td>

                    <td>{{ implode(" , " , Arr::pluck($course->programs->toArray(), 'title')) }}</td>

                    <td>{{$course->slug}}</td>

                    <td>
                        @foreach($course->campuses as $campus)
                            @if ($campus)
                                @include('back.campuses._partials.show', ['campus' => $campus])
                            @endif
                        @endforeach
                    </td>

                    <td class="small-column">{{$course->id}}</td>

                    <td class="control-column cta-column">
                        @php
                            $buttons = [
                                   'view' => [
                                        'text' => 'View Course',
                                        'icon' => 'icon-eye',
                                        'attr' => "onclick=app.redirect('".route('courses.show' , $course)."')",
                                        'class' => '',
                                        'show'  => PermissionHelpers::checkActionPermission('course', 'view', $course)
                                   ],

                                   'edit' => [
                                        'text' => 'Edit',
                                        'icon' => 'icon-note',
                                        'attr' => "onclick=app.redirect('".route('courses.edit' , $course)."')",
                                        'class' => '',
                                        'show'  => PermissionHelpers::checkActionPermission('course', 'edit', $course)
                                   ],

                                   'delete' => [
                                        'text' => 'Delete',
                                        'icon' => 'icon-trash text-danger',
                                        'attr' => 'onclick=app.deleteElement("'.route('courses.destroy' , $course).'","","data-course-id")',
                                        'class' => '',
                                        'show'  => PermissionHelpers::checkActionPermission('course', 'delete', $course)
                                   ]
                            ];

                        @endphp

                        @include('back.layouts.core.helpers.table-actions-permissions' , [
                            'buttons'=> $buttons, 'title'=>null])
                        </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>