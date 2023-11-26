@extends('back.layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body wizard-content">
                    <h4 class="card-title">{{__('Edit Campus')}} - {{$campus->title}}</h4>
                    <hr>

                    <form method="POST" action="{{ route('campuses.update' , $campus) }}" class="validation-wizard wizard-circle m-t-40"
                          aria-label="{{ __('Update Campus') }}" data-add-button="{{__('Update Campus')}}"  enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Step 1 -->
                        <h6>Campus Information</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'title',
                                        'label'     => 'Campus' ,
                                        'class'     =>'' ,
                                        'required'  => true,
                                        'attr'      => '',
                                        'value'     => isset($campus->title) ? $campus->title : ''
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'slug',
                                        'label'     => 'Slug/Code' ,
                                        'class'     =>'' ,
                                        'required'  => true,
                                        'attr'      => '',
                                        'value'     => isset($campus->slug) ? $campus->slug : ''
                                    ])
                                </div>
                            </div> <!-- row -->
                        </section>

                        <!-- Step 2 -->
                        <h6>Campus Details</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'properties[address]',
                                        'label'     => 'Address' ,
                                        'class'     => '',
                                        'required'  => false,
                                        'attr'      => '',
                                        'value'     => isset($campus->properties['address']) ? $campus->properties['address'] : ''
                                    ])
                                </div>

                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'properties[province]',
                                        'label'     => 'Province' ,
                                        'class'     => '',
                                        'required'  => false,
                                        'attr'      => '',
                                        'value'     => isset($campus->properties['province']) ? $campus->properties['province'] : ''
                                    ])
                                </div>
                            </div> <!-- row -->

                            <div class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'properties[city]',
                                        'label'     => 'City' ,
                                        'class'     => '',
                                        'required'  => false,
                                        'attr'      => '',
                                        'value'     => isset($campus->properties['city']) ? $campus->properties['city'] : ''
                                    ])
                                </div>

                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'properties[zip]',
                                        'label'     => 'Postal Code' ,
                                        'class'     => '',
                                        'required'  => false,
                                        'attr'      => '',
                                        'value'     => isset($campus->properties['zip']) ? $campus->properties['zip'] : ''
                                    ])
                                </div>
                            </div> <!-- row -->

                            <div class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.select',
                                    [
                                        'name'          => 'properties[country]',
                                        'label'         => 'Country' ,
                                        'class'         => '',
                                        'required'      => false,
                                        'attr'          => '',
                                        'value'         => isset($campus->properties['country']) ? $campus->properties['country'] : '',
                                        'placeholder'   => 'Select Country',
                                        'data'          => \App\Helpers\Application\FieldsHelper::getListData('mautic_countries')
                                    ])
                                </div>

                            </div> <!-- row -->
                        </section>

                        @features(['virtual_assistant'])
                            <!-- Step 3 -->
                            <h6>Campus Virtual Assistant</h6>
                            <section>
                            <div class="row">
                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.html', [
                                        'name' => 'properties[short_description]',
                                        'label' => 'Short Description' ,
                                        'class' => '' ,
                                        'required' => false,
                                        'attr' => '',
                                        'value' => isset($campus->properties['short_description']) ? $campus->properties['short_description'] : ''
                                    ])
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.html', [
                                        'name' => 'details',
                                        'label' => 'Details' ,
                                        'class' => '' ,
                                        'required' => false,
                                        'attr' => '',
                                        'value' => isset($campus->details) ? $campus->details : ''
                                    ])
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'properties[campus_location]',
                                        'label'     => 'Campus Location' ,
                                        'class'     =>'' ,
                                        'required'  => false,
                                        'attr'      => '',
                                        'value'     => isset($campus->properties['campus_location']) ? $campus->properties['campus_location'] : ''
                                    ])
                                </div>
                            </div> <!-- row -->

                            <div class="row">
                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'properties[video]',
                                        'label'     => 'Video' ,
                                        'class'     =>'' ,
                                        'required'  => false,
                                        'attr'      => '',
                                        'value'     => isset($campus->properties['video']) ? $campus->properties['video'] : ''
                                    ])
                                </div>
                            </div> <!-- row -->

                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="list-unstyled">
                                        <li class="media">

                                            @if (
                                                    isset($campus->properties['featured_image']) &&
                                                    is_array($campus->properties['featured_image']) &&
                                                    count($campus->properties['featured_image']) > 0
                                                )
                                                <img class="d-flex m-r-15" src="{{ Storage::disk('s3')->temporaryUrl($campus->properties['featured_image']['path'] , \Carbon\Carbon::now()->addMinutes(5)) }}"
                                                     alt="Generic placeholder image" width="120">
                                            @endif
                                            <div class="media-body">
                                                <div class="form-group">
                                                    @include('back.layouts.core.forms.file-input', [
                                                        'name'     => 'featured_image',
                                                        'label'    => 'Featured Image' ,
                                                        'class'    => '',
                                                        'required' => false,
                                                        'attr'     => '',
                                                        'value'    => '',
                                                    ])
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.text-area',
                                    [
                                        'name'      => 'properties[virtual_tour]',
                                        'label'     => '360 Virtual Tour' ,
                                        'class'     =>'' ,
                                        'required'  => false,
                                        'attr'      => '',
                                        'value'     => isset($campus->properties['virtual_tour']) ? $campus->properties['virtual_tour'] : ''
                                    ])
                                </div>
                            </div> <!-- row -->
                        </section>
                        @endfeatures
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
