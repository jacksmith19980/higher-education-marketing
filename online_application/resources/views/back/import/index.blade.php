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
                <div class="row">
                    <div class="offset-3 col-sm-6">
                        <div class="ml-lg mr-lg mt-md pa-lg">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <div class="alert alert-info">
                                        Upload the CSV file that contains the items to be imported. The next step will be to match the fields in the file with the fields available in HEM-SP.
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <form novalidate=""
                                    autocomplete="false"
                                    role="form" name="contact_import" method="post"
                                    action="{{route('import.upload')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group well mt-lg">
                                            <input
                                            type="file"
                                            id="contact_import_file" name="contact_import[file]" required="required"
                                            accept=".csv"
                                            class="form-control" autocomplete="false">
                                    </div>

                                        <div class="input-group well mt-3">
                                            <button type="submit" id="contact_import_start" name="contact_import[start]" class="btn btn-primary">
                                                <i class="fa fa-upload "></i>
                                                {{__('Upload')}}
                                            </button>
                                    </div>
                                    </form>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
