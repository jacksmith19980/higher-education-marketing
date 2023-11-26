@php
    $i = 1;
@endphp
@foreach ($items as $val=>$la)
    @if(is_array($la))
            <h5>{{$val}}</h5>
            @foreach ($la as $v=>$l)
                @include('back.applications.fields.list.mautic.custom_list_item' , ['order' => $i , 'label' => $l , 'value' => $v  ])
                @php
                    $i++;
                @endphp
            @endforeach
    @else
            @include('back.applications.fields.list.mautic.custom_list_item' , ['order' => $i , 'label' => $la , 'value' => $val  ])
            @php
                $i++;
            @endphp
    @endif
@endforeach
