<footer class="footer text-center text-dark">
     @php echo date('Y'); @endphp {{__('All Rights Reserved by')}}
     <a href="{{isset($settings['school']['website']) ? $settings['school']['website'] : 'javascript:void(0)'}}" target="_blank" class="text-main">{{$school->name}}</a>.
</footer>