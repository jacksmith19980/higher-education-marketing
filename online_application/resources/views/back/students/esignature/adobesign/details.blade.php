<tbody>
    <tr>
        <td>
            {{isset($contract->title) ? $contract->title : __('Contract')}}
        </td>
        <td>
            {{ucwords($contract->status)}}
        </td>
        <td>
            {{date('Y-m-d H:i:s' , strtotime($contract->properties['statusDateTime']))}}
        </td>
        <td>

            @php
                $view = 'back.students.esignature.' .$contract->service . '.actions.' . strtolower($contract->status);
            @endphp
            @if(View::exists($view))
                @include( $view , ['contract' => $contract])
            @else
                @include( 'back.students.esignature.' .$contract->service . '.actions.all', ['contract' => $contract])
            @endif
        </td>
    </tr>

</tbody>
