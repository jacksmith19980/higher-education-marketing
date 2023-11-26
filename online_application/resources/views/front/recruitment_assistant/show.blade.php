@extends('front.recruitment_assistant.layouts.layout')
@section('content')
    {{-- CTA Group --}}
    @include('front.recruitment_assistant._partials.general.cta')
    {{-- CTA Group --}}

    @include('front.recruitment_assistant._partials.' . $step . '.index')

    {{-- Helper --}}
    @include('front.recruitment_assistant._partials.general.helper')
    {{-- Helper --}}

   
@endsection