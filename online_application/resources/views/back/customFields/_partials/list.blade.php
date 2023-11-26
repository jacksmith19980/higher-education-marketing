<div class="row">
    <div class="col-md-2 offset-10 m-b-30">
        @include('back.layouts.core.helpers.add-elements-button' , [
            'text'          => 'Add Values',
            'action'        => 'customfield.addCustomfieldListValue',
            'container'     => '#customfield-list-data',
            'count'         => isset($program->properties['intake_date']) ? count($program->properties['intake_date']) : 0
        ])
    </div>
</div>

<div class="row" id="customfield-list-data">

</div>