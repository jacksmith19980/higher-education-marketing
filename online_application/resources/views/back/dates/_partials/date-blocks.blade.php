{{--  <div class="fluid-container">
    <div class="row">
        <div class="col-md-2 offset-10 m-b-30">
            <button type="button" class="btn btn-success btn-block" onclick="app.addSlotElements(this)"
            data-action="date.addDateBlock"
            data-props='{{json_encode([
                'dateType'     => $dateType,
                'entity'       => $entity,
                'entityType'   => $entityType
            ])}}'
            data-container="#specific_dates_wrapper">
            <i class="fa fa-plus"></i> {{__('Add More ')}}
            </button>
        </div>
    </div>
</div>  --}}
<div class="container">
    <div id="specific_dates_wrapper" class="row">
        @include('back.dates._partials.date-block')
    </div>
</div>
