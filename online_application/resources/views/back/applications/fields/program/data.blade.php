<div class="row">
    <div class="col-md-12">
        @php
            $data = App\Helpers\Application\FieldsHelper::getProgramsDataList();
        @endphp
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'data',
            'label'     => 'Preset List' ,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => 'onchange=app.dataListChange(this)',
            'data'      => $data,
            'value'     => isset(optional($field)->properties['listName']) ? optional($field)->properties['listName'] : ''
        ])

        @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'properties[listName]',
            'label'     => 'Type' ,
            'class'     =>'ajax-form-field list_name' ,
            'required'  => true,
            'attr'      => '',
            'value'     => isset(optional($field)->properties['listName']) ? optional($field)->properties['listName'] : ""
        ])

        @include('back.layouts.core.forms.hidden-input',[
            'name'      => 'properties[isCustomized]',
            'label'     => 'Customized?' ,
            'class'     =>'ajax-form-field list_name' ,
            'required'  => true,
            'attr'      => '',
            'value'     => isset(optional($field)->properties['isCustomized']) ? optional($field)->properties['isCustomized'] : ""
        ])

    </div>
    <div class="col-md-6 sync_with_campus hide">
        @include('back.layouts.core.forms.checkbox',
        [
            'name'          => 'properties[sync_isSynced]',
            'label'         => 'Sync.' ,
            'class'         => 'ajax-form-field sync_field' ,
            'required'      => false,
            'attr'          => 'onchange=app.syncWithSettings(this) data-application='.optional($application)->id.' ',

            'helper_text'   => 'Synchronize',
            'value'         =>  isset(optional($field)->properties['sync']['isSynced'])? optional($field)->properties['sync']['isSynced'] : false,
            'default'       => true,
        ])
    </div>

    @php
        $showClass = (isset(optional($field)->properties['listName']) && (optional($field)->properties['listName'] == 'custom_list')) ? ' ' : 'add-values';
    @endphp

    <div class="col-md-6  {{$showClass}}" style="padding-top: 30px">

        <a href="javascript:void(0)" class="btn btn-info" onclick="app.repeatElement('field.list' , 'custom_list_wrapper')">Add Value</a>

    </div>

</div>

<div class="sync-settings-wrapper row"></div>{{-- sync-settings-wrapper --}}

<div class="custom_list_wrapper" style="max-height:80vh;height:400px;width:100%;display:block;overflow-y:scroll;padding:30px;">

    @if (optional($field)->data)
        @include('back.applications.fields.list.custom_list' , ['items' => optional($field)->data   ])
    @endif

</div><!-- custom_list_wrapper -->
