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

                    <div class="alert alert-success">
                        ({{count($contacts)}})  {{__(' Students Imported Successfully!')}}
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
