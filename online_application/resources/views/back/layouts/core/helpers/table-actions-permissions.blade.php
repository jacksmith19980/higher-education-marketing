@php
    $toShow = array_filter(array_column($buttons,'show'));

@endphp

@if(count($toShow))

<div class="btn-group more-optn-group" style="text-align:right">
    @if(isset($show_check_box) && $show_check_box)
        <button
        type="button"
        class="btn btn-outline-secondary"
        {{!count($toShow) ? 'disabled="disabaled"' : ''}}
        ></button>

    @endif

        <button
        type="button"
        class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt flat-btn"
        {{!count($toShow) ? 'disabled="disabaled"' : ''}}
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
        >

        @if(isset($title) && $title)
            {!!__($title)!!}
        @endif

        </button>


    @if (isset($buttons) && count($toShow))
    <div class="dropdown-menu">
        @foreach ($buttons as $button)
            @if($button['show'])
                @php
                    $href = isset($button['href']) ? $button['href'] :  "javascript:void(0)";
                @endphp
                <a class="dropdown-item {{$button['class']}}" href="{{$href}}" {{$button['attr']}} >
                    <i class="{{$button['icon']}}"></i> <span  class="icon-text">{!!__($button['text'])!!}</span>
                </a>
            @endif
        @endforeach
    </div>
    @endif
</div>
@endif
