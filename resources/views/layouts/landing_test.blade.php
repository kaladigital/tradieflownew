<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>TradieFlow: Organize Your Tradie Business</title>
    <link rel="icon" href="/favicon-tradieflow.png">
    @yield('view_css')
    <link rel="stylesheet" href="/landing_media/css/main.css?v=1">
    <link rel="stylesheet" href="/js/noty/noty.css">
</head>
<body>
@yield('content')
<script src="/js/jquery-3.6.0.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/noty/noty.min.js"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
</script>
@yield('view_script')
@yield('view_script_ext')
</body>
</html>
