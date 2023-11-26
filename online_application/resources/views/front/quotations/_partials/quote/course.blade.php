<div class="program-list-item mb-4">
    <div class="program-list-header">
        <div class="flex-container">
            <h4>{{$course['title']}} - {{$campus->title}}
                ({{$settings['school']['default_currency']}}{{$course['total']}}) </h4>
            <span class="fas fa-plus"></span>
        </div>
    </div>
    <div class="program-list-content py-3 px-3">
        @foreach ($course['dates'] as $date)
            @include('front.quotations._partials.quote.date' , [
                'course' => $course,
                'date' => $date
            ])
        @endforeach

        @isset($course['fee'])
            <p class="sub-total text-right color-primary">
                {{__('Application Fee')}}:{{$settings['school']['default_currency']}}{{$course['fee']}}</p>
            <hr>
        @endisset

        <p class="sub-total text-right color-primary">
            {{__('Sub-total')}}:{{$settings['school']['default_currency']}}{{$course['total']}}</p>
    </div>
</div>