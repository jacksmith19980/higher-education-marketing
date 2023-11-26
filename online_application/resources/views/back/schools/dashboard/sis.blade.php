<div class="col-lg-6 col-md-6 col-sm-12 draggable">
    <div class="card new-card card-hover">
        <div class="card-body">
            <div class="d-flex card-header flex-row justify-content-between pl-0 pr-2">
                <h4 class="card-title"><i class="icon-graduation pr-3 pr-2 display-8"></i>SIS</h4>
            </div>
            <div class="card-content">
                <div class="data-row">
                    <div class="d-flex justify-content-between align-items-center pl-3 pr-3">
                        <div class="d-block">
                            <a href="{{route('instructors.index')}}">
                                <h6 class="font-medium" x-text="applicant.name">
                                    <i class="mdi mdi-glasses pr-3 text-primary"></i>{{__('Instructors')}}
                                </h6>
                            </a>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pl-3 pr-3">
                        <div class="d-block">
                            <a href="{{route('classrooms.index')}}">
                                <h6 class="font-medium" x-text="applicant.name">
                                    <i class="ti-view-grid pr-3 text-primary"></i>{{__('Classrooms')}}
                                </h6>
                            </a>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pl-3 pr-3">
                        <div class="d-block">
                            <a href="{{route('lessons.index')}}">
                                <h6 class="font-medium" x-text="applicant.name">
                                    <i class=" icon-pencil pr-3 text-primary"></i>{{__('Lessons')}}
                                </h6>
                            </a>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pl-3 pr-3"
                         style="border-bottom:1px solid #ccc !important;">
                        <div class="d-block">
                            <a href="{{route('groups.index')}}">
                                <h6 class="font-medium" x-text="applicant.name">
                                    <i class="icon-people pr-3 text-primary"></i>{{__('Cohorts')}}
                                </h6>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>