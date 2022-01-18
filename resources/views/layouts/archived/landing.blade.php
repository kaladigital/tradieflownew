<!DOCTYPE html>
<html lang="ru">
<head>
    @if(env('APP_ENV') !== 'local')
        @include('elements.seo.google_tag_manager')
    @endif
    <meta charset="utf-8">
    <title>TradieFlow: Organize Your Tradie Business</title>
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Template Basic Images Start -->
    <meta property="og:image" content="path/to/image.jpg">
    <link rel="icon" href="/landing_media/img/favicon/favicon-tradieflow.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/landing_media/img/favicon/favicon-tradieflow.png">
    <!-- Template Basic Images End -->
    <meta name="theme-color" content="#000">
    @if(env('APP_ENV') == 'local')
        <link rel="stylesheet" href="/landing_media/css/main.min.css">
    @else
        <link rel="stylesheet" href="/landing_media/css/main.min.css?v={{ \Carbon\Carbon::now()->timestamp }}">
    @endif
    <link rel="stylesheet" href="/js/noty/noty.css">
    <script type="text/javascript" src="https://www.bugherd.com/sidebarv2.js?apikey=pr2ua8xyggj9qhqfthlm3w" async="true"></script>
    @include('elements.inspectlet_code')
</head>
<body>
@if(env('APP_ENV') !== 'local')
    @include('elements.seo.google_tag_manager_no_script')
@endif
<div class="wrapper">
    @yield('content')
</div>
<script src="/js/jquery-3.6.0.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/noty/noty.min.js"></script>
@yield('view_script')
@yield('view_script_ext')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    $(document).ready(function(){
        $('.show-nav, .close').click(function () {
            $('.top-nav').slideToggle().css('display', 'flex');
            return false;
        });
    });
</script>
@if(env('APP_ENV') != 'local')
<script>
    // This will initiate Upscope connection. It's important it is added to all pages, even when the user is not logged in.
    (function(w, u, d){var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};var l = function(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://code.upscope.io/3TNBfi4H6Z.js';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);};if(typeof u!=="function"){w.Upscope=i;l();}})(window, window.Upscope, document);

    Upscope('init');
</script>

<script>
    // If the user is logged in, optionally identify them with the following method.
    // You can call Upscope('updateConnection', {}); at any time.
    Upscope('updateConnection', {
        // Set the user ID below. If you don't have one, set to undefined.
        uniqueId: "USER UNIQUE ID",

        // Set the user name or email below (e.g. ["John Smith", "john.smith@acme.com"]).
        identities: ["list", "of", "identities", "here"]
    });
</script>
@endif
</body>
</html>
