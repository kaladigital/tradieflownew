<header class="header">
    <div class="container d-flex align-items-center navbar-expand-md">
        <a href="/" class="main-logo navbar-brand">
            <img src="/images/main-logo.svg" alt="TradieFlow logo">
        </a>
        @if(isset($auth_user) && $auth_user)
            <div class="collapse navbar-collapse text-center" id="navbarContent">
                <a href="/settings/account" class="btn btn-primary btn--sqr start-free-trial-btn">My Account</a>
            </div>
        @else
            <a href="/auth/login" class="login-link ml-auto d-flex align-items-center">
                <img src="/images/user-icon-green-circle.svg" alt="User icon" class="icon">
                <span>Login</span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse text-center" id="navbarContent">
                <a href="/free-trial" class="btn btn-primary btn--sqr start-free-trial-btn animate-pulse">Start Free Trial</a>
            </div>
        @endif
    </div>
</header>
