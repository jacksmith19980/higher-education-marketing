<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script type="text/javascript"
            src="https://s3.amazonaws.com/eversign-embedded-js-library/eversign.embedded.latest.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            eversign.open({
                url: '{{$documentURL}}',
                containerID: 'eversigned-body',
                width: '100%',
                height: '1200',
                events: {
                    loaded: function () {
                    },
                    signed: function () {
                        $.ajax({
                            url: '{{route('eversign.signed', [$school, $application, $submission])}}',
                            type: "get",
                            dataType: 'json',
                            statusCode: {
                                404: function () {
                                    console.log('page not found')
                                }
                            }
                        }).done(function (data) {
                            //
                        });
                        window.location = '{{route('redirect.afterSubmit', [$school, $application, $submission])}}';
                    },
                    declined: function () {
                    },
                    error: function () {
                    }
                }
            });
        });
    </script>

    <title>Document</title>
</head>
<body style="overview:hidden">

<div class="eversigned-body" id="eversigned-body"
data-eversign_url='{{ route("sign.eversign" , [ 'school' => $school , 'application' => $application ]) }}'
data-field-id="{{ $action->id }}"
>
</div>



</body>
</html>