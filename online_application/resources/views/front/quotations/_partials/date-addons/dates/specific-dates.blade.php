
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 px-lg-2 px-md-2">
    <div class="list-item">
        <div class="list-header date-item {{ isset($date->properties['full']) ? ' disabled' : '' }}"
            data-start="{{$date->properties['start_date']}}"
            data-date="{{$date->key}}"
            onclick="app.selectDate('{{$date->key}}' , '{{$course->id}}' , '{{$date->properties['date_price']}}' , '{{$date->properties['start_date']}}', '{{$date->properties['end_date']}}')"
        >
            <div class="flex-container">
                <h4>{{ QuotationHelpers::formatDate($date->properties['start_date']) }}
                    - {{ QuotationHelpers::formatDate($date->properties['end_date']) }}
                    <strong>({{$settings['school']['default_currency']}}{{$date->properties['date_price']}})</strong>
                </h4>

                @if(isset($date->properties['full']))
                    <span class="badge badge-pill badge-success text-center d-inline">FULL</span>
                @else
                    <label class="checkbox-container">
                        <span class="checkmark"></span>
                    </label>
                @endif
            </div>
        </div>

        @if (isset($date->properties['addons']))
            <div class="list-content {{ (empty($date->properties['addons'])) ? 'hidden' : 'expandable'}}">
                @foreach ( QuotationHelpers::addonsByGroup($date->properties['addons']) as $group => $addons)
                    <strong class="d-block text-left pt-2">{{ __(QuotationHelpers::addonsTitle($group)) }}</strong>
                    <hr>
                    <ul>
                        @foreach ( $addons as $key => $addon)
                            <li class="d-flex" onclick="app.selectAddon(this)"
                                data-date-key="{{$date->key}}"

                                data-addon-key="{{$key}}"
                                data-addon-title="{{$addon['title']}}"
                                data-addon-price="{{$addon['price']}}"
                                data-addon-price-type="{{$addon['price_type']}}"
                                data-addon-group="{{$group}}"
                                data-addon-date="{{isset( $addon['date'] ) ? $addon['date'] : false}}"

                                data-allow-multi="{{ ( in_array($group , ['excursion'] ) ) ? true : false }}"
                            >
                                <span class="label">{{$addon['title']}}
                                    @if (isset($addon['date']))
                                        <small class="d-block text-primary">
                                            {{ QuotationHelpers::formatDate($addon['date']) }}
                                        </small>
                                    @endif
                                </span>

                                <span class="d-flex">
                                    @if($addon['price'] == '0' || !isset($addon['price']) || $addon['price'] == '')
                                        <span class="badge badge-pill badge-success mr-4 mt-1 text-center d-inline">{{__('FREE')}}</span>
                                    @else
                                        <span class="badge badge-pill badge-primary mr-4">
                                            {{$settings['school']['default_currency']}}{{$addon['price']}}
                                        </span>
                                    @endif
                                </span>

                                <label class="checkbox-container">
                                    <span class="checkmark"></span>
                                </label>

                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
            <div class="list-footer disabled {{ (empty($date->properties['addons'])) ? 'hidden' : ''}}"
                 data-date-footer="{{$date->key}}">
                <div class="flex-container">
                    <h5>{{__('Select')}}</h5>
                    <span class="fas fa-angle-down"></span>
                </div>
            </div>
        @endif
    </div>
</div>
