<div class="header-main-content pt-4 pb-4 pb-md-4 pb-lg-5">
    <h3>{{__($steps['current']['title'])}}</h3>
    @if (isset($steps['current']['sub_title']))
    <p>{{__($steps['current']['sub_title'])}}</p>
    @endif
</div>

@if (isset($steps['current']['instructions']))
{!! $steps['current']['instructions'] !!}
@endif