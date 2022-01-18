@extends('layouts.landing')
@section('content')
<style>

    #iubenda_policy{
        font-family: VisueltPro-Regular,sans-serif !important;
        color: #55606e !important;
    }

    #iubenda_policy h1{
        padding-top: 32px !important;
        margin-bottom: 12px !important;
        font-size: 45px !important;
        line-height: 55px !important;
        font-weight: 500 !important;
        color: #000 !important;
    }

    #iubenda_policy h1 strong{
        font-weight: 500 !important;
        color: #43d14f; !important;
    }


    #iubenda_policy h2{
        font-size: 22px !important;
        line-height: 26px !important;
        font-weight: 400 !important;
    }

    .iconed > h3,
    .one_line_col > h3,
    .expand-click{
        font-size: 18px !important;
        padding-bottom: 12px !important;
    }

    #iubenda_policy .iconed ul li h3{
        padding-top: 0 !important;
    }

    #iubenda_policy li::marker{
        color: #43d14f !important;
        font-size: 20px !important;
    }

    #iub-pp-container{
        width: 1210px !important;
        max-width: 100% !important;
        margin-bottom: 130px !important;
        margin-right: auto !important;
        margin-left: auto !important;
    }

</style>
<main class="main forgot-password-page privacy-terms">
    @include('auth.header',['auth_user' => $auth_user])
    <div class="container">
        <div class="row no-gutters">
            <a href="https://www.iubenda.com/privacy-policy/43099197" class="iubenda-white no-brand iubenda-noiframe iubenda-embed iubenda-noiframe iub-body-embed" title="Privacy Policy">Privacy Policy</a>
        </div>
    </div>
    @include('elements.footer',['hide_footer' => true])
</main>
@endsection
@section('view_script')
<script type="text/javascript">(function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src="https://cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);</script>
<script type="text/javascript">
    $(document).ready(function(){
        setTimeout(function(){
            $('.main-footer').show();
        },1000);
    });
</script>
@endsection
