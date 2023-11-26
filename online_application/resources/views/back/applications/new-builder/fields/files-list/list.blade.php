<div class="row">
    <input type="hidden" name="data" class="ajax-form-field" value="custom_list" />

    <div class="col-md-12" style="padding: 30px 15px">
        <a href="javascript:void(0)" class="btn btn-success float-right"
            onclick="app.repeatElement('field.list' , 'custom_list_wrapper')">{{__('+ Add Value')}}</a>
    </div>

    <div class="col-md-12">
        <div class="custom_list_wrapper"
            style="">


            @if (optional($field)->data)
                @include('back.applications.fields.list.custom_list' , ['items' => optional($field)->data ])
            @else
                @include('back.applications.fields.list.empty_list_items' , [
                    'order' => ( isset($field->data) ) ? count($field->data) : 1  ])
            @endif

        </div><!-- custom_list_wrapper -->
    </div>
</div>
