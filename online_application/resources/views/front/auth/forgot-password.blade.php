@php
$layout = isset($settings['auth']['login_type']) ? strtolower($settings['auth']['login_type']) : 'basic';
if($layout == 'enhance'){
    $layout = 'enhanced';
}
@endphp
@include('front.auth.'. $layout . '.forgot-password')
