<div class="tab-pane fade show active" id="promocode" role="tabpanel" aria-labelledby="pills-promocode-tab">
    <div class="card-body">

        <div class="row">

            <div class="col-md-10">
                <form method="post" action="{{route('promocodes.update' , $promocode)}}" class="needs-validation" novalidate="" enctype="multipart/form-data">

                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            @include('back.layouts.core.forms.select', [
                                'name'      => 'type',
                                'label'     => 'Type',
                                'class'     => '',
                                'required'  => true,
                                'attr'      => 'disabled',
                                'value'     => $promocode->type,
                                'data'      => $types
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('back.layouts.core.forms.text-input', [
                                'name'      => 'reward',
                                'label'     => 'Reward',
                                'class'     => '',
                                'required'  => true,
                                'attr'      => 'disabled',
                                'value'     => ($promocode->reward) ?? '0',
                            ])
                        </div>
                    </div> <!-- row -->

                    <div class="row">
{{--                        <div class="col-md-6">--}}
{{--                            @include('back.layouts.core.forms.text-input', [--}}
{{--                                'name'      => 'quantity',--}}
{{--                                'label'     => 'Quantity',--}}
{{--                                'class'     => '',--}}
{{--                                'required'  => false,--}}
{{--                                'attr'      => 'disabled',--}}
{{--                                'value'     => ($promocode->quantity) ?? '1'--}}
{{--                            ])--}}
{{--                        </div>--}}
                        <div class="col-md-6">
                            @include('back.layouts.core.forms.text-input', [
                                'name'      => 'data[message]',
                                'label'     => 'Message',
                                'class'     => '',
                                'required'  => false,
                                'attr'      => 'disabled',
                                'value'     => ($promocode->data['message']) ?? 'No Message'
                            ])
                        </div>
                    </div> <!-- row -->

                    <div class="row">
                        <div class="col-md-6">
                            @include('back.layouts.core.forms.date-input',
                            [
                                'name'      => 'commence_at',
                                'label'     => 'Start Date',
                                'class'     => '',
                                'required'  => false,
                                'attr'      => 'disabled',
                                'value'     => (isset($promocode->commence_at)) ? $promocode->commence_at->format('Y-m-d') : '',
                                'data'      => ''
                            ])
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                @include('back.layouts.core.forms.date-input',
                                [
                                    'name'      => 'expires_at',
                                    'label'     => 'Expires At ',
                                    'class'     => '',
                                    'required'  => false,
                                    'attr'      => 'disabled',
                                    'value'     => (isset($promocode->expires_at)) ? $promocode->expires_at->format('Y-m-d') : '',
                                    'data'      => ''
                                ])
                            </div>
                        </div>
                    </div> <!-- row -->

                    <div class="row">
                        <div class="col-md-6">
                            @include('back.layouts.core.forms.multi-select', [
                                'name'      => 'quotations[]',
                                'label'     => 'Quotations' ,
                                'class'     => 'select2',
                                'required'  => false,
                                'attr'      => 'disabled',
                                'value'     => isset($promocode_quotations) ? $promocode_quotations : [],
                                'data'      => $quotations
                            ])
                        </div>
                    </div> <!-- row -->

                    <div class="row">
                        <div class="col-md-6">
                            @include('back.layouts.core.forms.checkbox', [
                                'name'          => 'is_automatic',
                                'label'         => '',
                                'class'         => '' ,
                                'required'      => false,
                                'attr'          => 'disabled',
                                'helper_text'   => 'Automatic',
                                'value'         => ($promocode->is_automatic) ?? 1,
                                'default'       => 1
                            ])
                        </div>
                    </div> <!-- row -->

                    <div class="row">
                        <div class="col-md-6">
                            @include('back.layouts.core.forms.checkbox', [
                                'name'          => 'is_disposable',
                                'label'         => '',
                                'class'         => '' ,
                                'required'      => false,
                                'attr'          => 'disabled',
                                'helper_text'   => 'Disposable',
                                'value'         => ($promocode->is_disposable) ?? 1,
                                'default'       => 1
                            ])
                        </div>
                    </div> <!-- row -->

                    <div class="col-md-12">
{{--                        <button class="btn btn-success float-right">Save</button>--}}
                        <a class="btn btn-success float-right" href="{{route('promocodes.index')}}">{{__('Back')}}</a>
                    </div>

                </form>
            </div>

        </div>

    </div>
</div>