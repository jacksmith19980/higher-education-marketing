<tr data-student-id="{{$agentStudent->id}}">
    <td class="">
        <a class="d-block" target="_blank" href="{{route('agent.student.show', ['school' => $school, 'student' => $agentStudent])}}">
            {{$agentStudent->name}}
        </a>
        <small>{{$agentStudent->email}}</small>
    </td>
    <td>
       @include('front.agent._partials.student-submissions')
    </td>
    <td class="min-column norm-column">{{isset($agentStudent->admission_stage) ? $agentStudent->admission_stage : 'N/A'}}</td>
    <td class="min-column">{{$agentStudent->updated_at->diffForHumans()}}</td>
    <td class="min-column">{{$agentStudent->created_at->diffForHumans()}}</td>
    <td class="cta-column control-column">
        @include('back.layouts.core.helpers.table-actions' , [
            'buttons'=> [
                   'delete' => [
                        'text' => 'Delete',
                        'icon' => 'icon-trash text-danger',
                        'attr' => 'onclick=app.deleteElement("'.route('students.destroy' , $agentStudent).'","","data-student-id")',
                        'class' => '',
                   ],
            ]
        ])
       <!-- <div class="btn-group more-optn-group">
            <button type="button"
             class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt"
             data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item " href="javascript:void(0)"
                    onclick="app.redirect('https://application.crmforschools.net/applications/demande-dadmissions-pour-le-diplome-a-temps-plein/edit')">
                    <i class="flat-cion flaticon-eye"></i><span class="icon-text pl-2">View</span>
                </a>

                <a class="dropdown-item " href="javascript:void(0)"
                    onclick="app.deleteElement()">
                    <i class="flat-cion flaticon-edit-1"></i><span class="icon-text pl-2">Edit</span>
                </a>
                <a class="dropdown-item " href="javascript:void(0)"
                 onclick="app.redirect('https://application.crmforschools.net/applications/demande-dadmissions-pour-le-diplome-a-temps-plein/clone')">

                 <i class="icon-trash"></i><span class="icon-text pl-2">Delete</span>
                </a>
            </div>
       </div> -->
    </td>
 </tr>
