@if (isset($settings['tracking']['tracking_google']) && isset($settings['tracking']['gtm_id'])) 

<!-- Google Tag Manager (noscript) -->

<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{$settings['tracking']['gtm_id']}}"

height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<!-- End Google Tag Manager (noscript) -->

@endif


@if (isset($settings['tracking']['tracking_facebook']) && isset($settings['tracking']['facebook_id'])) 
<!-- Facebook Pixel Code -->
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id={{$settings['tracking']['facebook_id']}}&ev=PageView&noscript=1"
/></noscript>

<!-- End Facebook Pixel Code -->
@endif