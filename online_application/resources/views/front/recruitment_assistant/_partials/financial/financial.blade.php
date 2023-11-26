@php
    // if Multi Courses selection is enabled
    $multi = isset($quotation->properties['enable_multi_program'])? true : false
@endphp
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 px-lg-6 px-md-6">
    <div class="list-item">
        <div class="list-header campus-item"
             onclick="app.selectFinancial('{{$financial}}', true )"
             data-financial="{{$financial}}">

            <div class="flex-container">
                <span class="fas fa-map-marker-alt"></span>

                <h3>{{$financial}}</h3>

                <label class="checkbox-container">
                    <span class="checkmark"></span>
                </label>

            </div>
        </div>
        {{--   @endif  --}}
    </div>
</div>