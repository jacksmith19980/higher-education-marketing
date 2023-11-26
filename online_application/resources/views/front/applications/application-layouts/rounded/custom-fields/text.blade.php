<div class="field_wrapper col-md-6 {{($properties['hidden']) ? 'hidden' : ''}}">
<div class="form-group">
    <label for="{{$properties['name']}}">{{$properties['label']['text']}}</label>
    <input
        class="customFieldInput form-control-lg form-control{{ $errors->has($properties['name']) ? ' is-invalid' : '' }} {{ ($properties['class']) }}"
        type="{{($properties['hidden']) ? 'hidden' : 'text'}}"
        id="{{$properties['name']}}"
        data-customField = "{{ isset($customFieldName) ? $customFieldName : '' }} "
        name="{{$properties['name']}}"
        value="{{$properties['value']}}"
        {{(!$properties['editable']) ? 'readonly' : ''}}
    />
</div>
</div>
