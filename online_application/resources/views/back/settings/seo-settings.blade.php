{{--SEO Settings--}}
<div class="col-md-10">
    <div class="card no-padding card-border">
        <div class="card-header">
            <h4 class="card-title">{{__('SEO Settings')}}</h4>
        </div>
        <div class="card-body" style="border:1px solid #f7f7f7;">
            <div class="row">
                <div class="col-md-12">
                    @include('back.layouts.core.forms.text-input', [
                        'name' => 'title',
                        'label' => 'Title' ,
                        'class' => '',
                        'required' => false,
                        'attr' => $disabled,
                        'value' => isset($settings['branding']['title'])? $settings['branding']['title'] : ''
                    ])
                </div>
                <div class="col-md-12">
                    @include('back.layouts.core.forms.text-area', [
                        'name' => 'description',
                        'label' => 'Description' ,
                        'class' => '',
                        'required' => false,
                        'attr' => '',
                        'value' => isset($settings['branding']['description'])? $settings['branding']['description'] : ''
                    ])
                </div>
                <div class="col-md-12">
                    @include('back.layouts.core.forms.text-input', [
                        'name' => 'keywords',
                        'label' => 'Keywords' ,
                        'class' => '',
                        'required' => false,
                        'attr' => '',
                        'value' => isset($settings['branding']['keywords'])? $settings['branding']['keywords'] : '',
                        'helper' => 'Used to make possible for peapole to find your site via search engines, Please use comma `,` to separate the words.'
                    ])
                </div>
            </div>
        </div>
    </div>
</div>