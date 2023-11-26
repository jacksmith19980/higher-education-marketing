<div class="col-lg-6 col-md-6 col-sm-12 draggable">
    <div class="card new-card card-hover">
        <div class="card-body">
            <div class="card-header">
                <h4 class="card-title"><i class="icon-envelope-open pr-2 display-8"></i>{{__('Contacts')}}</h4>
            </div>
            <div class="card-content">
                <form action="{{route('contacts.search')}}" method="GET">
                    <div class="mb-4" style="max-width:300px;">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text ps-theme-default"><i class="ti-search"></i></span>
                            </div>
                            <input type="text" class="form-control" aria-label=""
                                   placeholder="{{__('Search Contacts Here')}}..." name="search">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit"><i class="ti-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="scrollable ps-container ps-theme-default content-row p-0 data-row">
                    <div class="d-flex justify-content-between align-items-center pl-3 pr-3">
                        <div class="d-block">
                            <a href="{{ route('students.leads') }}">
                                <h6 class="font-medium" x-text="applicant.name">
                                    <i class="icon-user pr-3 text-primary"></i>{{__('Leads')}}
                                </h6>
                            </a>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pl-3 pr-3">
                        <div class="d-block">
                            <a href="{{ route('students.index') }}">
                                <h6 class="font-medium" x-text="applicant.name">
                                    <i class="icon-people pr-3 text-primary"></i>{{__('Applicants')}}
                                </h6>
                            </a>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pl-3 pr-3">
                        <div class="d-block">
                            <a href="{{ route('students.index', ['type'=> 'student']) }}">
                                <h6 class="font-medium" x-text="applicant.name">
                                    <i class="icon-graduation pr-3 text-primary"></i>{{__('Students')}}
                                </h6>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
