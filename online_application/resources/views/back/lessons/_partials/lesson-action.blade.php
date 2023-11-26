@php
$buttons['buttons'] = [
    'view' => [
        'text'  => 'View',
        'icon'  => 'icon-eye',
        'attr'  => "",
        'href'  => route('lessons.show' , $lesson),
        'class' => 'dropdown-item',
        'show'  => 'true' // To use perissmions
    ],
    'edit' => [
        'text'  => 'Edit',
        'icon'  => 'icon-note',
        'attr'  => "",
        'href'  =>route('lessons.edit' , $lesson),
        'class' => 'dropdown-item',
        'show'  => 'true' // To use perissmions
    ],
    'delete' => [
        'text' => 'Delete',
        'icon' => 'icon-trash text-danger',
        'attr' => "onclick=app.deleteInvoice('".route('lessons.destroy' , $lesson). "')",
        'class' => 'dropdown-item',
        'show'  => 'true' // To use perissmions
    ],
];
@endphp
<div style="display:block;width:100%;text-align:right">
    @include('back.layouts.core.helpers.table-actions-permissions' , $buttons)
</div>
