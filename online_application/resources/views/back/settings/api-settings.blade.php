<h4 class="m-b-15">API Settings</h4>

<form method="post" action="{{route('settings.store')}}" class="needs-validation" novalidate="" enctype="multipart/form-data">
    <div class="row">
        @csrf
        @include('back.layouts.core.forms.hidden-input',
        [
            'name'          => 'group',
            'label'         => '' ,
            'class'         => '',
            'required'      => false,
            'attr'          => '',
            'value'         => 'api',
        ])
    </div>

    <div class="row">

        <div class="col-md-12">
            <button class="btn btn-success float-right">Save</button>
        </div>
        
    </div>

    

</form>