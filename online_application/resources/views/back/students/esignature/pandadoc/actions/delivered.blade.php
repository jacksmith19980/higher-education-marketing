@include('back.layouts.core.helpers.table-actions' , [
    'buttons'=> [
            'reminder' => [
                    'text' => 'Download',
                    'icon' => 'mr-1 ti-download',
                    'attr' => 'onclick=app.downloadContract(this,"'.route('contart.documents.list' , $contract->id).'")
                    data-title='.__('Documents List').' ',
                    'class' => '',
                ],
            'void' => [
                'text' => 'Void',
                'icon' => 'mr-1 icon-trash text-danger',
                'attr' => 'onclick=app.voidContract('.$contract->id.')',
                'class' => '',
            ],
    ]
])
