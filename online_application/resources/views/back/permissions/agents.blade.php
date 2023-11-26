<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'agent[view]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'View Agent',
       'value'         => isset($role) ? $role->hasPermissionTo('view|agent') : 0,
       'default'       =>  1,
       'helper'        => 'View'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'agent[edit]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Edit Agent',
       'value'         => isset($role) ? $role->hasPermissionTo('edit|agent') : 0,
       'default'       =>  1,
       'helper'        => 'Edit'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'agent[create]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Create Agent',
       'value'         => isset($role) ? $role->hasPermissionTo('create|agent') : 0,
       'default'       =>  1,
       'helper'        => 'Create'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'agent[delete]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Delete Agent',
       'value'         => isset($role) ? $role->hasPermissionTo('delete|agent') : 0,
       'default'       =>  1,
       'helper'        => 'Delete'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'agent[full]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Full Permissions on Agent',
       'value'         =>  0,
       'default'       =>  1,
       'helper'        => 'Full'
   ])
</div>