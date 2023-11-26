@extends('front.layouts.assistant-layout')
{{-- @extends('front.recruitment_assistant.layouts.minimal') --}}
@section('content')
<div class="page-wrapper" style="padding-top: 100px; display: block;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1>Thank you for your interest</h1>
                <!-- <a href="{{route('school.register' , [
                'school'    => $school,
                'assistant'   => $assistant->id,
                'user'      => $assistant->user_id,
            ] )}}" class="btn btn-lg btn-success">APPLY NOW</a> -->
            </div>
        </div>
    </div>
</div>
@endsection