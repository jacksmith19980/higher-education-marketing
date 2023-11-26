
@if($date->date_type == 'single-day')

        <td>{{$date->properties['date']}}</td>
        <td>{{$date->properties['start_time']}}</td>
        <td>{{$date->properties['end_time']}}</td>
        <td>{{$date->properties['date_price']}}{{$settings['school']['default_currency']}}</td>
        <td>{{ ($date->completed) ? 'Completed' : 'Available' }}</td>

@else

<td>
    <span style="font-size: 12px">
        {{$date->properties['start_date']}}
    </span>
</td>
<td>
    <span style="font-size: 12px">
        {{$date->properties['end_date']}}
    </span>
</td>
<td>
    @if(isset($date->properties['date_schudel']))
        @php
            $schedule = App\Tenant\Models\Schedule::find($date->properties['date_schudel']);
        @endphp
        <span style="font-size: 12px">
            {{$schedule->label.' '.date('g:i',strtotime($schedule->start_time)).' - '.date('g:i',strtotime($schedule->end_time))}}
        </span>
    @endif
</td>
<td>
    <span style="font-size: 12px">
        {{$date->properties['date_price']}}
    </span>
</td>

<td>{{ ($date->completed) ? 'Completed' : 'Available' }}</td>

<td>
    @php
    $buttons = [
        ["text"=>"Edit", "icon"=>"icon-pencil", "class"=>"", "url"=>"javascript:editCourseDatesPrices($course->id, $date->id)"]
    ]
    @endphp
    <div class="btn-group more-optn-group float-right">
        <button type="button"
        class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt flat-btn"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

        <div class="dropdown-menu">
            @foreach ($buttons as $button)
                    <a class="dropdown-item {{$button['class']}}" href="{{$button['url']}}">
                        <i class="{{$button['icon']}}"></i> <span  class="icon-text">{!!__($button['text'])!!}</span>
                    </a>
            @endforeach
        </div>
    </div>
</td>
@endif
<td>
    @php
        if(!$date->completed){
            $buttons['complete'] = [
                'text' => 'Mark as Completed',
                'icon' => 'icon-graph ',
                'attr' => "onclick=app.editDateStatus(this) data-date=" .$date->id." data-status=complete data-course=" .$course->id,
                'class' => '',
            ];
        }else{
            $buttons['complete'] = [
                'text' => 'Mark as Available',
                'icon' => 'icon-graph ',
                'attr' => "onclick=app.editDateStatus(this) data-date=" .$date->id." data-status=available data-course=" .$course->id,
                'class' => '',
            ];
        }
        $buttons['edit'] = [
                'text' => 'Edit',
                'icon' => 'icon-note',
                'attr' => "onclick=app.editCourseProp(this) data-route=".route('date.edit',['course' => $course->id , 'date'  => $date->id]) . " data-title=Edit&nbsp;Date",
                'class' => '',
        ];
        $buttons['delete'] = [
                'text' => 'Delete',
                'icon' => 'icon-trash text-danger',
                'attr' => "onclick=app.deleteCourseProp(this) data-delete-route=".route('date.delete',['course' => $course->id , 'date'  => $date->id]),
                'class' => '',
            ];
    @endphp
    <div class="text-right">
        @include('back.layouts.core.helpers.table-actions' , [
            'buttons'=> $buttons,
            'title' => null
        ])
    </div>
</td>
