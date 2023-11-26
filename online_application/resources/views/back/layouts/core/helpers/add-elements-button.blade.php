<button type="button"
class="btn btn-success btn-block"
{{isset($disabled) ? $disabled : ' '}}
onclick="app.addSlotElements(this,{{isset($order)?$order:0}})" data-action="{{$action}}" data-container="{{$container}}" @if(isset($count)) data-count="{{$count}}"  @endif>
            <i class="fa fa-plus"></i> {{__($text)}}
</button>
