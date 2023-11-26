@extends('back.layouts.core.helpers.table' , [
    'show_buttons' => true,
    'title'=> 'Modules'
])

@section('table-content')

    <table id="index_table" class="table table-striped table-bordered display">

        <thead>
        <tr>
{{--            <th class="control-column"></th>--}}
            <th>{{__('Module')}}</th>
            <th>{{__('Course')}}</th>
            <th>ID</th>
            <th>{{__('Actions')}}</th>
        </tr>
        </thead>

        <tbody>

        @if ($modules)

            @foreach ($modules as $module)

                <tr data-module-id="{{$module->id}}">

{{--                    <td class="control-column">--}}
{{--                        @include('back.layouts.core.helpers.table-actions' , [--}}
{{--                           'buttons'=> [--}}
{{--                                  'edit' => [--}}
{{--                                       'text' => 'Edit',--}}
{{--                                       'icon' => 'icon-note',--}}
{{--                                       'attr' => "onclick=app.redirect('".route('modules.edit' , $module)."')",--}}
{{--                                       'class' => '',--}}
{{--                                  ],--}}

{{--                                  'delete' => [--}}
{{--                                       'text' => 'Delete',--}}
{{--                                       'icon' => 'icon-trash text-danger',--}}
{{--                                       'attr' => 'onclick=app.deleteElement("'.route('modules.destroy' , $module).'","","data-module-id")',--}}
{{--                                       'class' => '',--}}
{{--                                  ],--}}
{{--                           ]--}}
{{--                       ])--}}

{{--                    </td>--}}

{{--                    <td><a href="{{route('modules.edit' , $module)}}">{{$module->title}}</a></td>--}}
                    <td>{{$module->title}}</td>

                    <td>{{$module->course->title}}</td>
                    <td class="small-column">{{$module->id}}</td>

                    <td class="small-column">
                        <a href="javascript:void(0)" onclick="app.redirect(`{{route('modules.edit' , $module)}}`)"><i class="icon-note"></i></a>
                        <a href="javascript:void(0)" onclick="app.deleteElement(`{{route('modules.destroy' , $module)}}`, '','data-module-id')"><i class="icon-trash text-danger"></i></a>
                    </td>

                </tr>

            @endforeach

        @endif

        </tbody>

    </table>

@endsection