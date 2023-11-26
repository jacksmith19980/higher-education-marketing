@extends('front.layouts.'.$application->layout.'-layout')

@section('content')
   @include('front.applications.application-layouts.'.$application->layout.'.'.$application->layout.'-form')
@endsection
