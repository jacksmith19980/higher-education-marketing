@extends('back.layouts.core.helpers.no-table' , ['show_buttons' => false,'title'=> null])

@section('page-content')
<div  class="row justify-content-center api_key_card_wrapper" style="min-height: 80vh">
    @include('back.api-key.key' , ['key' => $key])
</div>
@endsection
