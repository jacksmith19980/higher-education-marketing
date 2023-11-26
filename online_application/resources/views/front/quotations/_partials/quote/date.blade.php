<ul class="quote-sub-total mb-4">
    <li>{{ QuotationHelpers::formatDate($date['start'])}}
        <i class="fas fa-arrow-right color-primary inline-block mx-3"></i>
        {{ QuotationHelpers::formatDate($date['end'])}}
        ({{$settings['school']['default_currency']}}{{$date['price']}})
    </li>
</ul>

@if (isset($date['addons']) && !empty(array_filter($date['addons'])))
    <strong class="d-block text-left">{{__('Add-ons')}}</strong>
    @foreach ($date['addons'] as $group=> $addons)

        @if (!empty($addons))
            <strong class="d-block text-left text-primary"
                    style="font-size:12px;">{{Str::plural( ucwords($group) , 10)}}</strong>
            <ul class="quote-sub-total">
                @foreach ($addons as $addon)
                    <li>{{$addon['title']}} ({{$settings['school']['default_currency']}}{{$addon['price']}})</li>
                @endforeach
            </ul>
        @endif

    @endforeach
@endif
<hr/>