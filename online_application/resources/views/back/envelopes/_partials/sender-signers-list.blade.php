@if($signersList)
<div class="col-12">
    <h4>{{__('Signers List')}}</h4>
    <table class="table email-table no-wrap table-hover v-middle" style="margin-bottom:75px">
        <thead>
            <tr>
                <th>{{__('Order')}}</th>
                <th>{{__('Role')}}</th>
                <th>{{__('First Name')}}</th>
                <th>{{__('Last Name')}}</th>
                <th>{{__('Email')}}</th>

            </tr>
        </thead>

        <tbody>
            @foreach ($signersList as $order => $signer)
            <tr>
                <td>
                    <strong>{{$order}}</strong>
                </td>
                <td>
                    <strong>{{$signer['role']}}</strong>
                    <input type="hidden" class="ajax-form-field" value="{{$signer['role']}}"
                        name='signer_{{$order}}][role]'>
                </td>
                <td>
                    @if($signer['first_name'])
                    {{$signer['first_name']}}
                    <input class="ajax-form-field" type="hidden" value="{{$signer['first_name']}}"
                        name='signer_{{$order}}][first_name]'>

                    @else
                    @include('back.layouts.core.forms.select',
                    [
                    'name' => 'signer_'. $order .'][first_name]',
                    'label' => false ,
                    'class' => 'ajax-form-field' ,
                    'required' => true,
                    'attr' => '',
                    'placeholder' => __('Select Field'),
                    'data' => $fields,
                    'value' => ''
                    ])
                    @endif
                </td>
                <td>
                    @if($signer['last_name'])
                    <input type="hidden" class="ajax-form-field" value="{{$signer['last_name']}}"
                        name='signer_{{$order}}][last_name]'>
                    {{$signer['last_name']}}
                    @else
                    @include('back.layouts.core.forms.select',
                    [
                    'name' => 'signer_'. $order .'][last_name]',
                    'label' => false ,
                    'class' => 'ajax-form-field' ,
                    'required' => true,
                    'attr' => '',
                    'placeholder' => __('Select Field'),
                    'data' => $fields,
                    'value' => ''
                    ])
                    @endif

                </td>
                <td>
                    @if($signer['email'])
                    <input type="hidden" class="ajax-form-field" value="{{$signer['email']}}"
                        name='signer_{{$order}}][email]'>
                    {{$signer['email']}}
                    @else
                    @include('back.layouts.core.forms.select',
                    [
                    'name' => 'signer_'. $order .'][email]',
                    'label' => false ,
                    'class' => 'ajax-form-field' ,
                    'required' => true,
                    'attr' => '',
                    'placeholder' => __('Select Field'),
                    'data' => $fields,
                    'value' => ''
                    ])
                    @endif
                </td>

            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endif
