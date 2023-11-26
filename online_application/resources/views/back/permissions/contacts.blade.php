
<div class="col-md-12">
  <h6> {{__('Contacts')}} - {{__('User has access to')}}</h6>
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'contact[view]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'View Contacts',
       'value'         => isset($role) ? $role->hasPermissionTo('view|contact') : 0,
       'default'       =>  1,
       'helper'        => 'View'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'contact[edit]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Edit Contacts',
       'value'         => isset($role) ? $role->hasPermissionTo('edit|contact') : 0,
       'default'       => 1,
       'helper'        => 'Edit'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'contact[create]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Create Contacts',
       'value'         => isset($role) ? $role->hasPermissionTo('create|contact') : 0,
       'default'       => 1,
       'helper'        => 'Create'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'contact[delete]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Delete Contacts',
       'value'         => isset($role) ? $role->hasPermissionTo('delete|contact') : 0,
       'default'       =>  1,
       'helper'        => 'Delete'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'contact[full]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Full Permissions on Contacts',
       'value'         =>  0,
       'default'       =>  1,
       'helper'        => 'Full'
   ])
</div>
<div class="col-md-12">
<h6> {{__('Contacts')}} - {{__('Through Campuses')}}</h6>
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'contact[campusesView]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'View contacts from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesView|contact') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'contact[campusesEdit]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Edit contacts from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesEdit|contact') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'contact[campusesCreate]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Create contacts for other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesCreate|contact') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'contact[campusesDelete]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Delete contacts from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesDelete|contact') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'contact[campusesFull]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Full permissions on contacts from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesFull|contact') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>
