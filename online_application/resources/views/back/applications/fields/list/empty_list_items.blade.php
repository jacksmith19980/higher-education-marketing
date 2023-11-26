<div class="row repeated_fields" data-repeated="{{$order}}">

    <div class="col-md-6">
        <div class="form-group">
            <input type="text" class="form-control ajax-form-field multi-element" name="custom_data[{{$order}}][label]"
                placeholder="Label" value="">
        </div>
    </div>

    <div class="col-md-5">
        <div class="form-group">
            <input type="text" class="form-control ajax-form-field multi-element" name="custom_data[{{$order}}][value]"
                placeholder="Value" value="">
        </div>
    </div>

    <div class="col-md-1 action_wrapper">

        <!-- <div class="form-group action_button">
            <button class="btn btn-success" type="button"><i class="ti ti-arrows-vertical"></i></button>
        </div> -->

        <div class="form-group">
            <button class="btn btn-danger" type="button" onclick="app.removeRepeatedElement(this , {{$order}})">
                <i class="fa fa-minus"></i>
            </button>
        </div>

    </div>



</div>

<div class="repeated_fields_wrapper"></div><!-- repeated_fields_wrapper -->
