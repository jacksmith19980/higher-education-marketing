<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-info handle">
            <h4 class="m-b-0 text-white">{{__('Thank You Page')}}</h4>
        </div>
        <div class="card-body row">
            @include('back.recruitment_assistant._partials.thank_you.general')
        </div>
    </div>


    <div class="card">
        <div class="card-header bg-info handle">
            <h4 class="m-b-0 text-white">{{__('Thank You Email')}}</h4>
        </div>
        <div class="card-body row">
            @include('back.recruitment_assistant._partials.thank_you.email')
        </div>
    </div>
</div>