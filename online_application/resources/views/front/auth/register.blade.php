@php
if($layout == 'enhance'){
    $layout = 'enhanced';
}
@endphp

@include('front.auth.' . $layout . '.register' , [
    'params'        => $params ,
    'school'        => $school
])
