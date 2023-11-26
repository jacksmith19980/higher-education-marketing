<tbody>
    <tr>
        <td>
            {{isset($contract->title) ? $contract->title : __('Contract')}}
        </td>
        <td>
            {{ucwords($contract->status)}}
        </td>
        <td>

            {{ isset($contract->created_at) ?  date('Y-m-d H:i:s' , strtotime($contract->created_at)) : ''}}
        </td>
        <td>
            @include('back.students.esignature.' .$contract->service . '.actions.' . $contract->status , ['contract' => $contract])
        </td>
    </tr>

</tbody>
