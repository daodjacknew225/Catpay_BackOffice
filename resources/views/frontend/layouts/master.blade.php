@php
use App\Models\Admin\BasicSettings;
use App\Models\Admin\SiteSections;
use App\Models\Admin\Language;

// --- BASIC SETTINGS ---
$basic_settings = BasicSettings::first();
if (!$basic_settings) {
    $basic_settings = new \stdClass();

    // Site principal
    $basic_settings->site_name = 'My Website';
    $basic_settings->site_title = 'My Website Title';
    $basic_settings->site_logo = 'logo.png';
    $basic_settings->site_logo_dark = 'logo-dark.png';
    $basic_settings->site_fav = 'favicon.png';
    $basic_settings->site_fav_dark = 'favicon-dark.png';

    // Agent
    $basic_settings->agent_site_name = 'Agent Site';
    $basic_settings->agent_site_title = 'Agent Site Title';
    $basic_settings->agent_site_logo = 'agent-logo.png';
    $basic_settings->agent_site_logo_dark = 'agent-logo-dark.png';
    $basic_settings->agent_site_fav = 'agent-fav.png';
    $basic_settings->agent_site_fav_dark = 'agent-fav-dark.png';

    // Merchant
    $basic_settings->merchant_site_name = 'Merchant Site';
    $basic_settings->merchant_site_title = 'Merchant Site Title';
    $basic_settings->merchant_site_logo = 'merchant-logo.png';
    $basic_settings->merchant_site_logo_dark = 'merchant-logo-dark.png';
    $basic_settings->merchant_site_fav = 'merchant-fav.png';
    $basic_settings->merchant_site_fav_dark = 'merchant-fav-dark.png';
}

// --- COOKIE ---
$cookie = SiteSections::siteCookie();
$approval_status = request()->cookie('approval_status');
$c_user_agent = request()->cookie('user_agent');
$c_ip_address = request()->cookie('ip_address');
$c_browser = request()->cookie('browser');
$c_platform = request()->cookie('platform');

// --- SYSTEM INFO ---
$s_ipAddress = request()->ip();
$s_location = geoip()->getLocation($s_ipAddress);
$s_browser = Agent::browser();
$s_platform = Agent::platform();
$s_agent = request()->header('User-Agent');

// --- LANGUAGES ---
$__languages = Language::all() ?? collect();
@endphp

<!DOCTYPE html>
<html lang="{{ get_default_language_code() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <meta name="keywords" content="{{ isset($seo_data->tags) ? implode(',', $seo_data->tags) : '' }}">
    <meta property="og:description" content="{{ __(@$seo_data->desc) }}">
    <meta property="og:image" content="{{ get_image(@$seo_data->image,'seo') }}">
    <meta property="og:image:width" content="140">
    <meta property="og:image:height" content="80">

    @if (Route::currentRouteName() === 'agent')
        <title>{{ $basic_settings->agent_site_name ?? 'Agent Website' }}</title>
    @elseif(Route::currentRouteName() === 'merchant')
        <title>{{ $basic_settings->merchant_site_name ?? 'Merchant Website' }}</title>
    @else
        <title>{{ $basic_settings->site_name ?? 'Website' }}</title>
    @endif

    <link rel="shortcut icon" href="{{ $basic_settings->site_fav ?? '' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;900&family=Poppins:wght@100;900&display=swap" rel="stylesheet">

    @include('partials.header-asset')
    @stack('css')
</head>
<body class="{{ selectedLangDir() ?? 'ltr' }}">
    @include('frontend.partials.scroll-to-top')
    @include('frontend.partials.header')

    @yield('content')

    <!-- Cookie Banner -->
    <div class="cookie-main-wrapper">
        <div class="cookie-content">
            <p class="text-white">{{ __(strip_tags(@$cookie->value->desc ?? '')) }} 
               <a href="{{ url('/').'/'.(@$cookie->value->link ?? 'privacy-policy') }}">{{ __('privacy Policy') }}</a>
            </p>
        </div>
        <div class="cookie-btn-area">
            <button class="cookie-btn">{{ __('Allow') }}</button>
            <button class="cookie-btn-cross">{{ __('Decline') }}</button>
        </div>
    </div>

    @include('frontend.partials.download-app')
    @include('frontend.partials.footer')
    @include('partials.footer-asset')
    @include('frontend.partials.extensions.tawk-to')

    @stack('script')

    <script>
        var status = "{{ @$cookie->status ?? 0 }}";
        var approval_status = "{{ $approval_status ?? '' }}";
        var c_user_agent = "{{ $c_user_agent ?? '' }}";
        var c_ip_address = "{{ $c_ip_address ?? '' }}";
        var c_browser = "{{ $c_browser ?? '' }}";
        var c_platform = "{{ $c_platform ?? '' }}";
        var s_ipAddress = "{{ $s_ipAddress ?? '' }}";
        var s_browser = "{{ $s_browser ?? '' }}";
        var s_platform = "{{ $s_platform ?? '' }}";
        var s_agent = "{{ $s_agent ?? '' }}";

        const pop = document.querySelector('.cookie-main-wrapper');
        if(status == 1){
            if(approval_status == 'allow' || approval_status == 'decline' || c_user_agent === s_agent || c_ip_address === s_ipAddress || c_browser === s_browser || c_platform === s_platform){
                pop.style.bottom = "-300px";
            } else {
                window.onload = function(){
                    setTimeout(function(){ pop.style.bottom = "20px"; }, 2000);
                }
            }
        } else {
            pop.style.bottom = "-300px";
        }

        (function($){
            $('.cookie-btn').on('click', function(){
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.post('{{ route('global.set.cookie') }}', {type: "allow"}, function(response){
                    throwMessage('success', [response]);
                    setTimeout(() => location.reload(), 1000);
                });
            });
            $('.cookie-btn-cross').on('click', function(){
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.post('{{ route('global.set.cookie') }}', {type: "decline"}, function(response){
                    throwMessage('error',[response]);
                    setTimeout(() => location.reload(), 1000);
                });
            });
        })(jQuery);
    </script>
</body>
</html>