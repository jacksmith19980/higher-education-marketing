@extends('front.layouts.minimal')

@section('messages')
    <script src="{{ asset('media/js/front/messages.js') }}"></script>
@endsection

@section('content')
<div class="page-wrapper" style="padding-top: 100px;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="sec-app-dashboard">
                    <div class="p-4 mb-4 app-dashboard-container box-shadow  bg-grey-1">
                        <div class="app-dashboard-header">
                            <div class="row align-items-center">
                                @include('back.messages.message' , [
                                    'message'   => $message,
                                    'recipient' => $recipient,
                                    'owner'     => $owner,
                                    'student'   => $student,
                                    'front'     => true
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
