@if($envelopeTemplates)
<div class="temaplates-wrapper">
    <table class="table email-table no-wrap table-hover v-middle" style="margin-bottom:75px">
        <thead>
            <tr>
                <th></th>
                <th>{{__('Document')}}</th>
                <th>{{__('Actions')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($envelopeTemplates as $envelopeTemplate)
            @if(isset($envelopeTemplate['id']))
            <!-- row -->
            <tr class="unread" data-template-id="{{$envelopeTemplate['id']}}">
                <td class="starred"><i class="far fa-file-pdf"></i></td>
                <td class="user-name">
                    <h6 class="m-b-0">{{$envelopeTemplate['name']}}</h6>
                </td>
                <td class="clip">
                    @if($envelope)
                    @include('back.layouts.core.helpers.table-actions' , [
                    'buttons'=> [
                    'edit' => [
                    'text' => 'Edit',
                    'icon' => 'icon-note',
                    'attr' => "onclick=app.editEnvelopeTemplate('".
                    route('envelope.edit.template' ,
                    [
                    'envelope'=> $envelope,
                    'template' => $envelopeTemplate['id']
                    ] )."','',this)
                    data-title=".__(str_replace(' ' , "_" , 'Edit_Template'))."" . " data-template-id=" .
                    $envelopeTemplate['id'] ,
                    'class' => '',
                    ],
                    'delete' => [
                    'text' => 'Delete',
                    'icon' => 'icon-trash text-danger',
                    'attr' => "onclick=app.deleteEnvelopeTemplate('".$envelopeTemplate['id']."')",

                    'class' => '',

                    ],
                    ]
                    ])
                    @endif
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="temaplates-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning">
                {{__("You don't have any document templates in this envelope")}}
            </div>
        </div>
    </div>
</div>
@endif
