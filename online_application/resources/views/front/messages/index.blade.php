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
                    <div class="p-4 mb-4 app-dashboard-container box-shadow ">
                        <div class="app-dashboard-header">
                            <div class="row align-items-center">

                                <div class="p-15 b-b w-100">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h4>{{__('Messages')}}</h4>
                                            <span>
                                                {{__('Here is the list of your messages')}}
                                            </span>
                                        </div>
                                        <div class="ml-auto">

                                            @include('back.messages.search-form' , [
                                                'attr'   => 'onkeydown=message.searchMessages(this,'.$student->id.')',
                                            ])
                                        </div>
                                    </div>
                                </div>

                                @if (count($messages))
                                    <table id="MessagesTable" class="table email-table no-wrap table-hover v-middle">
                                        <tbody>
                                            @foreach($messages as $message)
                                                @include('back.messages.message-list' , [
                                                    'message'   => $message,
                                                    'applicant' => $student,
                                                    'front'     => true
                                                ])
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                        @include('back.students._partials.student-no-results')
                                    @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
