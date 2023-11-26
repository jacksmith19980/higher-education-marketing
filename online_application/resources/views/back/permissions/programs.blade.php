<div class="col-md-12">
  <h6> {{__('Programs')}} - {{__('User has access to')}}</h6>
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'program[view]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'View Program',
       'value'         => isset($role) ? $role->hasPermissionTo('view|program') : 0,
       'default'       =>  1,
       'helper'        => 'View'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'program[edit]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Edit Program',
       'value'         => isset($role) ? $role->hasPermissionTo('edit|program') : 0,
       'default'       =>  1,
       'helper'        => 'Edit'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'program[create]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Create Program',
       'value'         => isset($role) ? $role->hasPermissionTo('create|program') : 0,
       'default'       =>  1,
       'helper'        => 'Create'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'program[delete]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Delete Program',
       'value'         => isset($role) ? $role->hasPermissionTo('delete|program') : 0,
       'default'       =>  1,
       'helper'        => 'Delete'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'program[full]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Full Permissions on Program',
       'value'         =>  0,
       'default'       =>  1,
       'helper'        => 'Full'
   ])
</div>

<div class="col-md-12">
  <h6> {{__('Programs')}} - {{__('Through Campuses')}}</h6>
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'program[campusesView]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'View programs from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesView|program') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'program[campusesEdit]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Edit programs from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesEdit|program') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'program[campusesCreate]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Create programs for other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesCreate|program') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'program[campusesDelete]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Delete programs from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesDelete|program') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'program[campusesFull]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Full permissions on programs from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesFull|program') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>
