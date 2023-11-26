@include('back.layouts.core.helpers.table-actions' , [
    'buttons'=> [
            'review-send' => [
                    'text' => 'Review and Send',
                    'icon' => 'mr-1 icon-eye',
                    'attr' => 'onclick=app.reviewContract('. $contract->id .')',
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
