<div class="col-lg-12 col-xlg-12 col-md-10">
    <div class="card">
        <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
            @foreach($days as $dayTab)
                <li class="nav-item">
                    <a class="nav-link @if($dayTab == 'Monday')  active show @endif" id="pills-{{$dayTab}}-tab"
                       data-toggle="pill"
                       href="#{{$dayTab}}" role="tab"
                       aria-controls="pills-{{$dayTab}}" aria-selected="false">
                        {{__($dayTab)}}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" id="pills-tabContent">
            @foreach($days as $day)
                @php
                    $keys = array_keys(array_column($classroom->classroomSlots->toArray(), 'day'), $day);
                @endphp
                @include('back.classrooms._partials.day-tab', ['keys' => $keys])
            @endforeach
        </div>

    </div>
</div>