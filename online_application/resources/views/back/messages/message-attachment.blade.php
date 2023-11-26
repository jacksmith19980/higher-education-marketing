<div class="col-md-3">
    <div class="attachment-item d-flex justify-content-between align-items-center mb-3">
        <a href="{{Storage::disk('s3')->temporaryUrl($attachment->url, \Carbon\Carbon::now()->addMinutes(5))}}" target="_blank"
        class="d-flex"
        >
            <img
                class="img-thumbnail img-responsive thumbnail-sm pt-10 pb-10 mr-2"
                alt="{{$attachment->name}}"
                src="{{ ($attachment->type == 'image') ? Storage::disk('s3')->temporaryUrl($attachment->url, \Carbon\Carbon::now()->addMinutes(5)): asset('media/images/file.png')  }}">

            <span>{{ Str::limit(strip_tags($attachment->name), 25, $end='...') }}</span>
        </a>

        <span>
            @include('back.layouts.core.helpers.table-actions' , [
                'buttons'=> [
                    'view' => [
                            'text'  => 'View',
                            'icon'  => 'icon-eye',
                            'href'  => Storage::disk('s3')->temporaryUrl($attachment->url, \Carbon\Carbon::now()->addMinutes(5)),
                            'attr'  =>'target=_blank',
                            'class' => '',
                    ],
                    'download'  => [
                            'text'  => 'Download',
                            'icon'  => 'icon-arrow-down-circle',
                            'href'  => Storage::disk('s3')->temporaryUrl($attachment->url, \Carbon\Carbon::now()->addMinutes(5)),
                            'attr'  =>'download=download',
                            'class' => '',
                    ]
                ]
            ])
        </span>

    </div>

</div>
