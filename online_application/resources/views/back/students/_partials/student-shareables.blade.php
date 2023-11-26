<div class="p-4 tab-pane fade l-psuedu-border bg-grey-1" id="nav-shareables" role="tabpanel" aria-labelledby="pills-shareables-tab">

    <div class="row">
        <div class="text-right m-b-20 col-md-2 offset-10">
            
            <button type="button"
            class="btn btn-primary"
           onclick="app.showShareDocument('{{route('document.share.show', ['student' => $student])}}' , '' , '{{__('Share document')}}' , this, '{{ route('document.share') }}')" href="javascript:;">
                <i class="fa fa-plus"></i> {{__('Generate Document')}}
            </button>

            {{--
            <button data-name="dropdown-toggle" id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <i class="fa fa-paper-plane"></i> {{__('Send')}}
            </button>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item btn btn-success" onclick="app.sendEnvelope('{{route('envelope.send' , ['student' => $student])}}' , '' , '{{__('Send Envelope')}}' , this)" href="javascript:;">{{__('Send Envelope')}}</a>
                <a class="dropdown-item btn btn-success" onclick="app.showShareDocument('{{route('document.share.show', ['student' => $student])}}' , '' , '{{__('Share document')}}' , this, '{{ route('document.share') }}')" href="javascript:;">{{__('Share document')}}</a>
            </div>
            --}}
        </div>
    </div>

    <div class="applicant_shareables_div">
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
    </div>
</div>
