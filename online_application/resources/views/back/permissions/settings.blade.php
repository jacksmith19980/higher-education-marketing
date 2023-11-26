<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'settings[view]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'View Settings',
       'value'         => isset($role) ? $role->hasPermissionTo('view|settings') : 0,
       'default'       =>  1,
       'helper'        => 'View'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'settings[edit]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Edit Settings',
       'value'         => isset($role) ? $role->hasPermissionTo('edit|settings') : 0,
       'default'       =>  1,
       'helper'        => 'Edit'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'settings[full]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Full Permissions on Settings',
       'value'         =>  0,
       'default'       =>  1,
       'helper'        => 'Full'
   ])
</div>