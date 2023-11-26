@if($envelopeSigners)
<div class="signers-wrapper">
    <table class="table email-table no-wrap table-hover v-middle" style="margin-bottom:75px">

        <thead>
            <tr>
                <th></th>
                <th>{{__('Order')}}</th>
                <th>{{__('Role')}}</th>
                <th>{{__('Name')}}</th>
                <th>{{__('Email')}}</th>
                <th>{{__('Actions')}}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($envelopeSigners as $id => $envelopeSigner)
            <!-- row -->
            <tr class="unread" data-signers-id="{{$id}}">
                <td class="starred">
                    <i class="far fa-user"></i>
                </td>
                <td>
                    {{$envelopeSigner['order']}}
                </td>

                <td>
                    {{$envelopeSigner['role']}}
                </td>
                <td class="user-name">
                    <h6 class="m-b-0">{{ isset($envelopeSigner['first_name']) ? $envelopeSigner['first_name'] . ' ' .
                        $envelopeSigner['last_name'] : 'N/A' }} </h6>
                </td>

                <td class="user-name">
                    <h6 class="m-b-0">{{isset($envelopeSigner['email'])? $envelopeSigner['email'] : 'N/A'}}</h6>
                </td>

                <td class="clip">
                    @include('back.layouts.core.helpers.table-actions' , [
                    'buttons'=> [
                    'edit' => [
                    'text' => 'Edit',
                    'icon' => 'icon-note',
                    'attr' => "onclick=app.editenvelopeSigner('". route('envelope.add.template')."','',this)
                    data-title=".__(str_replace(' ' , "_" , 'Edit_Template'))."",
                    'class' => '',
                    ],
                    'delete' => [
                    'text' => 'Delete',
                    'icon' => 'icon-trash text-danger',
                    'attr' => "onclick=app.deleteEnvelopeSigner('".$envelopeSigner['order']."')",

                    'class' => '',

                    ],
                    ]
                    ])
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="signers-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning">
                {{__("You don't have any document templates in this envelope")}}
            </div>
        </div>
    </div>
</div>
@endif
