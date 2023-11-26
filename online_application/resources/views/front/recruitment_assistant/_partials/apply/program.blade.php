<div class="program-list-item mb-4">
    <div class="card">
        <div class="card-header vaa-summary">
            <p class="text-capitalize text-left" style="color: #fff;">
                {{__('YOUR PROGRAM SUMMARY')}}
            </p>
        </div>
        <div class="card-body">
            <ul class="fa-ul">
                @if (isset($cart['programs']) && count($cart['programs']) > 0)
                    <li><span class=" fa-li text-muted"><i class="fas fas fa-graduation-cap"></i></span>
                        <h5 class="text-left program-title">{{$cart['programs'][0]['title']}}</h5>

                        <p class="text-justify program-text">
                            {{ 	strip_tags(substr($cart['programs'][0]['details'], 0,  300)) }}
                        </p>
                    </li>
                    <br>
                @endif
                <li><span class=" fa-li text-muted text-muted"><i class="fas fa-map-marker-alt  text-muted"></i></span>
                <h5 class="text-left program-title">{{array_key_exists('campuses', $cart) ? $cart['campuses'][0]['title'] : ''}} {{__('Campus')}}</h5>
                    <p class="text-justify program-text">
                        @isset($cart['campuses'][0]['location'])
                            {{$cart['campuses'][0]['location']}}
                        @endisset
                    </p>
                </li>
                @if (isset($cart['programs']) && count($cart['programs']) > 0)
                <br>
                <li><span class=" fa-li text-muted"><i class="fas  fas fa-calendar-alt"></i></span>
                    <h5 class="text-left program-title">
                        {{ QuotationHelpers::formatDate($cart['programs'][0]['start'], 'd M, Y') }} - {{ QuotationHelpers::formatDate($cart['programs'][0]['end'], 'd M, Y') }}
                        ( {{ \App\Helpers\Assistant\AssistantHelpers::getSchedule($cart['programs'][0]['schudel']) }})
                    </h5>
                </li>
                @endif
                @if(isset($cart['financials']) && count($cart['financials']) > 0)
                    <br>
                    <li><span class=" fa-li text-muted"><i class="far fa-money-bill-alt"></i></span>
                        <h5 class="text-left program-title">
                            {{__('Financial Aid')}}
                        </h5>
                        @foreach($cart['financials'] as $financial)
                            <p class="text-justify program-text">
                            {{$financial}}
                            </p>
                        @endforeach
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>