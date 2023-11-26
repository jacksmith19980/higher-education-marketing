<div class="card">
    <div class="card-body" x-data="recentApplicants()" x-init="init()">
        <div>
        <h4 class="card-title">{{__('Recent Accounts')}}</h4>
        </div>
        <div class="comment-widgets scrollable ps-container ps-theme-default ps-active-y" style="height:150px;">
            <template v-if="(Object.values(applicants)).length" x-for="(applicant, index) in Object.values(applicants)" :key="index">

                <div class="d-flex flex-row comment-row m-t-1 justify-content-between">
                    
                    <div class="d-block">
                        <a :href="/students/ + applicant.id ">
                            <h6 class="font-medium" x-text="applicant.name"></h6>
                        </a>
                        <span class="text-muted" x-text="applicant.created_at"></span>
                    </div>
                    
                    <template x-if="applicant.submissions[0]">
                        <div>
                            <span class="badge badge-default badge-success text-white" x-text="{{__('applicant.submissions[0].status')}}"></span>
                        </div>
                    </template>

                    <template x-if="!applicant.submissions[0]">
                        <div>
                            <span class="badge badge-default badge-danger text-white d-block">{{__('No Applications')}}</span>
                        </div>
                    </template>
                </div>
            </template>
       </div>
    </div>
</div>
<script>
    function recentApplicants(){
        return {
            applicants : {},
            init(){
                fetch('{{route('dashboard.widget' , [
                    'widget' => 'recent-applicants'
                ])}}')
                .then(response =>response.json())
                .then(response => {
                    this.applicants = response.data.applicants
                          
                })
            }
        }
    }
</script>


