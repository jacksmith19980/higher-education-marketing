<form method="post" action="{{route('settings.addSchedule')}}" class="needs-validation" novalidate=""
      enctype="multipart/form-data">
    <div class="row">
        @csrf
        <div class="col-12" id="schedule_wrapper">
            @include('back.courses._partials.schedule.create-schedule')
        </div>
    </div>
</form>