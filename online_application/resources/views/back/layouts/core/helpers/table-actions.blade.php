<div class="btn-group more-optn-group" style="width: 100%;
display: block;">
    @if(isset($show_check_box) && $show_check_box)
    <button type="button" class="btn btn-outline-secondary" style="float:right"></button>
    @endif


    <!-- <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> -->
    <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt flat-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="float:right">

        @if(isset($title) && $title)
            {!! __($title) !!}
        @endif

        </button>


    @if (isset($buttons))
    <div class="dropdown-menu">
        @foreach ($buttons as $button)

            <a class="dropdown-item {{$button['class']}}" href="{{ isset($button['href']) ? $button['href'] : 'javascript:void(0)'  }}" {{ isset($button['attr']) ? $button['attr'] : ''  }} >
                <i class="{{$button['icon']}}"></i> <span  class="icon-text">{!! __($button['text']) !!}</span>
            </a>

        @endforeach
    </div>
    @endif
</div>
