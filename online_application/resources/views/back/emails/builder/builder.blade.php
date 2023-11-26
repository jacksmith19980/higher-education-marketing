<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <!-- Include Header -->
    @include('back.layouts._partials.header')
    <!-- Import Styles -->
    <link href="{{ asset('media/libs/grapesjs/css/grapesjs.css') }}" rel="stylesheet"/>
    <link href="{{ asset('media/libs/grapesjs/css/app.css') }}" rel="stylesheet"/>
    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-thumb {
            background-color: rgba(255,255,255,.2);
        }
        .gjs-editor-cont ::-webkit-scrollbar-track {
            background: rgba(0,0,0,.1);
        }
    </style>
</head>

<body>

    <div>
        <input type="hidden" id="email_template_id" value="{{ $emailTemplate->id }}">
        <input type="hidden" id="email_template_content" value="{{ $emailTemplate->content }}">
    </div>

    <div id="themes" style="overflow: auto;">
        <div class="header">
            <h5 style="margin: 0;">Email Template Builder</h5>
            <a class="d-block nav-link sidebartoggler waves-effect waves-light sidebar-nav-toggler" href="javascript:void(0)"><i class="ti-arrow-left"></i></a>
        </div>
        <div style="width: 100%; padding-top: 10%;">
            <div style="text-align: center; margin-bottom: 15px;">
                <i class="fas fa-history"></i>
                <select name="changes_history" id="changes_history" style="background-color: transparent; color: white; border: none;">
                    @foreach ($emailTemplateHistory as $history)
                        <option style="background-color: #373d49;" value="{{ $history->content }}">{{ $history->created_at }}</option>
                    @endforeach
                </select>
            </div>
            <div class="header" style="padding-bottom: 10%; margin-bottom: 10%;">
                <h5 style="margin: 0;">Email Themes</h5>
            </div>
            <ul class="inline-list" style="list-style-type: none; padding-left: 13%; padding-right: 13%;">
                @foreach ($emailThemes as $theme)
                <li style="display: inline-block; width: 100%;">
                    <a href="javascript:;" class="card border themesLinks" style="width: 100%; height: 12rem;" data-themeId="{{ $theme->id }}" data-themeContent="{{ $theme->content }}">
                        <div class="card-body" style="display: flex; align-items: center; justify-content: center; text-align: center; overflow: hidden; padding: 0;">
                        @if (!isset($theme->thumb_image_path))
                            <h5 class="card-title">{{ $theme->name }}</h5>
                        @else
                            <img src="{{ Storage::disk('s3')->temporaryUrl($theme->thumb_image_path , \Carbon\Carbon::now()->addMinutes(5)) }}" alt="{{$theme->thumb_image_name}}" style="width: 100%; height: 100%;">
                        @endif
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div id="gjs_div">
        <div id="gjs"></div>
    </div>

    <div id="confirm-cancel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                        <strong>Are you sure you want to cancel the editing ?</strong>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <a class="btn btn-danger btn-ok text-white">Yes</a>
                </div>
            </div>
        </div>
    </div>

    <div id="confirm-reset" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                        <strong>Are you sure you want to reset the changes ?</strong>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <a class="btn btn-danger btn-ok text-white">Yes</a>
                </div>
            </div>
        </div>
    </div>

    <div id="confirm-close" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                        <strong>Are you sure you want to close the editor ?</strong>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <a class="btn btn-danger btn-ok text-white">Yes</a>
                </div>
            </div>
        </div>
    </div>

    <div id="confirm-theme" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                        <strong>Are you sure you want to upload the content of this theme ? </strong>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <a class="btn btn-danger btn-ok text-white">Yes</a>
                </div>
            </div>
        </div>
    </div>

    <div id="confirm-history" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                        <strong>Are you sure you want to replace the content with the change history ? </strong>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <a class="btn btn-danger btn-ok text-white">Yes</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        var email_templates_url = "{{ route('email-templates.index') }}";
    </script>
</body>

</html>


<!-- Import Scripts -->
@include('back.layouts._partials.scripts')
<script src="{{ asset('media/libs/grapesjs/js/grapesjs.js') }}"></script>
<script src="{{ asset('media/libs/grapesjs/js/grapesjs-preset-newsletter.js') }}"></script>
<script src="{{ asset('media/libs/grapesjs/js/app.js') }}"></script>
<script>
    $(document).ready(function() {
        var emailTemplateContent = document.getElementById("email_template_content");
        editor.setComponents(emailTemplateContent.value);
    });
    const themesLinks = document.querySelectorAll('.themesLinks');
    themesLinks.forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            $('#confirm-theme').modal('show');
            $('#confirm-theme .btn-ok').click(function (e) {
                const themeContent = link.dataset.themecontent;
                editor.setComponents(themeContent);
                $('#confirm-theme').modal('hide');
            });
        });
    });
    document.getElementById('changes_history').addEventListener('change', function() {
        var selectedValue = this.value;
        event.preventDefault();
        $('#confirm-history').modal('show');
        $('#confirm-history .btn-ok').click(function (e) {
            editor.setComponents(selectedValue);
            $('#confirm-history').modal('hide');
        });
    });
</script>
