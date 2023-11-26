@if (count($shareables))
    <div class="shareables">
        <table class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
            <thead>
                <tr>
                    <th>{{__('Document Title')}}</th>
                    <th>{{__('Description')}}</th>
                    <th style="width:40px;">{{__('Action')}}</th>
                </tr>
            </thead>
            @foreach ($shareables as $shareable)
                <tr>
                    <td>
                        {{isset($shareable->document) ? $shareable->document->name : ''}}
                    </td>
                    <td>
                        {{isset($shareable->document) ? $shareable->document->description : ''}}
                    </td>
                    <td>
                        @include('back.layouts.core.helpers.table-actions' , [
                            'buttons'=> [
                                    'view' => [
                                        'text' => 'View',
                                        'icon' => 'mr-1 icon-eye',
                                        'attr' => 'onclick=app.getStudentSharedDocument('. $shareable->id .',"view")',
                                        'class' => '',
                                    ],
                                    'download' => [
                                        'text' => 'Download',
                                        'icon' => 'mr-1 icon-arrow-down-circle text-green',
                                        'attr' => 'onclick=app.getStudentSharedDocument('.$shareable->id.',"download")',
                                        'class' => '',
                                    ],
                                    
                            ]
                        ])
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@else
    @include('back.students._partials.student-no-results')
@endif