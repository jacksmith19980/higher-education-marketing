@extends('back.layouts.default')
@section('content')

    <div class="row justify-content-center">

        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="float-left">
                    <h4 class="page-title">{{__('Application Forms')}}</h4>
                    </div>
                        @if($show_buttons)
                            <div class="float-right btn-group" role="group">
                                <div id="myid" data-name="add_new_application_dropdown" class="btn-group" role="group">
                                    <button data-name="dropdown-toggle" id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{__("Add New")}}
                                    </button>
                                    <div data-name="dropdown-menu" class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                        <a class="dropdown-item student-application" href="{{route( $params['modelName'].'.create' , [
                                            'object' => 'student',
                                            'hash'   => $hash
                                        ])}}@if($hash){{'#addapplication'}} @endif">{{__('Student Application')}}</a>

                                        <a class="dropdown-item" href="{{route( $params['modelName'].'.create' , [
                                            'object' => 'agency',
                                            'hash'   => $hash
                                        ])}}@if($hash){{'#addapplication'}} @endif">{{__('Agencies Application')}}</a>

                                        <a class="dropdown-item" href="{{route( $params['modelName'].'.create' , [
                                            'object' => 'form',
                                            'hash'   => $hash
                                        ])}}@if($hash){{'#addapplication'}} @endif">{{__('Form Application')}}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="container-fluid">
            <!-- <div class="card">
                <div class="card-body">
                    <div class="table-responsive"> -->
                        @yield('table-content')
                    <!-- </div>
                </div>
            </div> -->
            </div>
        </div>

    </div>
@endsection
