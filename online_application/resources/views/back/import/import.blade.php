@extends('back.layouts.default')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="float-left">
                    <h4 class="page-title">{{__('Import Contacts')}}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


                    <form novalidate=""
                        autocomplete="false"
                        role="form" name="contact_import" method="post"
                        action="{{route('import.import')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                @include('back.layouts.core.forms.hidden-input',
                                [
                                    'name'      => 'file',
                                    'label'     => 'File' ,
                                    'class'     =>'' ,
                                    'required'  => true,
                                    'attr'      => '',
                                    'value'     => $url
                                ])
                            </div>
                        </div>

                        <div class="row">
                            @foreach ($headers as $header)
                                <div class="col-6">
                                    @include('back.layouts.core.forms.select',
                                    [
                                        'name'          => "map[$header]",
                                        'label'         => $header ,
                                        'placeholder'   => 'Select Field',
                                        'class'         =>'select2' ,
                                        'required'      => true,
                                        'attr'          => '',
                                        'value'         => $header,
                                        'data'          => $fields
                                    ])
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-2 offset-10">
                                <input type="submit" name="" value="Import" class="btn btn-primary" />
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>


@endsection
