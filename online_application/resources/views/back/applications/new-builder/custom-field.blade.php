{{--  @dump($customField)  --}}
<div
class="custom-prop"
:class="{'no-draggable' : {{ !$customField['is_used'] }} }"
:draggable="{{!$customField['is_used']}}"
x-on:dragstart.self="startFieldDragging( {{$customField['id']}} , true)"
x-on:dragend.self="endFieldDragging({{$customField['id']}} , true)"
>
    <div class="custom-prop-name">{{$customField['name']}}</div>
    <div class="custom-prop-type">{{$customField['field_type']}}</div>
</div>
