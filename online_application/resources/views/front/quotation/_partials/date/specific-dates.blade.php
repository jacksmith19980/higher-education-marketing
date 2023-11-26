@foreach ($lists['programs'] as $programId => $list)

    @php

        $title = (isset($programsList[$programId]) && count($programsList) > 1) ? "(".$programsList[$programId].")" : '';

    @endphp

    <div class="card card-body bg-light-grey">

        <div id="date-wrapper">

            <div class="form-group">

                @include('back.layouts.core.forms.checkbox-group',

                [

                        'name'          => "date[$programId][]",

                        'label'         => __("Which weeks would you like to book $title?") ,

                        'class'         => 'quotationDateSelect' ,

                        'required'      => true,

                        'attr'          => 'onchange=app.dateSelected(this) data-quotation='.$quotation->id." data-program=$programId",

                        'value'         => '',

                        'placeholder'   => __('Select start dates'),

                        'data'          => $list,

                        ])

            </div>

        </div>

        <div class="addons-wrapper"></div>

    </div>



@endforeach

