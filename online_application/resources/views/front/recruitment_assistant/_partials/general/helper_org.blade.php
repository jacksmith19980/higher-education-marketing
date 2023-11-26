@if($step != 'apply')
<div class="helpbutton" data-toggle="popover"
     style="
           background: url('https://previews.123rf.com/images/arhimicrostok/arhimicrostok1708/arhimicrostok170801289/84517307-question-mark-sign-icon-help-symbol-faq-sign-flat-design-style-.jpg');
             background-repeat: no-repeat;
             background-size: 100% 100%">
    <div class="info">
    </div>
</div>

<script>
    $(function () {
        $('.helpbutton').popover({
            placement: 'top',
            html: true,
            content: '<a href="#" class="close" data-dismiss="alert">&times;</a>' +
                '<div class="popover-div">' +
                '<div class="popover-div-body" style="padding: 20px 5px">' +
                '<h5 class="media-heading">Need Help?</h5>' +
                '<p>{{$assistantBuilder->help_content}}</p>' +
                '</div></div>'

        }).popover('show');

        $(document).on("click", ".popover .close", function () {
            $(this).parents(".popover").popover('hide');
        });

        $(document).on("click", ".helpbutton", function () {
            $('.info').popover('hide');
        });

        $(document).on("click", ".popover-div", function () {
            $(this).parents(".popover").popover('hide');
            $('.info').popover({
                placement: 'top',
                html: true,
                content: '<div class="popover-wrapper">' +
                    '<a href="#" class="close" data-dismiss="alert">&times;</a>' +
                    '<div class="popover-div">' +
                    '<div class="popover-div-body" style="padding: 20px 5px">' +
                    `<h5 class="media-heading">{!! $assistantBuilder->properties[$step . '_help_title'] !!}</h5>` +
                    `<p>{!! $assistantBuilder->properties[$step . '_help_message'] !!}</p>` +
                    `</div></div></div>`
            }).popover('show');
        });

    });
</script>
@endif