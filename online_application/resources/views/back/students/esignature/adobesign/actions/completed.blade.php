@include('back.layouts.core.helpers.table-actions' , [
    'buttons'=> [
            'download' => [
                    'text' => 'Download',
                    'icon' => 'mr-1 ti-download',
                    'attr' => 'onclick=app.downloadContract(this,"'.route('contart.documents.list' , $contract->id).'")
                    data-title='.__('Documents List').' ',
                    'class' => '',
                ],
    ]
])
