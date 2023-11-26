<div class="row">
    <div class="col-md-6">
        @php
                $data = FieldsHelper::getAvailableDataLists($integrable);
                $data['custom_list'] = 'Custom List';
        @endphp

        @include('back.layouts.core.forms.select',[
            'name'      => 'data',
            'label'     => 'Preset List' ,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => 'onchange=app.dataListChange(this) data-application='.optional($application)->id.' ',
            'data'      => $data,
            'value'     => isset(optional($field)->properties['listName']) ? optional($field)->properties['listName'] : ''
        ])


        @include('back.layouts.core.forms.hidden-input',[
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

    <div class="col-md-1 hidden refresh">
        <label for="wert">Refresh</label>
        <div class="form-group">
            <button class="btn btn-success" type="button" onclick="app.dataListRefresh()">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>
    {{-- @dump(optional($field)->properties) --}}
    <div class="col-md-5 sync_isSynced">
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

    @if (isset($field->properties['sync']['isSynced']))
        @if ($field->properties['listName'] != 'custom_field')
           @include('back.applications._partials.fields-sync-settings' , [
           'application' => $application,
           'fields'      => app('App\Http\Controllers\Tenant\FieldController')->getApplicationFileds($application),
           'field'       => optional($field)
       ])
       @endif
   @endif

   @php
        $showClass = ( isset(optional($field)->properties['listName']) && optional($field)->properties['listName'] == 'custom_list') ? ' ' : 'add-values';
   @endphp

   <div class="col-md-6  {{$showClass}}" style="padding-top: 30px">
       <a href="javascript:void(0)" class="btn btn-info"
           onclick="app.repeatElement('field.list' , 'custom_list_wrapper')">Add Value</a>
   </div>

</div>

<div class="sync-settings-wrapper row"></div>{{-- sync-settings-wrapper --}}
<div class="custom_list_wrapper" onclick="app.manageCustomList();"
       style="max-height:80vh;height:400px;width:100%;display:block;overflow-y:scroll;padding:30px;">

    @if (isset($field->properties['listName']) && $field->properties['listName'] == 'custom_field')
        @include('back.applications._partials.customfields-sync-settings', [
            'application' => $application,
            'fields'      => app('App\Http\Controllers\Tenant\FieldController')->getApplicationFileds($application)
        ])
    @endif

   @if (optional($field)->data)
       @include('back.applications.fields.list.custom_list' , ['items' => optional($field)->data])
   @endif

   @if (isset(optional($field)->properties['listName']) && optional($field)->properties['listName'] == 'mautic_custom_field')
       @include('back.applications.fields.list.mautic_custom_field', [
           'items' => FieldsHelper::getListData(optional($field)->properties['listName'], $integrable),
           'selected' => optional($field)->properties['mautic']['custom']
       ])
   @endif

</div><!-- custom_list_wrapper -->
