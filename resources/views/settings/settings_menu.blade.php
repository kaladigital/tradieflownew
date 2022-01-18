<div class="col-md-auto col-12 content-nav-col">
    @if($user_onboarding->status == 'pending')
        <div class="setup-progress-widget d-flex align-items-center">
            <div class="circle-progress" data-progress="13">
                <svg>
                    <circle cx="22.5" cy="22.5" r="22.5" fill="none" stroke="#eff2f9" stroke-width="2"></circle>
                    <circle cx="22.5" cy="22.5" r="22.5" fill="none" stroke="#43d14f" stroke-width="2"></circle>
                </svg>
                <h6>{{ $auth_user->onboarding_state }}<span>%</span></h6>
            </div>
            <div class="greetings">
                <h6>Get Started!</h6>
                <span>{{ 100 - $auth_user->onboarding_state }}% remaining</span>
            </div>
        </div>
    @endif
    <ul class="content-nav">
        <li class="nav-item {{ $user_onboarding->account ? 'completed' : '' }} {{ $active_page == 'account' ? 'active' : '' }}">
            <a class="nav-link" href="/settings/account">
                <span class="nav-text">Account</span>
            </a>
        </li>
        <li class="nav-item {{ $user_onboarding->phone_numbers ? 'completed' : '' }} {{ $active_page == 'phone_numbers' ? 'active' : '' }}">
            <a class="nav-link" href="/settings/phone-numbers">
                <span class="nav-text">Phone Numbers</span>
            </a>
        </li>
        <li class="nav-item {{ $user_onboarding->calendar ? 'completed' : '' }} {{ $active_page == 'calendar' ? 'active' : '' }}">
            <a class="nav-link" href="/settings/calendar">
                <span class="nav-text">Calendar</span>
            </a>
        </li>
        <li class="nav-item {{ $user_onboarding->forms ? 'completed' : '' }} {{ $active_page == 'forms' ? 'active' : '' }}">
            <a class="nav-link" href="/settings/forms">
                <span class="nav-text">Forms</span>
            </a>
        </li>
        <li class="nav-item {{ $user_onboarding->integrations ? 'completed' : '' }} {{ $active_page == 'integrations' ? 'active' : '' }}">
            <a class="nav-link" href="/settings/integrations">
                <span class="nav-text">Integrations</span>
            </a>
        </li>
        <li class="nav-item {{ $user_onboarding->invoices ? 'completed' : '' }} {{ $active_page == 'invoices' ? 'active' : '' }}">
            <a class="nav-link" href="/settings/invoices">
                <span class="nav-text">Invoices</span>
            </a>
        </li>
        <li class="nav-item {{ $user_onboarding->subscriptions ? 'completed' : '' }} {{ $active_page == 'subscriptions' ? 'active' : '' }}">
            <a class="nav-link" href="/settings/subscriptions">
                <span class="nav-text">Subscriptions</span>
            </a>
        </li>
        <li class="nav-item {{ $user_onboarding->help ? 'completed' : '' }} {{ $active_page == 'help' ? 'active' : '' }}">
            <a class="nav-link" href="/ready-to-go">
                <span class="nav-text">Ready To Go </span>
            </a>
        </li>
    </ul>
    @if($auth_user->tradieflow_subscription_expire_message)
        <div class="update-widget text-center">
            <h6>{{ $auth_user->tradieflow_subscription_expire_message }}</h6>
            <a href="/settings/subscriptions" class="btn update">Upgrade</a>
        </div>
    @endif
</div>
