<div class="form-check form-check-inline">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" id="group" name="group_semester" onclick="app.showGroups(this)">
        <label class="custom-control-label" for="group">{{__('Cohort')}}</label>
    </div>
</div>

<div class="form-check form-check-inline">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" id="semester" name="group_semester" onclick="app.showSemesters(this)">
        <label class="custom-control-label" for="semester">{{__('Semester')}}</label>
    </div>
</div>