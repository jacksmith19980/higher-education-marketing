@extends('front.layouts.quotation-layout')
@section('content')
<div class="page-wrapper" style="padding-top: 100px; display: block;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1>Thank you for your interest</h1>
                <a href="{{route('school.register' , [
                'school'    => $school,
                'booking'   => $booking->id,
                'user'      => $booking->user_id,
            ] )}}" class="btn btn-lg btn-success">BOOK NOW</a>
            </div>
        </div>
    </div>
</div>
@endsection