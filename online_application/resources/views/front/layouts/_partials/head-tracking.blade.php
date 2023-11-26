@if (isset($settings['tracking']['tracking_google']) && isset($settings['tracking']['gtm_id'])) 
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{{$settings['tracking']['gtm_id']}}');</script>
<!-- End Google Tag Manager -->
@endif

@if (isset($settings['tracking']['tracking_google']) && isset($settings['tracking']['analytics_id'])) 

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{$settings['tracking']['analytics_id']}}"></script>

    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag("js", new Date());
        gtag("config", "{{$settings['tracking']['analytics_id']}}");
    </script> 
@endif


@if (isset($settings['tracking']['tracking_facebook']) && isset($settings['tracking']['facebook_id'])) 
    <!-- Facebook Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{$settings['tracking']['facebook_id']}}');
        fbq('track', 'PageView');
        </script>
    <!-- End Facebook Pixel Code -->

@endif