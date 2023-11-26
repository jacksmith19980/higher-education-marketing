<div class="row repeated_fields" data-repeated="{{$order}}">

    <div class="col-md-6">
        <div class="form-group">
            <input type="text" disabled class="form-control ajax-form-field multi-element" name="custom_data[{{$order}}][label]" placeholder="Label" value="{{isset($label) ? $label : ''}}">
        </div>
    </div>

    <div class="col-md-5">
        <div class="form-group">
            <input type="text"disabled class="form-control ajax-form-field multi-element" name="custom_data[{{$order}}][value]" placeholder="Value" value="{{isset($value) ? $value : ''}}">
        </div>
    </div>

</div>
