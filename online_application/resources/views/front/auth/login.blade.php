@php
if($layout == 'enhance'){
    $layout = 'enhanced';
}
@endphp

@include('front.auth.' . $layout . '.login' , [
    'params'        => $params ,
    'school'        => $school
])
