<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 px-lg-3 px-md-3">
    <div class="list-item has-no-content">
        <div class="list-header"
             data-misc-option="{{$option}}"
             data-misc-option-price="{{$price}}"
             data-misc-option-key="{{Str::slug($option)}}-{{$key}}"
             onclick="app.selectMisc(this)"
        >
            <div class="flex-container">
                <span class="fas fa-taxi"></span>
                <h4>{{__($option)}}
                    @if ($price != 0)
                        <strong>({{$settings['school']['default_currency']}}{{$price}})</strong>
                    @endif
                </h4>
                <label class="checkbox-container">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
    </div>
</div>