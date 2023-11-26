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
            @include('back.students.esignature.' .$contract->service . '.actions.' . $contract->status , ['contract' => $contract])
        </td>
    </tr>

</tbody>
