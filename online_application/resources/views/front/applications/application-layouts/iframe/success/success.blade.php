<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Successful submitted</title>
</head>

<body>
    Successful submitted!
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        window.top.location.href = "{{$redirect}}";
    });
</script>
</body>
</html>