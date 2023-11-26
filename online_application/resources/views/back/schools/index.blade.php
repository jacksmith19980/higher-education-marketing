@extends('back.layouts.core.helpers.table' ,
[
'show_buttons' => true,
'title' => 'Schools',
]
)
@section('table-content')
<table id="index_table" class="table table-striped table-bordered display" data-order='[[ 2, "desc" ]]'>
    <thead>
        <tr>
            <th>{{__('School Name')}}</th>
            @if(auth()->guard('web')->user()->isHem)
                <th style="width:70px;!important">{{__('Plan')}}</th>
            @endif
            <th>ID</th>
            <th>{{__('Actions')}}</th>
        </tr>
    </thead>

    <tbody>
        @if ($schools)
        @foreach ($schools as $school)
        <tr data-school-id="{{$school->id}}">

            <td><a href="{{ route('tenant.switch' , $school) }}">{{$school->name}}</a></td>

            @if(auth()->guard('web')->user()->isHem)

                    <td class="small-column">{{$school->plan->title}}</td>
            @endif

            <td class="small-column">{{$school->id}}</td>
            <td>
                @php
                    $buttons['buttons']['edit'] = [
                        'text' => 'Edit',
                        'icon' => 'icon-note',
                        'attr' => null,
                        'href' => route('schools.edit' , $school),
                        'class' => 'dropdown-item',
                        'show'  => false //auth()->guard('web')->user()->isHem
                    ];
                    $buttons['buttons']['delete'] = [
                        'text' => 'Delete',
                        'icon' => 'icon-trash text-danger',
                        'attr' => 'onclick=app.deleteElement("'.route('schools.destroy' ,
                        $school).'","","data-school-id")',
                        'class' => 'dropdown-item',
                        'show'  => in_array(Auth::user()->id , explode("," , env('SUPER_ADMIN_ID')))
                    ];
                @endphp
                @include('back.layouts.core.helpers.table-actions-permissions' , $buttons)
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
@endsection
