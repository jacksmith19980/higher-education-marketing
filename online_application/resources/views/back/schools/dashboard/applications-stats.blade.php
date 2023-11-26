<div class="card">
    
    <div class="card-body" x-data="applicationsStats()" x-init="init()">
        <div>
            <h4 class="card-title">{{__('Appication Sources')}}</h4>
        </div>
        <div class="comment-widgets ps-container ps-theme-default ps-active-y">
            
            <div id="nested-pie" style="height:400px;"></div>
            
            {{-- <template v-if="(Object.values(followups)).length" x-for="(followup, index) in Object.values(followups)" :key="index">
                <div class="d-flex flex-row comment-row m-t-1" >
                    <div class="w-100">
                        <h6 class="font-medium" x-text="followup.name"></h6>
                        <span class="d-block text-muted" x-text="followup.phone + ' - ' +followup.date"></span>
                    </div>
                    <div>
                        <span class="label label-rounded" 
                        x-bind:class="{ 
                            'label-danger': followup.status=='scheduled',
                            'label-success': followup.status=='completed',
                            'label-info': followup.status=='pending',
                        }"

                        x-text="followup.status"></span>
                    </div>
                </div>
            </template> --}}

        </div>
    </div>
</div>
<script>
    function applicationsStats(){
        return {
            followups : {},
            init(){
                this.chart();
                /* fetch('{{route('dashboard.widget' , [
                    'widget' => 'applications-stats'
                ])}}')
                .then(response =>response.json())
                .then(response => {
                    console.log(response.data)
                    //this.followups = response.data.followups
                }) */
            },
            chart(){
                    console.log("chart");
                    var nestedChart = echarts.init(document.getElementById('nested-pie'));
                    
        var option = {
            
           /* tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                }, */

                // Add legend
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data: ['Italy','Spain','Austria','Germany','Poland','Denmark','Hungary','Portugal','France','Netherlands']
                },

                // Add custom colors
                color: ['#ffbc34', '#4fc3f7', '#212529', '#f62d51', '#2962FF'],

                // Display toolbox
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    feature: {
                        mark: {
                            show: true,
                            title: {
                                mark: 'Markline switch',
                                markUndo: 'Undo markline',
                                markClear: 'Clear markline'
                            }
                        },
                        dataView: {
                            show: true,
                            readOnly: false,
                            title: 'View data',
                            lang: ['View chart data', 'Close', 'Update']
                        },
                        magicType: {
                            show: true,
                            title: {
                                pie: 'Switch to pies',
                                funnel: 'Switch to funnel',
                            },
                            type: ['pie', 'funnel']
                        },
                        restore: {
                            show: true,
                            title: 'Restore'
                        },
                        saveAsImage: {
                            show: true,
                            title: 'Same as image',
                            lang: ['Save']
                        }
                    }
                },

                // Enable drag recalculate
                calculable: false,

                // Add series
                series: [

                    // Inner
                    {
                        name: 'Countries',
                        type: 'pie',
                        selectedMode: 'single',
                        radius: [0, '40%'],

                        // for funnel
                        x: '15%',
                        y: '7.5%',
                        width: '40%',
                        height: '85%',
                        funnelAlign: 'right',
                        max: 1548,

                        itemStyle: {
                            normal: {
                                label: {
                                    position: 'inner'
                                },
                                labelLine: {
                                    show: false
                                }
                            },
                            emphasis: {
                                label: {
                                    show: true
                                }
                            }
                        },

                        data: [
                            {value: 535, name: 'Italy'},
                            {value: 679, name: 'Spain'},
                            {value: 1548, name: 'Austria'}
                        ]
                    },

                    // Outer
                    {
                        name: 'Countries',
                        type: 'pie',
                        radius: ['60%', '85%'],

                        // for funnel
                        x: '55%',
                        y: '7.5%',
                        width: '35%',
                        height: '85%',
                        funnelAlign: 'left',
                        max: 1048,

                        data: [
                            {value: 535, name: 'Italy'},
                            {value: 310, name: 'Germany'},
                            {value: 234, name: 'Poland'},
                            {value: 135, name: 'Denmark'},
                            {value: 948, name: 'Hungary'},
                            {value: 251, name: 'Portugal'},
                            {value: 147, name: 'France'},
                            {value: 202, name: 'Netherlands'}
                        ]
                    }
                ]
        };    
       
    
        nestedChart.setOption(option); 
                  
            }
        }
    }
</script>


