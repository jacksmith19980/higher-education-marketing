<form method="post" action="{{ route('settings.storeDegrees') }}" class="needs-validation" novalidate=""
        enctype="multipart/form-data">
        @csrf
<div class="col-md-10" style="padding: 0;">
    <div class="card no-padding card-border">
        <div class="card-header">
            <h4 class="card-title">{{__('Degrees\\Program types')}}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xlg-3 offset-xlg-9 col-lg-4 offset-lg-8 m-b-30 col-md-4 offset-md-8 col-sm-5 offset-sm-7 col-xs-6 offset-xs-6">
                    @include('back.layouts.core.helpers.add-elements-button' , [
                        'text'          => 'Add Degree',
                        'action'        => 'setting.addDegree',
                        'container'     => '#degrees-types',
                        'disabled'      => $disabled
                    ])
                </div>
            </div>

            <div class="row" id="degrees-types">
                @if (isset($settings['education']['degrees']))
                    @foreach ($settings['education']['degrees'] as $label => $value)
                        @include('back.settings.education._partials.degree-row' , [
                            'label'     => $label,
                            'value'     => $value,
                            'disabled'  => $disabled
                        ])
                    @endforeach
                @endif
            </div>
            <!--  <input type="submit" name="submit" value="Save"> -->
                <button class="float-right btn btn-success btn-add-schedule" {{$disabled}}>{{__('Save')}}</button>
        </div>

    </div>
</div>
</form>
