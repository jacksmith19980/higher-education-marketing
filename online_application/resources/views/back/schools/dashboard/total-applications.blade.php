<div class="col-lg-6 col-md-6 col-sm-12 draggable">
    <div class="card new-card card-hover">
        <div class="card-body">
            <div class="card-content my-auto">
                <div class="text-center">
                    <i class="flaticon-trophy display-4 pr-2"></i>
                    <span class="display-4 font-medium text-dblue" id="total_completions" x-text="data.total"></span>
                    <span class="d-block pt-3" style="font-size:1.25rem;">{{__('Total Applications')}}</span>
                    <!-- Progress -->

                    <div class="progress m-t-40" style="height:4px;">
                        <div class="progress-bar bg-orange progress-complete" role="progressbar"
                             aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar bg-yellow progress-incomplete" role="progressbar"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <!-- Progress -->
                    <!-- row -->
                    <div class="row m-t-30 m-b-20">
                        <!-- column -->
                        <div class="col-6 border-right text-center">
                            <h3 class="m-b-0 font-medium" id="complete" x-text="20"></h3>
                            {{__('Complete')}}
                        </div>
                        <!-- column -->
                        <div class="col-6">
                            <h3 class="m-b-0 font-medium" id="incomplete" x-text="80"></h3>
                            {{__('Incomplete')}}
                        </div>
                    </div>

                    <a href="{{ route('submissions.index') }}"
                       class="waves-effect waves-light m-t-20 btn btn-lg btn-primary accent-4 m-b-20 text-white align-self-center">
                        {{__('VIEW DETAILS')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>