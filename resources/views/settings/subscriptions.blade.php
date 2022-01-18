@extends('layouts.master')
@section('content')
@if($auth_user->role == 'sales')
    @include('admin.left_sidebar_admin_menu',['active_page' => 'settings'])
@else
    @include('dashboard.left_sidebar_menu',['active_page' => 'settings'])
@endif
<div class="col-md-auto col-12 content-wrap">
    <div class="content-inner">
        <div>
            <h2 class="page-title">Settings</h2>
            <div class="content-widget row no-gutters">
                @include('settings.settings_menu',['active_page' => 'subscriptions', 'user_onboarding' => $user_onboarding])
                <div class="col-md-auto col-12 contents">
                    <div class="content-body">
                        <h3>Subscriptions</h3>
                        <div class="visual-section">
                            <div class="inner-container d-flex align-items-center">
                                <div class="note-wrap order-md-2 order-lg-1 d-flex">
                                    <div class="icon">
                                        <img src="/images/info-icon.svg" alt="Info icon">
                                    </div>
                                    <p class="info">
                                        You current subscription details.
                                    </p>
                                </div>
                                <div class="graphics-figure ml-auto order-md-1 order-lg-2">
                                    <img src="/images/subscriptions-visual-figure.svg" alt="Subscriptions visual figure">
                                </div>
                            </div>
                        </div>
                        <h6>Subscriptions</h6>
                        <p>Here you will be able to handle all of your Pedestal subscriptions.</p>
                        <div class="card subscription-card pro-plan inner-container">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div class="info-wrap">
                                    <div class="logo"><img src="/images/main-logo.svg" alt="Tradieflow logo"></div>
                                    @if($current_subscription)
                                        <strong>
                                            {{ $current_subscription->subscription_plan_name }}
                                            @if($upcoming_subscription)
                                                ("{{ $upcoming_subscription->subscription_plan_name }}" starts on {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$current_subscription->expiry_date_time)->format('M j, Y H:i') }})
                                            @endif
                                        </strong>
                                        @if($current_subscription->subscription_plan_code == 'trial')
                                            <p class="card-text">{{ \App\Helpers\Helper::calculateEstimateTime($current_subscription->expiry_date_time) }}</p>
                                        @endif
                                    @elseif($old_subscription)
                                        <span class="warning-text">Expired {{ $old_subscription->subscription_plan_name }} Subscription</span>
                                    @endif
                                </div>
                                <div class="action-wrap d-flex align-items-center">
                                    @if(
                                         !$current_subscription || ($current_subscription && $current_subscription->subscription_plan_code == 'trial') ||
                                         ($upcoming_subscription && !$upcoming_subscription->is_extendable) || ($current_subscription && (!$current_subscription->is_extendable || $current_subscription->subscription_plan_code  == 'pro'))
                                     )
                                        <button class="btn btn--sqr btn-primary upgrade_plan">Upgrade Your Plan</button>
                                    @endif
                                    @if($current_subscription && $current_subscription->is_extendable && $current_subscription->subscription_plan_code == 'yearly' && !$upcoming_subscription)
                                        <button class="btn btn--sqr btn-primary" id="downgrade_plan_btn">Downgrade Your Plan</button>
                                    @endif
                                    @if(($upcoming_subscription && $upcoming_subscription->is_extendable) || ($current_subscription && $current_subscription->subscription_plan_code !== 'trial' && $current_subscription->is_extendable))
                                        <button class="btn btn--sqr btn-secondary cancel-renewal" id="cancel_renewal_btn">Cancel Renewal</button>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                @if($upcoming_subscription)
                                    @if($upcoming_subscription->is_extendable)
                                        <p>
                                            Your next payment costs <strong>{{ $upcoming_subscription->price }} {{ $upcoming_subscription->currency == 'usd' ? 'USD' : 'AUD' }}</strong>, to be charged on <strong>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$upcoming_subscription->expiry_date_time)->format('M j, Y') }}.</strong>
                                        </p>
                                        <p class="small-text">
                                            Your subscription "{{ $upcoming_subscription->subscription_plan_name }}" will be automatically renewed each {{ ($upcoming_subscription->subscription_plan_code == 'pro') ? 'month.' : 'year.' }}
                                        </p>
                                    @else
                                        <p class="small-text">
                                            Your subscription "{{ $upcoming_subscription->subscription_plan_name }}" will expire on {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$upcoming_subscription->expiry_date_time)->format('M j, Y H:i') }}
                                        </p>
                                    @endif
                                @elseif($current_subscription)
                                    @if($current_subscription->is_extendable)
                                        <p>
                                            Your next payment costs <strong>{{ $current_subscription->price }} {{ $current_subscription->currency == 'usd' ? 'USD' : 'AUD' }}</strong>, to be charged on <strong>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$current_subscription->expiry_date_time)->format('M j, Y') }}.</strong>
                                        </p>
                                        <p class="small-text">
                                            Your subscription "{{ $current_subscription->subscription_plan_name }}" will be automatically renewed each {{ ($current_subscription->subscription_plan_code == 'pro') ? 'month.' : 'year.' }}
                                        </p>
                                    @else
                                        <p class="small-text">
                                            Your subscription "{{ $current_subscription->subscription_plan_name }}" will expire on {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$current_subscription->expiry_date_time)->format('M j, Y H:i') }}
                                        </p>
                                    @endif
                                @elseif($old_subscription)
                                    <p class="small-text">
                                        Your subscription expired on <strong>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$old_subscription->expiry_date_time)->format('M j, Y') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                        @if($tradiereview_current_subscription || $tradiereview_old_subscription)
                            <div class="card subscription-card sub free-trial inner-container">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div class="info-wrap">
                                        <div class="logo">
                                            <img src="/images/tradiereviews.svg" alt="TradieReviews logo">
                                        </div>
                                        @if($tradiereview_current_subscription)
                                            <strong>{{ $tradiereview_current_subscription->subscription_plan_name }}</strong>
                                            @if($tradiereview_current_subscription->subscription_plan_code == 'trial')
                                                <p class="gray-text">{{ \App\Helpers\Helper::calculateEstimateTime($tradiereview_current_subscription->expiry_date_time) }}</p>
                                            @else
                                                <p class="gray-text">Expires on: {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$tradiereview_current_subscription->expiry_date_time)->format('j F, Y') }}</p>
                                            @endif
                                        @elseif($tradiereview_old_subscription)
                                            <strong>{{ $tradiereview_old_subscription->subscription_plan_name }}</strong>
                                            <?php
                                                $expiry_date_time = $tradiereview_old_subscription->final_expiry_date_time ? $tradiereview_old_subscription->final_expiry_date_time : $tradiereview_old_subscription->expiry_date_time;
                                            ?>
                                            <p class="gray-text">Expired on: {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$expiry_date_time)->format('j F, Y') }}</p>
                                        @endif
                                    </div>
                                    @if($tradiereview_current_subscription)
                                        @if($tradiereview_current_subscription->subscription_plan_code !== 'trial')
                                            <div class="badge-wrap">
                                                <span class="badge blue">Paid {{ $tradiereview_current_subscription->subscription_plan_code == 'pro' ? 'Monthly' : 'Yearly' }}</span>
                                            </div>
                                        @endif
                                    @elseif($tradiereview_old_subscription)
                                        @if($tradiereview_old_subscription->subscription_plan_code !== 'trial')
                                            <div class="badge-wrap">
                                                <span class="badge blue">Paid {{ $tradiereview_old_subscription->subscription_plan_code == 'pro' ? 'Monthly' : 'Yearly' }}</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="choose-plan-inner inner-container">
                            <div class="row select-plan-row" id="plans_container" style="display:none;">
                                <div class="col-12">
                                    <h6>Upgrade Your Plan</h6>
                                </div>
                                <div class="col-12 col-xl-6 plan_item" data-type="pro" data-price="{{ $auth_user->currency == 'usd' ? $subscription_plans['pro']['price_usd'] : $subscription_plans['pro']['price_aud'] }}" data-title="Monthly Starter">
                                    <div class="plan-item active d-flex align-items-center">
                                        <figure>
                                            <img src="/images/monthly-figure.jpg" alt="Monthly figure image">
                                        </figure>
                                        <div class="plan-info">
                                            <h6>Monthly Starter</h6>
                                            <p>
                                                <span class="price">{{ $auth_user->currency == 'usd' ? '$'.$subscription_plans['pro']['price_usd'] : 'AUD '.$subscription_plans['pro']['price_aud'] }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-3 mt-xl-0 col-xl-6 plan_item" data-type="yearly" data-price="{{ $auth_user->currency == 'usd' ? $subscription_plans['yearly']['price_usd'] : $subscription_plans['yearly']['price_aud'] }}" data-title="Yearly Professional">
                                    <div class="plan-item d-flex align-items-center">
                                        <figure>
                                            <img src="/images/yearly-figure.jpg" alt="Monthly figure image">
                                        </figure>
                                        <div class="plan-info">
                                            <h6>Yearly Professional</h6>
                                            <p>
                                                <span class="price">{{ $auth_user->currency == 'usd' ? '$'.$subscription_plans['yearly']['price_usd'] : 'AUD '.$subscription_plans['yearly']['price_aud'] }}</span>
                                                / yearly
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row discount-section" id="discount_box_container" style="display:none;">
                                <div class="col-12">
                                    <h6>Discount Code</h6>
                                    <p>Please enter your discount code below.</p>
                                    <div class="field-wrap d-flex align-items-center">
                                        <div class="form-group">
                                            {!! Form::text('discount_code',null,['class' => 'form-control', 'placeholder' => 'Discount Code', 'id' => 'discount_code', 'autocomplete' => 'off']) !!}
                                            {!! Form::label('discount_code','Discount Code') !!}
                                        </div>
                                        <button type="button" class="btn btn-primary btn--sqr" id="apply_discount_code">Apply</button>
                                    </div>
                                    <div class="notification-row widget-box d-flex align-items-center success display-hidden" id="discount_applied_container" style="display:none;">
                                        <div class="icon">
                                            <img class="success-avatar" src="/images/discount-success-avatar.png" alt="Avatar">
                                        </div>
                                        <h6>
                                            <span>Success!</span> You have successfully added your discount code.
                                        </h6>
                                        <button type="button" class="btn btn-close ml-auto close_discount_alert">
                                            <img src="/images/close-gray.svg" alt="Close icon">
                                        </button>
                                    </div>
                                    <div class="notification-row widget-box d-flex align-items-center error display-hidden" id="discount_error_container">
                                        <div class="icon">
                                            <img class="error-avatar" src="/images/discount-error-avatar.png" alt="Avatar">
                                        </div>
                                        <h6>
                                            <span>Oooops,</span> this discount code is not valid. Please try another one!
                                        </h6>
                                        <button type="button" class="btn btn-close ml-auto close_discount_alert">
                                            <img src="/images/close-gray.svg" alt="Close icon">
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row total-payable-row" id="total_price_container" style="display:none;">
                                <div class="col-12">
                                    <h6>Total to be payed:</h6>
                                    <ul>
                                        <li class="d-flex align-items-center">
                                            <span>Yearly Professional:</span>
                                            <div class="price" id="current_plan_price"></div>
                                        </li>
                                        <li class="d-flex align-items-center display-hidden" id="discount_container">
                                            <span>Discount Value:</span>
                                            <div class="price" id="total_discount_price"></div>
                                        </li>
                                        <li class="d-flex align-items-center">
                                            <span>GST(10%):</span>
                                            <div class="price" id="gst_price"></div>
                                        </li>
                                        <li class="d-flex align-items-center estotal">
                                            <span>ESTIMATED TOTAL:</span>
                                            <div class="price" id="total_subscription_price"></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div id="card_checkout_container" class="mt-4" style="display:none;">
                                <div class="card_checkout_loader" style="display:none;">
                                    <img src="/images/loader.png" width="24px" class="float-left">
                                    <span class="float-left ml-1 loader-text">Processing</span>
                                </div>
                                <button id="card_checkout_btn" class="btn btn--sqr btn-primary">Checkout</button>
                            </div>
                            <div class="row payment-method">
                                <div class="col-12">
                                    <div id="payment_container" style="display:none;">
                                        <h6>Payment Method</h6>
                                        <p>Here you can customize what you would prefer to pay with.</p>
                                        <div class="payment-method-form-wrap form-shown card-container">
                                            <form id="subscription_form" data-type="">
                                                <div class="form-group">
                                                    <div id="card_number" class="form-control"></div>
                                                    <label for="card_number">Credit Card Number</label>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-lg-6">
                                                        <div id="expiry_date" class="form-control"></div>
                                                        <label for="expiry_date">Expiry Date</label>
                                                    </div>
                                                    <div class="form-group col-lg-6">
                                                        <div id="cvv_code" class="form-control"></div>
                                                        <label for="cvv_code">CVC (Security Code)</label>
                                                    </div>
                                                </div>
                                                <div id="stripe_error"></div>
                                                <div class="subscription_loader" style="display:none;">
                                                    <img src="/images/loader.png" width="24px" class="float-left">
                                                    <span class="float-left ml-1 loader-text">Processing</span>
                                                </div>
                                                <button type="submit" class="btn btn--sqr btn-primary" id="default_checkout_btn">Checkout</button>
                                            </form>
                                        </div>
                                    </div>
                                    @if($has_payment_method)
                                        <div class="card-info-wrap row align-items-center" id="update_card_container">
                                            <div class="col-12 col-sm-6 col-md-auto card-figure">
                                                <div class="card">
                                                    <img src="/images/card-bgr.jpg" alt="Card image">
                                                    <div class="card-info">
                                                        <div class="blank">&nbsp;</div>
                                                        <div class="card-number row no-gutters">
                                                            <div class="col-3">....</div>
                                                            <div class="col-3">....</div>
                                                            <div class="col-3">....</div>
                                                            <div class="col-3 last-digit">{{ $card_details['last_digits'] }}</div>
                                                        </div>
                                                        <div class="card-icon master-card">
                                                            @switch($card_details['card_type'])
                                                                @case('mastercard')
                                                                    <img class="master-card" src="/images/ic-mastercard.png" alt="Master card">
                                                                @break
                                                                @case('visa')
                                                                    <img class="visa-card" src="/images/ic-visa.png" alt="Visa card">
                                                                @break
                                                                @case('jcb')
                                                                    <img class="jcb-card" src="/images/ic-jcb.png" alt="JCB card">
                                                                @break
                                                                @case('amex')
                                                                    <img class="american-express-card" src="/images/ic-american-express.png" alt="American express club card">
                                                                @break
                                                                @case('diners_club')
                                                                    <img class="diners-club-card" src="/images/ic-Diners-club.png" alt="Diners club card">
                                                                @break
                                                                @case('discover')
                                                                    <img class="discover-card" src="/images/ic-Discover.png" alt="Discover card">
                                                                @break
                                                                @default
                                                                    <img class="unknown-card" src="/images/ic-unknown.png" alt="Unknown card">
                                                                @break
                                                            @endswitch
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-auto mt-3 mt-sm-0 mt-md-3 mt-lg-0 card-action-col">
                                                <button type="button" class="btn btn-primary btn--sqr" id="change_user_card">Change Card</button>
                                                <button type="button" class="btn btn-secondary btn--sqr" id="remove_user_card">Remove Card</button>
                                            </div>
                                        </div>
                                    @endif
                                    <a href="/get/tradiereviews" class="btn btn-primary btn--round float-right">Get TradieReviews</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="action-row">
                        @if($user_onboarding->status == 'pending')
                            <a href="/settings/skip/subscriptions" class="btn btn--round btn-secondary">Skip</a>
                            <a href="/settings/skip/subscriptions" class="btn btn--round btn-primary">Continue</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade country-code-specification-modal" id="change_phone_modal" tabindex="-1" role="dialog" aria-labelledby="countryCodeSpecificationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header text-center">
                    <div class="modal-content-inner">
                        <h5 class="modal-title" id="countryCodeSpecificationModalLabel">Would you like to change your phone number?</h5>
                        <p>You will be able to select from a set of country codes if you answer with yes. You can choose form US, British, Australian and Canadian phone numbers.</p>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="modal-content-inner">
                        <div class="row buttons-row" id="phone_number_change_options">
                            <div class="col-12 col-sm-6">
                                <button type="button" class="btn btn-outline btn-no" id="change_phone_number_no">
                                    <img class="icon default" src="/images/close-round-circle-icon.svg" alt="Close icon round circle">
                                    <img class="icon white" src="/images/close-round-circle-icon-white.svg" alt="Close icon round circle">
                                    No
                                </button>
                            </div>
                            <div class="col-12 mt-3 mt-sm-0 col-sm-6">
                                <button type="button" class="btn btn-outline btn-yes" id="change_phone_number_yes">
                                    <img class="icon white" src="/images/check-round-white.svg" alt="Close icon round circle">
                                    <img class="icon default" src="/images/check-round-green-outline.svg" alt="Close icon round circle">
                                    Yes, change it
                                </button>
                            </div>
                        </div>
                        <div class="aditional-form" id="phone_number_change_container" style="display:none;">
                            <div class="group-heading">Select Phone Number country code</div>
                            <div class="form-group select-group">
                                {!! Form::select('phone_country',$phone_countries,null,['id' => 'phone_country', 'class' => 'form-control', 'placeholder' => 'Select', 'required' => 'required', 'autocomplete' => 'off']) !!}
                                {!! Form::label('phone_country','Phone Number Country Code') !!}
                            </div>
                            <section id="au_address_container" style="display:none;">
                                <div class="group-heading">Select Number Type</div>
                                <div class="form-group">
                                    <ul class="nav nav-tabs action-triger">
                                        <li class="nav-item">
                                            <a class="nav-link au_phone_type active" data-type="local" href="#">Local</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link au_phone_type" data-type="mobile" href="#">Mobile</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link au_phone_type" data-type="toll_free" href="#">Toll-Free</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="group-heading">Select a Local Number/Area</div>
                                <div class="form-group select-group twilio_address_item">
                                    <label for="au_phone_area_code">Phone Number Area Code</label>
                                    {!! Form::select('au_phone_area_code', $au_phone_area_codes, null, ['class' => 'form-control', 'id' => 'au_phone_area_code']) !!}
                                </div>
                                <div class="group-heading">Address</div>
                                <p class="declined-note" id="twilio_address_validate_fail">The address you provided was declined by our phone number provider. Please fill out the below form again!</p>
                                <div class="form-group twilio_address_item">
                                    {!! Form::text('address', $auth_user->address, ['class' => 'form-control', 'id' => 'address', 'placeholder' => 'Address Line']) !!}
                                    {!! Form::label('address','Address Line') !!}
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-6 twilio_address_item">
                                        {!! Form::text('city', $auth_user->city, ['class' => 'form-control', 'placeholder' => 'City', 'id' => 'city']) !!}
                                        {!! Form::label('city','City') !!}
                                    </div>
                                    <div class="form-group col-6 twilio_address_item">
                                        {!! Form::text('state', $auth_user->state, ['class' => 'form-control', 'id' => 'state', 'placeholder' => 'State']) !!}
                                        {!! Form::label('state','State') !!}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-6 twilio_address_item">
                                        {!! Form::text('zip_code', $auth_user->zip_code, ['class' => 'form-control', 'placeholder' => 'ZIP', 'id' => 'zip_code']) !!}
                                        {!! Form::label('zip_code','ZIP') !!}
                                    </div>
                                    <div class="form-group select-group col-6 twilio_address_item">
                                        {!! Form::select('country_id', $all_countries, $auth_user->country_id, ['class' => 'form-control', 'id' => 'country_id', 'placeholder' => 'Select']) !!}
                                        {!! Form::label('country_id','Country') !!}
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex">
                    <button class="btn btn-primary btn--round ml-auto disabled" id="change_number_next_btn">Next</button>
                    <div id="twilio_address_loader" style="display:none;">
                        <img src="/images/loader.png" width="24px" class="float-left">
                        <span class="float-left ml-1 loader-text">Processing</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@if($actual_subscription)
    <div class="modal fade downgrade-plan-popup" id="cancel_subscription_modal" tabindex="-1" role="dialog" aria-labelledby="downgradePlanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <span class="modal-title ml-auto" id="downgradePlanModalLabel">Cancel Your Plan</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="/images/in-call-close-icon.svg" alt="Close icon gray">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="figure text-center">
                        <img src="/images/tradie-icon.svg" alt="Tradie icon">
                    </div>
                    <h2>Do you want to keep being a {{ $actual_subscription->subscription_plan_name  }}</h2>
                    <h6>You will be able to use the {{ $actual_subscription->subscription_plan_name }} Account until {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$actual_subscription->expiry_date_time)->format('F j, Y') }}.</h6>
                    <div class="btn-row d-flex align-items-center">
                        <button type="button" id="confirm_cancel_subscription" class="btn btn--sqr btn-primary cancel_subscription_actions">
                            I don’t want to be a {{ $actual_subscription->subscription_plan_name }}
                        </button>
                        <button type="button" class="btn btn--sqr btn-secondary cancel_subscription_actions" data-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                    <h6 id="cancel_renewal_loader" class="w118" style="display:none;">
                        <img src="/images/loader.gif" width="24px" class="float-left">
                        <span class="float-left ml-1 loader-text">Processing</span>
                    </h6>
                </div>
            </div>
        </div>
    </div>
@endif
@if($current_subscription && $current_subscription->is_extendable && $current_subscription->subscription_plan_code == 'yearly' && !$upcoming_subscription)
    <div class="modal fade downgrade-plan-popup" id="downgrade_subscription_modal" tabindex="-1" role="dialog" aria-labelledby="downgradePlanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <span class="modal-title ml-auto" id="downgradePlanModalLabel">Downgrade Your Plan</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="/images/in-call-close-icon.svg" alt="Close icon gray">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="figure text-center">
                        <img src="/images/tradie-icon.svg" alt="Tradie icon">
                    </div>
                    <h2>Downgrade to {{ $subscription_plans['pro']['name']  }}?</h2>
                    <h6>You will be able to use the {{ $current_subscription->subscription_plan_name }} Account until {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$current_subscription->expiry_date_time)->format('F j, Y') }}.</h6>
                    <div class="btn-row d-flex align-items-center">
                        <button type="button" id="confirm_downgrade_subscription" class="btn btn--sqr btn-primary downgrade_subscription_actions">
                            I don’t want to be a {{ $current_subscription->subscription_plan_name }}
                        </button>
                        <button type="button" class="btn btn--sqr btn-secondary downgrade_subscription_actions" data-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                    <h6 id="downgrade_subscription_loader" class="w118" style="display:none;">
                        <img src="/images/loader.gif" width="24px" class="float-left">
                        <span class="float-left ml-1 loader-text">Processing</span>
                    </h6>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
@section('view_script')
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    $(document).ready(function(){
        window.phone_number_state = null;
        window.phone_number_address = {};
        window.stripe_form_token = null;
        window.discount_code_obj = {
            code: null,
            discount_percentage: null
        }
        window.region_codes = <?php echo json_encode($twilio_region_codes); ?>;
        window.au_regions = <?php echo json_encode($au_phone_area_codes); ?>;

        $(document).on('click','#change_phone_number_no',function(){
            $('#change_phone_number_yes').removeClass('active')
            $(this).addClass('active');
            $('#change_number_next_btn').removeClass('disabled');
            $('#au_address_container').hide();
            $('#phone_number_change_container').hide();
            $('#phone_country').val('');
            $('#twilio_address_validate_fail').removeClass('d-block');
            $('.twilio_address_item').removeClass('warning');
            return false;
        });

        $(document).on('click','#change_phone_number_yes',function(){
            $('#change_phone_number_no').removeClass('active')
            $(this).addClass('active');
            $('#change_number_next_btn').addClass('disabled');
            $('#au_address_container').hide();
            $('#phone_number_change_container').show();
            $('#phone_country').val('');
            $('#twilio_address_validate_fail').removeClass('d-block');
            $('.twilio_address_item').removeClass('warning');
            return false;
        });

        $(document).on('click','#change_number_next_btn',function(){
            var no_checked = $('#change_phone_number_no').hasClass('active');
            var yes_checked = $('#change_phone_number_yes').hasClass('active');

            if (no_checked || yes_checked) {
                if (no_checked) {
                    $('#change_phone_modal').modal('hide');
                    $('#card_checkout_btn').hide();
                    $('.card_checkout_loader').show();
                    phone_number_state = 'no';
                    phone_country_id = null;
                    update_subscription(stripe_form_token);
                }
                else{
                    if ($('#phone_number_change_container').is(':visible')) {
                        var phone_country = $('#phone_country').val();
                        if (phone_country) {
                            var phone_country_label = $('#phone_country option:selected').text();
                            if (phone_country_label.indexOf('Australia') == -1) {
                                phone_number_address = {
                                    phone_country : phone_country,
                                    valid: true
                                }

                                $('#change_phone_modal').modal('hide');
                                $('#card_checkout_btn').show();
                                $('.card_checkout_loader').hide();
                                update_subscription(stripe_form_token);
                            }
                            else{
                                var address = $.trim($('#address').val());
                                if (!address.length) {
                                    $('#address').focus();
                                    return false;
                                }

                                var city = $.trim($('#city').val());
                                if (!city.length) {
                                    $('#city').focus();
                                    return false;
                                }

                                var zip_code = $.trim($('#zip_code').val());
                                if (!zip_code.length) {
                                    $('#zip_code').focus();
                                    return false;
                                }

                                var state = $.trim($('#state').val());
                                if (!state) {
                                    $('#state').focus();
                                    return false;
                                }

                                var country = $('#country_id').val();
                                if (!country) {
                                    $('#country_id').focus();
                                    return false;
                                }

                                phone_number_address = {
                                    address: address,
                                    city: city,
                                    zip_code: zip_code,
                                    state: state,
                                    country_id: country,
                                    phone_country: phone_country,
                                    phone_type: $('.au_phone_type').filter('.active').attr('data-type'),
                                    phone_area: $('#au_phone_area_code').val()
                                }

                                $('#change_number_next_btn').hide();
                                $('#twilio_address_loader').show();
                                $('.twilio_address_item').removeClass('warning');
                                $.ajax({
                                    url: '/settings/au/phone/address',
                                    type: 'POST',
                                    async : false,
                                    dataType: 'JSON',
                                    data: phone_number_address,
                                    success: function(data){
                                        $('#change_number_next_btn').show();
                                        $('#twilio_address_loader').hide();
                                        if (data.status) {
                                            $('#change_phone_modal').modal('hide');
                                            $('#card_checkout_btn').hide();
                                            $('.card_checkout_loader').show();
                                            update_subscription(stripe_form_token);
                                        }
                                        else{
                                            $('#twilio_address_validate_fail').addClass('d-block');
                                            $('.twilio_address_item').addClass('warning');
                                        }
                                    }
                                });
                            }
                        }
                        else{
                            $('#phone_country').focus();
                        }
                    }
                    else{
                        $('#au_address_container').hide();
                        $('#phone_number_change_container').show();
                        $('#phone_country').val('');
                    }
                }
            }

            return false;
        });

        $(document).on('click','#discard_change_phone',function(){
            $('#change_phone_modal').modal('hide');
            $('#card_checkout_btn').show();
            $('.card_checkout_loader').hide();
            phone_number_state = null;
            phone_country_id = null;
            phone_number_address = {};
            return false;
        });

        $(document).on('change','#phone_country',function(){
            var phone_country_label = $('#phone_country option:selected').text();
            if (phone_country_label.indexOf('Australia') == -1) {
                $('#au_address_container').hide();
            }
            else{
                $('#au_address_container').show();
                $('.au_phone_type').filter('.active').trigger('click');
            }

            if ($(this).val()) {
                $('#change_number_next_btn').removeClass('disabled');
            }
            else{
                $('#change_number_next_btn').addClass('disabled');
            }
            $('.twilio_address_item').removeClass('warning');
            $('#twilio_address_validate_fail').removeClass('d-block');
            return false;
        });

        $(document).on('click','.au_phone_type',function(){
            $('.au_phone_type').not($(this)).removeClass('active');
            $(this).addClass('active');
            var data_type = $(this).attr('data-type');
            var current_country = $('#au_phone_area_code').val();
            $('#au_phone_area_code').empty();
            for (var i in au_regions) {
                if (region_codes[i]['has_' + data_type]) {
                    $('#au_phone_area_code').append(new Option(au_regions[i],i));
                }
            }

            /**If old selection exists*/
            if ($('#au_phone_area_code option[value="' + current_country + '"]').length) {
                $('#au_phone_area_code').val(current_country);
            }

            return false;
        })

        /**Handle Discount Change*/
        $(document).on('click','#apply_discount_code',function(){
            var discount_code = $.trim($('#discount_code').val());
            if (discount_code.length) {
                $.post('/settings/subscriptions/discount',{ code: discount_code },function(data){
                    if (data.status) {
                        $('#discount_error_container').addClass('display-hidden');
                        $('#discount_applied_container').removeClass('display-hidden');
                        discount_code_obj.code = discount_code;
                        discount_code_obj.discount_percentage = data.percentage;
                    }
                    else{
                        $('#discount_applied_container').addClass('display-hidden');
                        $('#discount_error_container').removeClass('display-hidden');
                        discount_code_obj.code = null;
                        discount_code_obj.discount_percentage = null;
                    }

                    $('.plan-item.active').trigger('click');
                },'json');
            }
            else{
                $('#discount_applied_container,#discount_error_container').addClass('display-hidden');
                discount_code_obj.code = null;
                discount_code_obj.discount_percentage = null;
                $('#discount_code').focus();
            }

            return false;
        });

        $(document).on('click','.close_discount_alert',function(){
            $(this).closest('.widget-box').addClass('display-hidden');
            return false;
        });

        $(document).on('click','.upgrade_plan',function(){
            show_subscription_upgrade_box(true);
            return false;
        });

        $(document).on('click','#cancel_renewal_btn',function(){
            $('#cancel_subscription_modal').modal('show');
            return false;
        });

        $(document).on('click','#confirm_cancel_subscription',function(){
            $('.cancel_subscription_actions').removeClass('d-flex').hide();
            $('#cancel_renewal_loader').show();
            $.post('/settings/cancel/renewal',{},function(data){
                App.render_message('success','Renewal cancelled successfully');
                setTimeout(function(){
                    location.reload();
                },1000);
            },'json');
        });

        $(document).on('click','#downgrade_plan_btn',function(){
            $('#downgrade_subscription_modal').modal('show');
            return false;
        });

        $(document).on('click','#confirm_downgrade_subscription',function(){
            $('.downgrade_subscription_actions').hide();
            $('#downgrade_subscription_loader').show();

            $.post('/settings/update/subscription',{ token: null, subscription: 'pro', discount_code: null },function(data){
                $('#downgrade_subscription_modal').modal('hide');
                if (data.status) {
                    setTimeout(function(){
                        location.reload();
                    },1500);
                }
                else{
                    $('#downgrade_subscription_loader').hide();
                    $('.downgrade_subscription_actions').show();
                }
            },'json');

            return false;
        });

        $(document).on('click','#change_user_card',function(){
            $('#update_card_container').hide();
            $('#subscription_form').attr('data-type','card');
            $('#default_checkout_btn').text('Update Card');
            $('#plans_container,#total_price_container,#card_checkout_container').hide();
            $('#payment_container').fadeIn();
            return false;
        });

        $(document).on('click','#remove_user_card',function(){
            $('#update_card_container').remove();
            $.post('/settings/remove/user/card',{},function(data){
                App.render_message('success','Card removed successfully');
                setTimeout(function(){
                    location.reload();
                },1000)
            },'json');
            return false;
        });

        $(document).on('click','.plan_item',function(){
            $('#discount_box_container').show();
            $('.plan-item').not($(this)).removeClass('active');
            $(this).find('.plan-item').addClass('active');
            var price_text = $(this).attr('data-price');
            var price = parseFloat($(this).attr('data-price'));
            var discount_price = 0;
                discount_price = price * discount_code_obj.discount_percentage / 100;
                price -= discount_price;
            var gst_price = price / 10;
            $('#current_plan_title').text($(this).attr('data-title') + ':');
            $('#current_plan_price').text('{{ $auth_user->currency == 'usd' ? '$' : 'AUD ' }}' + price_text);

            $('#gst_price').text('{{ $auth_user->currency == 'usd' ? '$' : 'AUD ' }}' + format_number(gst_price));

            if (discount_code_obj.discount_percentage) {
                $('#total_discount_price').text((discount_price ? '- ' : '') + '{{ $auth_user->currency == 'usd' ? '$' : 'AUD ' }}' + format_number(discount_price));
                $('#discount_container').removeClass('display-hidden');
            }
            else{
                $('#discount_container').addClass('display-hidden');
            }

            $('#total_subscription_price').text('{{ $auth_user->currency == 'usd' ? '$' : 'AUD ' }}' + format_number(price + gst_price));
            return false;
        });

        $(document).on('submit','#subscription_form',function(){
            var form_type = $(this).attr('data-type');
            if (form_type == 'upgrade') {
                if ($('#update_card_container').length) {
                    var expire_status = has_expired_subscription();
                    if (expire_status.expired) {
                        /**Show Modal*/
                        if (expire_status.phone_lost) {
                            $('#phone_number_change_options').hide();
                            $('#change_phone_number_yes').trigger('click');
                        }
                        else{
                            $('#phone_number_change_options').show();
                            $('#change_phone_number_no').removeClass('active');
                            $('#change_phone_number_yes').removeClass('active');
                            $('#change_number_next_btn').addClass('disabled');
                            $('#au_address_container').hide();
                            $('#phone_number_change_container').hide();
                            $('#twilio_address_validate_fail').removeClass('d-block');
                            $('.twilio_address_item').removeClass('warning');
                        }

                        $('#change_phone_modal').modal('show');
                        $('#default_checkout_btn').show();
                        $('.subscription_loader').hide();
                    }
                    else{
                        update_subscription(null);
                    }
                }
                else{
                    $('#default_checkout_btn').hide();
                    $('.subscription_loader').show();
                    stripe.createToken(cardNumber).then(function(result) {
                        if (result.error) {
                            $('#stripe_error').text(result.error.message);
                            $('#default_checkout_btn').show();
                            $('.subscription_loader').hide();
                        }
                        else {
                            $('#stripe_error').empty();
                            stripe_form_token = result.token.id;
                            if (phone_number_state == 'no') {
                                update_subscription(stripe_form_token);
                            }
                            else if(phone_number_state == 'yes') {
                                $('#default_checkout_btn').show();
                                $('.subscription_loader').hide();
                                if (phone_number_state.valid) {
                                    update_subscription(stripe_form_token);
                                }
                                else{
                                    $('#change_phone_modal').modal('show');
                                    $('#default_checkout_btn').show();
                                    $('.subscription_loader').hide();
                                }
                            }
                            else {
                                var expire_status = has_expired_subscription();
                                if (expire_status.expired) {
                                    /**Show Modal*/
                                    if (expire_status.phone_lost) {
                                        $('#phone_number_change_options').hide();
                                        $('#change_phone_number_yes').trigger('click');
                                    }
                                    else{
                                        $('#phone_number_change_options').show();
                                        $('#change_phone_number_no').removeClass('active');
                                        $('#change_phone_number_yes').removeClass('active');
                                        $('#change_number_next_btn').addClass('disabled');
                                        $('#au_address_container').hide();
                                        $('#phone_number_change_container').hide();
                                        $('#twilio_address_validate_fail').removeClass('d-block');
                                        $('.twilio_address_item').removeClass('warning');
                                    }

                                    $('#change_phone_modal').modal('show');
                                    $('#default_checkout_btn').show();
                                    $('.subscription_loader').hide();
                                }
                                else{
                                    update_subscription(stripe_form_token);
                                }
                            }
                        }
                    });
                }
            }
            else{
                $('.subscription_loader').show();
                $('#default_checkout_btn').hide();
                stripe.createToken(cardNumber).then(function(result) {
                    if (result.error) {
                        $('#stripe_error').text(result.error.message);
                    }
                    else {
                        $('#stripe_error').empty();
                        update_card(result.token.id);
                    }
                });
            }

            return false;
        });

        $(document).on('click','#card_checkout_btn',function(){
            stripe_form_token = false;
            $(this).hide();
            $('.card_checkout_loader').show();
            if (phone_number_state) {
                update_subscription(null);
            }
            else{
                var expire_status = has_expired_subscription();
                if (expire_status.expired) {
                    /**Show Modal*/
                    if (expire_status.phone_lost) {
                        $('#phone_number_change_options').hide();
                        $('#change_phone_number_yes').trigger('click');
                    }
                    else{
                        $('#phone_number_change_options').show();
                        $('#change_phone_number_no').removeClass('active');
                        $('#change_phone_number_yes').removeClass('active');
                        $('#change_number_next_btn').addClass('disabled');
                        $('#au_address_container').hide();
                        $('#phone_number_change_container').hide();
                        $('#twilio_address_validate_fail').removeClass('d-block');
                        $('.twilio_address_item').removeClass('warning');
                    }

                    $('#change_phone_modal').modal('show');
                    $(this).show();
                    $('.card_checkout_loader').hide();
                }
                else{
                    update_subscription(null);
                }
            }

            return false;
        });

        // $('.upgrade_plan').trigger('click');

        var update_subscription = function(stripe_token) {
            var subscription_type = ($('#subscription_form').attr('data-type') == 'upgrade') ? $('.plan-item.active').closest('.plan_item').attr('data-type') : '';
            $('.card_update_loader').show();
            $.post('/settings/update/subscription',{ token: stripe_token, subscription: subscription_type, phone_number_address: phone_number_address, discount_code: discount_code_obj.code },function(data){
                if (data.status) {
                    if (data.open_notifications) {
                        App.notifications.items = data.notifications;
                        App.notifications.has_more_items = data.has_more_items;
                        $('#notification_icon').trigger('click');
                        $("html, body").animate({ scrollTop: 0 }, 'slow');
                    }
                    else{
                        App.render_message('success','Subscription upgraded successfully');
                    }
                    setTimeout(function(){
                        location.reload();
                    },1500);
                }
                else{
                    $('.subscription_loader').hide();
                    $('.card_checkout_loader').hide();
                    $('#card_checkout_btn').show();
                    if (data.open_notifications) {
                        App.notifications.items = data.notifications;
                        App.notifications.has_more_items = data.has_more_items;
                        $('#notification_icon').trigger('click');
                        $("html, body").animate({ scrollTop: 0 }, 'slow');
                    }
                    else{
                        App.render_message('error','Something went wrong, please make sure credit card details are correct');
                    }
                }
            },'json');
        }

        var update_card = function(stripe_token) {
            $('.subscription_loader').show();
            $.post('/settings/update/card',{ token: stripe_token },function(data){
                $('.card_update_loader').hide();
                if (data.status) {
                    App.render_message('success','Card updated successfully');
                    setTimeout(function(){
                        location.reload();
                    },1000);
                }
                else{
                    $('.subscription_loader').hide();
                    $('#default_checkout_btn').show();
                    App.render_message('error','Something went wrong, pleae make sure card credentials are correct');
                }
            },'json');
        }

        var stripe = Stripe('{{ env('STRIPE_PUBLIC_KEY') }}');
        var elementStyles = {
            base: {
                iconColor: '#20283e',
                color: '#000000',
                fontWeight: 400,
                fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                fontSize: '16px',
                fontSmoothing: 'antialiased',
                ':-webkit-autofill': {
                    color: '#fce883',
                },
                '::placeholder': {
                    opacity: 0,
                    color: '#86969E',
                },
                '.CardBrandIcon-container': {
                    left: 'auto',
                    right: 20
                }
            },
            invalid: {
                iconColor: '#4cb5f5',
                color: '#4cb5f5',
            },
        };

        var elements = stripe.elements({
            fonts: [
                {
                    cssSrc: 'https://fonts.googleapis.com/css?family=Poppins',
                },
            ]
        });

        var elementClasses = {
            focus: 'focused',
            empty: 'empty',
            invalid: 'invalid',
        };

        var cardNumber = elements.create('cardNumber', {
            showIcon: false,
            style: elementStyles,
            classes: elementClasses,
            placeholder: '',
        });

        cardNumber.mount('#card_number');
        cardNumber.on('change', function(event) {
            var displayError = document.getElementById('card_errors');
            if (event.error) {
                $('.error .message').text(event.error.message);
            } else {
                $('.error .message').text('');
            }
        });

        var cardExpiry = elements.create('cardExpiry', {
            style: elementStyles,
            classes: elementClasses,
            placeholder: ' ',
        });

        cardExpiry.mount('#expiry_date');

        var cardCvc = elements.create('cardCvc', {
            style: elementStyles,
            classes: elementClasses,
            placeholder: ' ',
        });
        cardCvc.mount('#cvv_code');
        registerElements([cardNumber, cardExpiry, cardCvc], 'card-container','default');

        function registerElements(elements, exampleName) {
            var formClass = '.' + exampleName;
            var example = document.querySelector(formClass);

            var form = example.querySelector('form');
            var error = document.getElementById('stripe_error');

            function enableInputs() {
                Array.prototype.forEach.call(
                    form.querySelectorAll(
                        "input[type='text'], input[type='email'], input[type='tel']"
                    ),
                    function(input) {
                        input.removeAttribute('disabled');
                    }
                );
            }

            function disableInputs() {
                Array.prototype.forEach.call(
                    form.querySelectorAll(
                        "input[type='text'], input[type='email'], input[type='tel']"
                    ),
                    function(input) {
                        input.setAttribute('disabled', 'true');
                    }
                );
            }

            function triggerBrowserValidation() {
                var submit = document.createElement('input');
                submit.type = 'submit';
                submit.style.display = 'none';
                form.appendChild(submit);
                submit.click();
                submit.remove();
            }

            // Listen for errors from each Element, and show error messages in the UI.
            var savedErrors = {};
            elements.forEach(function(element, idx) {
                element.on('change', function(event) {
                    if (event.error) {
                        error.classList.add('visible');
                        savedErrors[idx] = event.error.message;
                        error.innerText = event.error.message;
                    } else {
                        savedErrors[idx] = null;
                        error.innerText = '';
                        var nextError = Object.keys(savedErrors)
                            .sort()
                            .reduce(function(maybeFoundError, key) {
                                return maybeFoundError || savedErrors[key];
                            }, null);

                        if (nextError) {
                            error.innerText = nextError;
                        } else {
                            error.classList.remove('visible');
                        }
                    }
                });
            });
        }

        var has_expired_subscription = function(){
            var status = { expired: false, phone_lost: false };
            $.ajax({
                url: '/settings/check/subscription',
                type: 'POST',
                dataType: 'json',
                async: false,
                success: function(data){
                    if (data.status) {
                        if (data.expired) {
                            status.expired = true;
                        }

                        if (data.phone_lost) {
                            status.phone_lost = true;
                        }
                    }
                }
            });

            return status;
        };
    });

    var show_subscription_upgrade_box = function(scroll)
    {
        @if($upcoming_subscription)
            @if($upcoming_subscription->subscription_plan_code == 'yearly' && $upcoming_subscription->is_extendable)
                $('.plan_item[data-type="pro"]').remove();
            @endif
        @else
            @if($current_subscription && $current_subscription->subscription_plan_code == 'pro' && $current_subscription->is_extendable)
                $('.plan_item[data-type="pro"]').remove();
            @endif
        @endif

        $('#plans_container,#total_price_container').show();
        $('.plan_item').first().trigger('click');
        @if($has_payment_method)
            $('#payment_container').hide();
            $('#card_checkout_container').show();
        @else
            $('#payment_container').show();
            $('#card_checkout_container').hide();
        @endif
        $('#subscription_form').attr('data-type','upgrade');
        $('#default_checkout_btn').text('Checkout');
        if ($('#update_card_container').length) {
            $('#update_card_container').show();
        }

        if (scroll) {
            $('body,html').animate({ scrollTop: $('#discount_box_container').offset().top - 200 }, { duration: 900 });
        }
    }

    var format_number = function(price){
        return price.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }
</script>
<script type="text/template" id="new_phone_template">
    <form id="new_phone_form">
        <div id="new_phone_container" style="display: none;">
            <img src="/images/loader.png" width="24px" class="float-left">
            <span class="float-left ml-1 loader-text">Processing</span>
        </div>
        <button type="submit" class="btn btn-primary" id="save_new_phone_btn">Save</button>
    </form>
</script>
@endsection
