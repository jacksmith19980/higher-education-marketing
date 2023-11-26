@extends('back.layouts.core.helpers.table' , [
    'show_buttons' => $permissions['create|field'],
    'title'=> 'Custom fields'
    ])

@section('table-content')

        <table id="index_table" class="table table-bordered new-table nowrap display">
            <thead>
                <tr>
                    <!-- <th class="control-column">{{__('Actions')}}</th> -->
                    <th>{{__('Custom fields')}}</th>
                    <th>{{__('Slug')}}</th>
                    <th>ID</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @if ($customFields)
                    @foreach ($customFields as $customField)
                        <tr data-customfield-id="{{$customField->id}}">

                            <td>{{$customField->name}}</td>

                            <td>{{$customField->slug}}</td>

                            <td class="small-column">{{$customField->id}}</td>

                            @php
                                $buttons['buttons']['edit'] = [
                                    'text' => 'Edit',
                                    'icon' => 'icon-note',
                                    'attr' => "onclick=app.redirect('".route('customfields.edit' , $customField)."')",
                                    'class' => '',
                                    'show'  => PermissionHelpers::checkActionPermission('field', 'edit', $customField)
                                ];
                                $buttons['buttons']['delete'] = [
                                    'text' => 'Delete',
                                    'icon' => 'icon-trash text-danger',
                                    'attr' => 'onclick=app.deleteElement("'.route('customfields.destroy' , $customField).'","","data-customfield-id")',
                                    'class' => '',
                                    'show'  => PermissionHelpers::checkActionPermission('field', 'delete', $customField)
                                ];
                            @endphp

                            <td class="control-column cta-column">
                                @include('back.layouts.core.helpers.table-actions-permissions', $buttons)
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
@endsection
