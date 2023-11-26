@php
    $main_color = (isset($settings['branding']['main_color'])) ? $settings['branding']['main_color'] : '#CC0033';
    $secondary_color = (isset($settings['branding']['secondary_color'])) ? $settings['branding']['secondary_color'] : '#1a8ec6';
@endphp
<style>
    .list-item.has-no-content {
        border-color: {{$secondary_color}};
    }

    .header-main .header-headline {
        background: {{$secondary_color}};
    }

    .header-main .header-nav .header-nav-container nav ul li.active a {
        background: {{$secondary_color}};
    }

    .header-main .header-nav .header-nav-container nav ul li a {
        border-color: {{$secondary_color}};
    }

    .header-main .header-nav .header-nav-container .nav-hline-accent{
        background: {{$main_color}};
    }

    .btn.btn-accent-1 {

        border-color: {{$main_color}};
        background: {{$main_color}};
        @php
            $hsl = App\Helpers\Shared\StyleHelpers::RGBToHSL(App\Helpers\Shared\StyleHelpers::HTMLToRGB($main_color));
        @endphp
        @if($hsl->lightness < 100)
            color: #ffffff;
        @else
            color: #000000;
        @endif
}

    .btn.btn-accent-1:hover {
        border-color: {{$secondary_color}};;
        background: {{$secondary_color}};;
        @php
            $hsl_hover = App\Helpers\Shared\StyleHelpers::RGBToHSL(
                App\Helpers\Shared\StyleHelpers::HTMLToRGB((isset($settings['branding']['secondary_color'])) ? $settings['branding']['secondary_color'] : '#CC0033')
            );
        @endphp
        @if($hsl_hover->lightness < 100)
            color: #ffffff;
        @else
            color: #000000;
        @endif
    }

    .btn.btn-outline-accent-1 {
        border-color: {{$secondary_color}};;
    }

    .btn.btn-outline-accent-1:hover {
        border-color: {{$main_color}};
        background: {{$main_color}};
        @php
          $hsl_outline_hover = App\Helpers\Shared\StyleHelpers::RGBToHSL(App\Helpers\Shared\StyleHelpers::HTMLToRGB($main_color));
        @endphp

        @if($hsl_outline_hover->lightness < 100)
            color: #ffffff;
        @else
            color: #000000;
        @endif
    }

    .main {
        background: {{(isset($settings['branding']['background_color'])) ? $settings['branding']['background_color'] : '#f2f9fc'}};
    }

    .list-item .list-footer-vaa{
        background: {{$secondary_color}};
    }

    .start-date-heading {
        border-bottom: 2px solid {{$secondary_color}};
    }

    .list-start-date{
        border-bottom: 5px solid {{$secondary_color}};
    }

    .subTotal-container-border{
        background: {{$secondary_color}};
    }

    .cta-group ul li{
        background-color: {{$main_color}};
    }
    .cta-group ul li:hover{
        background-color: {{$secondary_color}};
    }

    footer p a {
        color: {{$main_color}};
    }

    .vaa-title{
        color:{{$secondary_color}};
    }

    .vaa-summary {
        background-color: {{$secondary_color}};
        @php
          $hsl_outline_hover = App\Helpers\Shared\StyleHelpers::RGBToHSL(App\Helpers\Shared\StyleHelpers::HTMLToRGB($secondary_color));
        @endphp

        @if($hsl_outline_hover->lightness < 100)
            color: #ffffff;
        @else
            color: #6a6f72;
    @endif
}
</style>
