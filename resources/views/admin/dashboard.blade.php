@extends('layouts.master')
@section('view_css')
<link rel="stylesheet" href="/js/select2/css/select2.min.css">
@endsection
@section('content')
    @include('admin.left_sidebar_admin_menu',['active_page' => 'dashboard'])
    <div class="col-md-auto col-12 content-wrap client-profile dashboard">
        <div class="content-inner">
            <div class="page-title d-flex align-items-center" data-select2-id="select2-data-6-6dcb">
                <h2>Dashboard</h2>
                <div class="select-date d-flex align-items-center ml-auto">
                    <select class="tradieflow-select-dropdown" name="selectDate">
                        <option value="last-years">Last years</option>
                        <option value="last3-months">Last 3 months</option>
                        <option value="last30-days">Last 30 days</option>
                        <option value="last10-days">Last 10 days</option>
                        <option value="last10-days">Last 7 days</option>
                    </select>
                </div>
            </div>
            <div class="statistical-boxes">
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-auto box-item">
                        <div class="statistical-boxe-item stage lead">
                            <figure>
                                <img src="/images/dashboard-lead-icon.svg" alt="Lead icon">
                            </figure>
                            <div class="info">
                                <span>Leads</span>
                                <h2>374</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-auto box-item">
                        <div class="statistical-boxe-item stage quote-meeting">
                            <figure>
                                <img src="/images/dashboard-meetings-icon.svg" alt="Meetings calendar icon">
                            </figure>
                            <div class="info">
                                <span>Meetings</span>
                                <h2>165</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-auto box-item">
                        <div class="statistical-boxe-item sales">
                            <div class="figure">
                                <img src="/images/dashboard-sales-Icons.svg" alt="Sales icon">
                            </div>
                            <div class="info">
                                <span>Sales</span>
                                <h2>46</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-auto box-item">
                        <div class="statistical-boxe-item">
                            <div class="figure">
                                <img src="/images/money-icon-green.svg" alt="Money icon green">
                            </div>
                            <div class="info">
                                <span>Sales Value</span>
                                <h2>$ 145,000</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-widget statistics-chart">
                <div class="widget-heading d-flex align-items-center">
                    <h2>Statistics</h2>
                    <ul class="chart-hint d-flex align-items-center ml-auto">
                        <li class="hint-item lead">Leads</li>
                        <li class="hint-item quote-meeting">Meetings</li>
                        <li class="hint-item sales">Sales</li>
                        <li class="hint-item sales-value">Sales Value</li>
                    </ul>
                </div>
                <div class="widget-bdoy">
                    <div class="chart-wrapper">
                        <img src="/images/statistics-chart-img.png" alt="Statostocs">
                    </div>
                </div>
            </div>
            <div class="services-wrapper">
                <div class="row">
                    <div class="col-xl-4 col-md-6 col-12">
                        <div class="profile-widget most-popular-service">
                            <h2>Most Popular Services</h2>
                            <div class="popular-service-chart">
                                <img src="/images/most-popular-servicescharts.svg" alt="Most popular services charts">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-12">
                        <div class="profile-widget most-payble-clients">
                            <h2>Largest Customers</h2>
                            <ul class="most-payble-clients-wrapper">
                                <li class="client-item d-flex align-items-center" data-color="green">
                                    <figure class="icon">
                                        <span>MG</span>
                                    </figure>
                                    <span class="name">Mira George</span>
                                    <span class="value ml-auto">$45,000</span>
                                </li>
                                <li class="client-item d-flex align-items-center" data-color="green">
                                    <figure class="icon">
                                        <span>PG</span>
                                    </figure>
                                    <span class="name">Paityn Geidt</span>
                                    <span class="value ml-auto">$35,000</span>
                                </li>
                                <li class="client-item d-flex align-items-center" data-color="blue">
                                    <figure class="icon">
                                        <span>AD</span>
                                    </figure>
                                    <span class="name">Adison Dorwart</span>
                                    <span class="value ml-auto">$25,000</span>
                                </li>
                                <li class="client-item d-flex align-items-center" data-color="green">
                                    <figure class="icon">
                                        <span>RH</span>
                                    </figure>
                                    <span class="name">Ryan Herwitz</span>
                                    <span class="value ml-auto">$15,000</span>
                                </li>
                                <li class="client-item d-flex align-items-center" data-color="blue">
                                    <figure class="icon">
                                        <span>CC</span>
                                    </figure>
                                    <span class="name">Cristofer Calzoni</span>
                                    <span class="value ml-auto">$10,000</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 col-xl-4 col-12">
                        <div class="row">
                            <div class="col-12 col-xl-12 col-md-6">
                                <div class="profile-widget total-info">
                                    <h2>Total Invoiced</h2>
                                    <div class="info-row row">
                                        <div class="col-sm-5 col-12 col-md-12 col-lg-5 order-sm-2 order-md-1 order-lg-2 ml-sm-auto">
                                            <div class="cart-figure">
                                                <img src="/images/total-invoice-chart.svg" alt="Total invoice">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-7 col-md-12 col-lg-7 order-md-2 order-lg-1 order-sm-1">
                                            <span class="price">$ 145,000</span>
                                            <div class="stage-icon stage-up">+10% this month</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-xl-12 col-md-6">
                                <div class="profile-widget total-info">
                                    <h2>Total Collected</h2>
                                    <div class="info-row row">
                                        <div class="col-sm-5 col-12 col-md-12 col-lg-5 order-sm-2 order-md-1 order-lg-2 ml-sm-auto">
                                            <div class="cart-figure">
                                                <img src="/images/total-collected-chart.svg" alt="Total collected">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-7 col-md-12 col-lg-7 order-md-2 order-lg-1 order-sm-1">
                                            <span class="price">$ 91,000</span>
                                            <div class="stage-icon stage-down">-22% this month</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-12">
                        <div class="profile-widget sales-funnel">
                            <h2>Sales Funnel</h2>
                            <div class="sales-funnel-chart">
                                <img src="/images/sales-funnel-chart.svg" alt="Sales funnel">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-12">
                        <div class="profile-widget avarage-sales-cycle">
                            <h2>Avarage Sales Cycle</h2>
                            <div class="days">18 days <span class="stage-icon stage-up">10%</span></div>
                            <h5>From Lead to Job Completed compared to last month’s statistics</h5>
                            <ul class="process-time">
                                <li>
                                    <div class="info d-flex">
                                        <h5>Not Listed</h5>
                                        <h6 class="day">5 days</h6>
                                    </div>
                                    <div class="process" data-process="5"></div>
                                </li>
                                <li>
                                    <div class="info d-flex">
                                        <h5>Lead</h5>
                                        <h6 class="day">4 days</h6>
                                    </div>
                                    <div class="process" data-process="4"></div>
                                </li>
                                <li>
                                    <div class="info d-flex">
                                        <h5>Meeting Scheduled</h5>
                                        <h6 class="day">10 days</h6>
                                    </div>
                                    <div class="process" data-process="10"></div>
                                </li>
                                <li>
                                    <div class="info d-flex">
                                        <h5>Booked Job</h5>
                                        <h6 class="day">20 days</h6>
                                    </div>
                                    <div class="process" data-process="20"></div>
                                </li>
                                <li>
                                    <div class="info d-flex">
                                        <h5>Job Completed</h5>
                                        <h6 class="day">3 days</h6>
                                    </div>
                                    <div class="process" data-process="3"></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('view_script')
<script src="/js/popper.min.js" type="text/javascript"></script>
<script src="/js/select2/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.tradieflow-select-dropdown').select2();
    });
</script>
@endsection
