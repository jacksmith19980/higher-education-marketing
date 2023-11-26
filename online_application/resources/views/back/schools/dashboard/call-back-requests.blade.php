<div class="card">
    
    <div class="card-body" x-data="callBackRequests()" x-init="init()">
        <div>
            <h4 class="card-title">{{__('Callback Requests')}}</h4>
        </div>
        <div class="comment-widgets scrollable ps-container ps-theme-default ps-active-y" style="height:150px;">
            
            <template v-if="(Object.values(followups)).length" x-for="(followup, index) in Object.values(followups)" :key="index">
                <div class="d-flex flex-row comment-row m-t-1" >
                    <div class="w-100">
                        <h6 class="font-medium" x-text="followup.name"></h6>
                        <span class="d-block text-muted" x-text="followup.phone + ' - ' +followup.date"></span>
                    </div>
                    <div>
                        <span class="badge badge-default text-white" 
                        x-bind:class="{ 
                            'label-danger': followup.status=='scheduled',
                            'label-success': followup.status=='completed',
                            'label-info': followup.status=='pending',
                        }"

                        x-text="followup.status"></span>
                    </div>
                </div>
            </template>

        </div>
    </div>
</div>
<script>
    function callBackRequests(){
        return {
            followups : {},
            init(){
                fetch('{{route('dashboard.widget' , [
                    'widget' => 'callback-requests'
                ])}}')
                .then(response =>response.json())
                .then(response => {
                    this.followups = response.data.followups
                })
            }
        }
    }
</script>


