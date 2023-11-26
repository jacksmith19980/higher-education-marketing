@extends('back.layouts.core.helpers.table' , ['show_buttons' => false,'title'=> 'Submission Review'])

@section('table-content')
    <div class="col-12">
        <div class="review-page" data-route="{{route('submissions.review', [$submission])}}">
            <center>
                <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
            </center>
        </div>
    </div>

@endsection
