<div class="card" style="min-height: 483px;">
    <div class="card-body" x-data="Applications()" x-init="init()">
        <div class="p-t-20 text-center">
            <i class="mdi mdi-account-card-details display-4 text-orange d-block"></i>
            <template x-if="data.total">
                <span class="display-4 d-block font-medium" x-text="data.total"></span>
            </template>

            <span>{{__('Total Applications')}}</span>
            <!-- Progress -->

            <div class="progress m-t-40" style="height:4px;">

                <div class="progress-bar bg-info progress-complete" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>

                <div class="progress-bar bg-orange progress-incomplete" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>


            </div>

            <!-- Progress -->
            <!-- row -->
            <div class="row m-t-30 m-b-20">

                <template x-if="data.complete">
                    <!-- column -->
                    <div class="col-6 border-right text-center">
                        <h3 class="m-b-0 font-medium" x-text="data.complete.count"></h3>{{__('Complete')}}</div>
                    <!-- column -->
                </template>

                <template x-if="data.incomplete">
                <div class="col-6">
                    <h3 class="m-b-0 font-medium" x-text="data.incomplete.count"></h3>
                    {{__('Incomplete')}}</div>
                </template>

            </div>
            <a href="/students" class="waves-effect waves-light m-t-20 btn btn-lg btn-info accent-4 m-b-20">{{__('VIEW DETAILS')}}</a>
        </div>
    </div>
</div>

<script>
    function Applications(){
        return {
            data : {},
            init(){
                fetch('{{route('dashboard.widget' , [
                    'widget' => 'applications'
                ])}}')
                .then(response => response.json())
                .then(response => {
                    this.data = response.data
                    $('.progress-complete').css({
                        'width' :  this.data.complete.percentage  + '%'
                    })
                    $('.progress-incomplete').css({
                        'width' : this.data.incomplete.percentage  +  '%'
                    })
                })
            }
        }
    }
</script>
