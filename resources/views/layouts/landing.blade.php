<!DOCTYPE html>
<html lang="en">
<head>
    <?php $app_env = env('APP_ENV'); ?>
    @if($app_env !== 'local')
        @include('elements.seo.google_tag_manager')
    @endif
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>TradieFlow: Organize Your Tradie Business</title>
    <link rel="icon" href="{{ $app_cdn_url }}/landing_media/img/favicon/favicon-tradieflow.png">
    @yield('view_css')
    <link rel="stylesheet" href="{{ $app_cdn_url }}/landing_media/css/main.css">
</head>
<body>
@if($app_env !== 'local')
    @include('elements.seo.google_tag_manager_no_script')
@endif
@yield('content')
<script src="{{ $app_cdn_url }}/js/jquery-3.6.0.min.js"></script>
<script src="{{ $app_cdn_url }}/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
</script>
@yield('view_script')
@yield('view_script_ext')
@include('elements.inspectlet_code')
@include('elements.form_tracking_code')
</body>
</html>
