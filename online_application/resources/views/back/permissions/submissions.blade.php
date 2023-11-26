
<div class="col-md-12">
  <h6> {{__('Submissions')}} - {{__('User has access to')}}</h6>
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'submission[view]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'View Submission',
       'value'         => isset($role) ? $role->hasPermissionTo('view|submission') : 0,
       'default'       =>  1,
       'helper'        => 'View'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'submission[edit]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Edit Submission',
       'value'         => isset($role) ? $role->hasPermissionTo('edit|submission') : 0,
       'default'       => 1,
       'helper'        => 'Edit'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'submission[create]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Create Submission',
       'value'         => isset($role) ? $role->hasPermissionTo('create|submission') : 0,
       'default'       => 1,
       'helper'        => 'Create'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'submission[delete]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Delete Submission',
       'value'         => isset($role) ? $role->hasPermissionTo('delete|submission') : 0,
       'default'       =>  1,
       'helper'        => 'Delete'
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'submission[full]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Full Permissions on Submission',
       'value'         =>  0,
       'default'       =>  1,
       'helper'        => 'Full'
   ])
</div>
<div class="col-md-12">
<h6> {{__('Submissions')}} - {{__('Through Campuses')}}</h6>
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'submission[campusesView]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'View submissions from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesView|submission') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'submission[campusesEdit]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Edit submissions from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesEdit|submission') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'submission[campusesCreate]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Create submissions for other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesCreate|submission') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'submission[campusesDelete]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Delete submissions from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesDelete|submission') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.checkbox',
   [
     'name'            => 'submission[campusesFull]',
       'label'         => false ,
       'class'         => '' ,
       'required'      => false,
       'attr'          => '',
       'helper_text'   => 'Full permissions on submissions from other campuses',
       'value'         =>  isset($role) ? $role->hasPermissionTo('campusesFull|submission') : 0,
       'default'       =>  1,
       'helper'        => ''
   ])
</div>
