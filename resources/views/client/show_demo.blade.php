@extends('layouts.master')
@section('view_css')
    <link rel="stylesheet" href="/js/jquery-confirm/jquery-confirm.min.css">
    <link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css">
@endsection
@section('content')
    @if($auth_user->role == 'sales')
        @include('admin.left_sidebar_admin_menu',['active_page' => 'client'])
    @else
        @include('dashboard.left_sidebar_full_menu',['active_page' => 'client'])
    @endif
    <div class="col-md-auto col-12 content-wrap client-profile">
        <div class="content-inner">
            <div class="client-name d-flex align-items-center">
                <h2>Thomas Smith</h2>
                <div class="start-call d-flex align-items-center ml-sm-auto">
                    <button class="btn start-call-btn ml-auto">
                        <span>Start Call</span>
                        <img class="icon" src="/images/call-icon-white.svg" alt="Call icon">
                    </button>
                </div>
            </div>
            <div class="statistical-boxes">
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-auto box-item">
                        <div class="statistical-boxe-item stage in-progress">
                            <figure>
                                <img src="/images/work-in-progress.svg" alt="Work in progress icon">
                            </figure>
                            <div class="info">
                                <span>Stage</span>
                                <h2>Work <small>in progress</small></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-auto box-item">
                        <div class="statistical-boxe-item payment-type outstanding-payments">
                            <div class="figure">
                                <div class="circle-progress" data-progress="75">
                                    <svg>
                                        <circle cx="22.5" cy="22.5" r="22.5" fill="none" stroke="#eff2f9" stroke-width="2"></circle>
                                        <circle cx="22.5" cy="22.5" r="22.5" fill="none" stroke="#43d14f" stroke-width="2"></circle>
                                    </svg>
                                    <h6>75<span>%</span></h6>
                                </div>
                            </div>
                            <div class="info">
                                <span>Transferred Payments</span>
                                <h2>$ 1,200 <small>Mar 12</small></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-auto box-item">
                        <div class="statistical-boxe-item">
                            <div class="figure">
                                <img src="/images/money-icon-green.svg" alt="Money icon green">
                            </div>
                            <div class="info">
                                <span>Total Earned</span>
                                <h2>$ 14,500</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-auto box-item">
                        <div class="statistical-boxe-item">
                            <div class="figure">
                                <img src="/images/score-icon-green.svg" alt="Score icon green">
                            </div>
                            <div class="info">
                                <span>Score</span>
                                <h2>80 <small>points</small></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-xl-3 actions-widget-wrapper order-xl-2">
                    <div class="quick-action profile-widget">
                        <h6>Quick Actions</h6>
                        <div class="action-wrap">
                            <div class="row box-row">
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" class="action-box d-flex align-items-center event">
                                        <img src="/images/action-calendar.svg" alt="Calendar icon orange" class="icon">
                                        <div class="info">Add Calendar Event</div>
                                    </a>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" class="action-box d-flex align-items-center send-message">
                                        <img src="/images/action-message.svg" alt="Message icon" class="icon">
                                        <div class="info">Send Text Message</div>
                                    </a>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" class="action-box d-flex align-items-center follow-up">
                                        <img src="/images/action-reminder.svg" alt="Reminder icon" class="icon">
                                        <div class="info">Set a Follow-up</div>
                                    </a>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" class="action-box d-flex align-items-center send-quote">
                                        <img src="/images/action-quote.svg" alt="Quote icon" class="icon">
                                        <div class="info">Send Quote</div>
                                    </a>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" class="action-box d-flex align-items-center send-invoice">
                                        <img src="/images/action-invoicevia-text.svg" alt="Invoice text icon" class="icon">
                                        <div class="info">Send Invoice via Text</div>
                                    </a>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" class="action-box d-flex align-items-center send-invoice">
                                        <img src="/images/action-invoicevia-email.svg" alt="Invoice email icon" class="icon">
                                        <div class="info">Send Invoice via Email</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="progress-boxes profile-widget">
                        <h6>What is your Progress?</h6>
                        <div class="boxes-wrap">
                            <div class="row box-row">
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" class="action-box d-flex align-items-center lead">
                                        <figure class="icon">
                                            <img src="/images/progress-lead-icon.svg" alt="Lead icon">
                                        </figure>
                                        <div class="info">This became a Lead</div>
                                    </a>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" class="action-box d-flex align-items-center not-lead">
                                        <figure class="icon">
                                            <img src="/images/progress-no-lead-icon.svg" alt="Not lead icon">
                                        </figure>
                                        <div class="info">This is not a Lead</div>
                                    </a>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" class="action-box d-flex align-items-center unknown">
                                        <figure class="icon">
                                            <img src="/images/progress-unknown-icon.svg" alt="Progress unknown icon">
                                        </figure>
                                        <div class="info">Don’t know yet</div>
                                    </a>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" class="action-box d-flex align-items-center cancelled">
                                        <figure class="icon">
                                            <img src="/images/cancelled-icon.svg" alt="Cancelled icon">
                                        </figure>
                                        <div class="info">Cancelled</div>
                                    </a>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" class="action-box d-flex align-items-center work-in-progress">
                                        <figure class="icon">
                                            <img src="/images/work-in-progress.svg" alt="Invoice text icon">
                                        </figure>
                                        <div class="info">Work in Progress</div>
                                    </a>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" class="action-box d-flex align-items-center completed">
                                        <figure class="icon">
                                            <img src="/images/payment-received-icon.svg" alt="Invoice email icon">
                                        </figure>
                                        <div class="info">Payment Received</div>
                                    </a>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" class="action-box d-flex align-items-center cancelled">
                                        <figure class="icon">
                                            <img src="/images/payment-not-received-icon.svg" alt="Invoice email icon">
                                        </figure>
                                        <div class="info">Payment Not Received Yet</div>
                                    </a>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" class="action-box d-flex align-items-center add-new-project">
                                        <figure class="icon">
                                            <img src="/images/add-new-project-icon.svg" alt="Add new project icon">
                                        </figure>
                                        <div class="info">Add New Project</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-9 sections-wrapper order-xl-1">
                    <div class="adding-note profile-widget form-active">
                        <div class="widget-heading d-flex align-items-center">
                            <h6>Notes</h6>
                            <button class="btn add-note-btn btn-primary btn--round d-flex align-items-center ml-auto">
                                <img src="/images/plus-Icons.svg" alt="Plus icon" class="icon">
                                <span>Add New</span>
                            </button>
                        </div>
                        <div class="widget-bdoy">
                            <div class="add-note-form add-new">
                                <form>
                                    <label for="newNote">Add Note</label>
                                    <div class="form-group">
                                        <textarea class="form-control" id="newNote" placeholder="Start typing..."></textarea>
                                        <label for="newNote">Start typing...</label>
                                    </div>
                                    <div class="btn-row d-flex">
                                        <button class="btn btn-secondary btn--sqr ml-auto cancel-btn">Cancel</button>
                                        <button class="btn btn-primary btn--sqr submit-btn">Send</button>
                                    </div>
                                </form>
                            </div>
                            <div class="note-wrapper scrollable-contents">
                                <ul class="note-items">
                                    <li class="note-item row no-gutters">
                                        <div class="col-auto time">
                                            <span>12:30</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas quis rutrum massa. Ut nec efficitur est.</p>
                                        </div>
                                    </li>
                                    <li class="note-item row no-gutters">
                                        <div class="col-auto time">
                                            <span>Mar 21</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse scelerisque, lacus nec ultrices interdum, purus metus tincidunt diam, a vehicula purus orci in turpis.</p>
                                        </div>
                                    </li>
                                    <li class="note-item row no-gutters">
                                        <div class="col-auto time">
                                            <span>Mar 19</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse scelerisque, lacus nec ultrices interdum, purus metus tincidunt diam, a vehicula purus orci in turpis. Quisque maximus ex at velit hendrerit faucibus. Cras rhoncus ornare elit nec cursus. Cras non ligula lacus.</p>
                                        </div>
                                    </li>
                                    <li class="note-item row no-gutters">
                                        <div class="col-auto time">
                                            <span>Mar 17</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas quis rutrum massa. Ut nec efficitur est.</p>
                                        </div>
                                    </li>
                                    <li class="note-item row no-gutters">
                                        <div class="col-auto time">
                                            <span>12:30</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas quis rutrum massa. Ut nec efficitur est.</p>
                                        </div>
                                    </li>
                                    <li class="note-item row no-gutters">
                                        <div class="col-auto time">
                                            <span>Mar 21</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse scelerisque, lacus nec ultrices interdum, purus metus tincidunt diam, a vehicula purus orci in turpis.</p>
                                        </div>
                                    </li>
                                    <li class="note-item row no-gutters">
                                        <div class="col-auto time">
                                            <span>Mar 19</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse scelerisque, lacus nec ultrices interdum, purus metus tincidunt diam, a vehicula purus orci in turpis. Quisque maximus ex at velit hendrerit faucibus. Cras rhoncus ornare elit nec cursus. Cras non ligula lacus.</p>
                                        </div>
                                    </li>
                                    <li class="note-item row no-gutters">
                                        <div class="col-auto time">
                                            <span>Mar 17</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas quis rutrum massa. Ut nec efficitur est.</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="history profile-widget">
                        <div class="widget-heading d-flex align-items-center">
                            <h6>History</h6>
                        </div>
                        <div class="widget-bdoy">
                            <div class="history-item">
                                <span>Today</span>
                            </div>
                            <div class="history-item">
                                <div class="icon">
                                    <img src="/images/history-phone-icon.svg" alt="Phone icon green">
                                </div>
                                <div class="info">
                                    <p>Outgoing Call<span class="time">2 days ago</span></p>
                                    <a href="#">Listen back</a>
                                </div>
                            </div>
                            <div class="history-item">
                                <div class="icon">
                                    <img src="/images/history-money-icon.svg" alt="Money icon green">
                                </div>
                                <div class="info">
                                    <p>Upfront Value Added<span class="time">2 days ago</span></p>
                                    <span>$1,200</span>
                                </div>
                            </div>
                            <div class="history-item">
                                <div class="icon">
                                    <img src="/images/history-profile-icon.svg" alt="Profile icon green">
                                </div>
                                <div class="info">
                                    <p>Name Added<span class="time">2 days ago</span></p>
                                    <span>Thomas Sith</span>
                                </div>
                            </div>
                            <div class="history-item">
                                <div class="icon">
                                    <img src="/images/history-email.svg" alt="Email icon green">
                                </div>
                                <div class="info">
                                    <p>Email Sent<span class="time">2 days ago</span></p>
                                    <a href="#">Check Email</a>
                                </div>
                            </div>
                            <div class="history-item">
                                <div class="icon">
                                    <img src="/images/history-calendar.svg" alt="Calendar">
                                </div>
                                <div class="info">
                                    <p>Event Added<span class="time">2 days ago</span></p>
                                    <a href="#">Work · March 12, 2021 08:00 - 12:00</a>
                                </div>
                            </div>
                            <div class="history-item">
                                <div class="icon">
                                    <img src="/images/history-quote.svg" alt="Quote">
                                </div>
                                <div class="info">
                                    <p>Quote Sent<span class="time">2 days ago</span></p>
                                    <a href="#">Open · March 12, 2021 08:00</a>
                                </div>
                            </div>
                            <div class="history-item">
                                <div class="icon">
                                    <img src="/images/history-money-icon.svg" alt="Money">
                                </div>
                                <div class="info">
                                    <p>Invoice Sent<span class="time">2 days ago</span></p>
                                    <a href="#">Open · March 12, 2021 08:00</a>
                                </div>
                            </div>
                            <div class="history-item">
                                <div class="icon">
                                    <img src="/images/history-forms-icon.svg" alt="Forms">
                                </div>
                                <div class="info">
                                    <p>Form Sent<span class="time">2 days ago</span></p>
                                    <a href="#">Open · March 12, 2021 08:00</a>
                                </div>
                            </div>
                            <div class="history-item">
                                <a href="#" class="view-all">View all</a>
                            </div>
                        </div>
                    </div>
                    <div class="client-data profile-widget">
                        <div class="widget-heading d-flex align-items-center">
                            <h6>Client Data</h6>
                        </div>
                        <div class="widget-bdoy">
                            <form>
                                <div class="row client-data-row">
                                    <div class="col-12 col-lg-6">
                                        <h6>Stage</h6>
                                        <div class="form-group custom-select-group dropdown-wrap">
                                            <div class="select-toggler">
                                                <span class="label" for="selectClientStage">Client’s Stage</span>
                                                <div class="selected work-in-progress">Work in Progress</div>
                                            </div>
                                            <ul class="dropdown-items" id="selectClientStage">
                                                <li data-label="all">
                                                    <span>All</span>
                                                </li>
                                                <li data-label="lead">
                                                    <span>Leads</span>
                                                </li>
                                                <li data-label="not-listed">
                                                    <span>Not listed</span>
                                                </li>
                                                <li data-label="quote-meeting">
                                                    <span>Quote Meeting</span>
                                                </li>
                                                <li data-label="work-in-progress" data-selected="selected">
                                                    <span>Work in Progress</span>
                                                </li>
                                                <li data-label="completed">
                                                    <span>Job Completed</span>
                                                </li>
                                                <li data-label="cancelled">
                                                    <span>Cancelled</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <h6>Basic Information</h6>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="clientFullName" placeholder="Full Name" value="Thomas Smith">
                                            <label for="clientFullName">Full Name</label>
                                        </div>
                                        <div class="form-group-row form-row">
                                            <div class="form-group col-12 col-sm-6">
                                                <input type="text" class="form-control" id="upfrontValue" placeholder="Upfront Value" value="$300.00">
                                                <label for="upfrontValue">Upfront Value</label>
                                            </div>
                                            <div class="form-group col-12 col-sm-6">
                                                <input type="text" class="form-control" id="ongoingValue" placeholder="Ongoing Value" value="$300.00">
                                                <label for="ongoingValue">Ongoing Value</label>
                                            </div>
                                        </div>
                                        <button class="btn add-another add-another-project d-flex align-items-center"><img src="/images/add-icon-green.svg" alt="Pluse icon" class="icon">Add Another Project</button>
                                        <h6>Additional Data</h6>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="companyName" placeholder="Company">
                                            <label for="companyName">Company</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="companyEmail" placeholder="Email" value="thomas.smith@gmail.com">
                                            <label for="companyEmail">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <h6>Progress</h6>
                                        <div class="form-group">
                                            <img src="/images/progress-slide.svg" alt="Progress">
                                        </div>
                                        <h6>Location</h6>
                                        <div class="form-group-row form-row">
                                            <div class="form-group col-12 col-sm-6">
                                                <input type="text" class="form-control" id="city" placeholder="City" value="Sidney">
                                                <label for="city">City</label>
                                            </div>
                                            <div class="form-group col-12 col-sm-6">
                                                <input type="text" class="form-control" id="zipCode" placeholder="ZIP" value="NSW 2031">
                                                <label for="zipCode">ZIP</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="addressLine" placeholder="Address Line" value="50 Missenden Rd">
                                            <label for="addressLine">Address Line</label>
                                        </div>
                                        <button class="btn add-another d-flex align-items-center"><img src="/images/add-icon-green.svg" alt="Pluse icon" class="icon">Add Another</button>
                                        <h6>Phone Number</h6>
                                        <div class="form-group phone-number-field no-gutters d-flex">
                                            <div class="select-flag d-flex align-items-center col-auto">
                                                <div class="dropdown-wrap position-relative">
                                                    <div class="selected-flag"><img src="/images/au.svg" alt=""></div>
                                                    <ul id="countryFlag" class="dropdown-items position-absolute">
                                                        <li><img src="/images/au.svg" alt="Australia"> <span>+1</span></li>
                                                        <li><img src="/images/ca.svg" alt="Canada"> <span>+1</span></li>
                                                        <li><img src="/images/us.svg" alt="Us"> <span>+1</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="form-group col-auto mb-0">
                                                <input type="text" class="form-control" id="phoneNumber" placeholder="Phone Number" value="+61 123 456 789">
                                                <label for="phoneNumber">Phone Number</label>
                                            </div>
                                        </div>
                                        <button class="btn add-another d-flex align-items-center"><img src="/images/add-icon-green.svg" alt="Pluse icon" class="icon">Add Another</button>
                                    </div>
                                </div>
                                <div class="row client-data-row multiple-items-row">
                                    <div class="col-12"><h2 class="pt-4">Multiple items</h2></div>
                                    <div class="col-12 col-lg-6">
                                        <h6>Basic Information</h6>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="clientFullName" placeholder="Full Name" value="Thomas Smith">
                                            <label for="clientFullName">Full Name</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        &nbsp;
                                    </div>
                                    <div class="col-12 col-lg-6 project-item">
                                        <h6>Project #1</h6>
                                        <div class="form-group-row form-row align-items-center">
                                            <div class="form-group col-auto info-field-wrap">
                                                <input type="text" class="form-control" id="projectName1" placeholder="Project Name" value="Renovation">
                                                <label for="projectName1">Project Name</label>
                                            </div>
                                            <div class="form-group col-auto delete-button-wrap">
                                                <button class="btn remove-item"><img src="/images/delete-red.svg" alt="Delete icon red"></button>
                                            </div>
                                        </div>
                                        <div class="form-group custom-select-group dropdown-wrap">
                                            <div class="select-toggler">
                                                <span class="label" for="selectClientStage1">Client’s Stage</span>
                                                <div class="selected work-in-progress">Work in Progress</div>
                                            </div>
                                            <ul class="dropdown-items" id="selectClientStage1">
                                                <li data-label="all">
                                                    <span>All</span>
                                                </li>
                                                <li data-label="lead">
                                                    <span>Leads</span>
                                                </li>
                                                <li data-label="not-listed">
                                                    <span>Not listed</span>
                                                </li>
                                                <li data-label="quote-meeting">
                                                    <span>Quote Meeting</span>
                                                </li>
                                                <li data-label="work-in-progress" data-selected="selected">
                                                    <span>Work in Progress</span>
                                                </li>
                                                <li data-label="completed">
                                                    <span>Job Completed</span>
                                                </li>
                                                <li data-label="cancelled">
                                                    <span>Cancelled</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="form-group progress-group">
                                            <label for="progress1">Progress</label>
                                            <img src="/images/progress-slide.svg" alt="Progress">
                                        </div>
                                        <div class="form-group-row form-row">
                                            <div class="form-group col-12 col-sm-6">
                                                <input type="text" class="form-control" id="upfrontValue1" placeholder="Upfront Value" value="$300.00">
                                                <label for="upfrontValue1">Upfront Value</label>
                                            </div>
                                            <div class="form-group col-12 col-sm-6">
                                                <input type="text" class="form-control" id="ongoingValue1" placeholder="Ongoing Value" value="$300.00">
                                                <label for="ongoingValue1">Ongoing Value</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6 project-item">
                                        <h6>Project #2</h6>
                                        <div class="form-group-row form-row align-items-center">
                                            <div class="form-group col-auto info-field-wrap">
                                                <input type="text" class="form-control" id="projectName2" placeholder="Project Name" value="Kitchen Furniture">
                                                <label for="projectName2">Project Name</label>
                                            </div>
                                            <div class="form-group col-auto delete-button-wrap">
                                                <button class="btn remove-item"><img src="/images/delete-red.svg" alt="Delete icon red"></button>
                                            </div>
                                        </div>
                                        <div class="form-group custom-select-group dropdown-wrap">
                                            <div class="select-toggler">
                                                <span class="label" for="selectClientStage2">Client’s Stage</span>
                                                <div class="selected work-in-progress">Work in Progress</div>
                                            </div>
                                            <ul class="dropdown-items" id="selectClientStage2">
                                                <li data-label="all">
                                                    <span>All</span>
                                                </li>
                                                <li data-label="lead">
                                                    <span>Leads</span>
                                                </li>
                                                <li data-label="not-listed">
                                                    <span>Not listed</span>
                                                </li>
                                                <li data-label="quote-meeting">
                                                    <span>Quote Meeting</span>
                                                </li>
                                                <li data-label="work-in-progress" data-selected="selected">
                                                    <span>Work in Progress</span>
                                                </li>
                                                <li data-label="completed">
                                                    <span>Job Completed</span>
                                                </li>
                                                <li data-label="cancelled">
                                                    <span>Cancelled</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="form-group progress-group">
                                            <label for="progress2">Progress</label>
                                            <img src="/images/progress-slide.svg" alt="Progress">
                                        </div>
                                        <div class="form-group-row form-row">
                                            <div class="form-group col-12 col-sm-6">
                                                <input type="text" class="form-control" id="upfrontValue2" placeholder="Upfront Value" value="$2,300.00">
                                                <label for="upfrontValue2">Upfront Value</label>
                                            </div>
                                            <div class="form-group col-12 col-sm-6">
                                                <input type="text" class="form-control" id="ongoingValue2" placeholder="Ongoing Value">
                                                <label for="ongoingValue2">Ongoing Value</label>
                                            </div>
                                        </div>
                                        <button class="btn add-another add-another-project d-flex align-items-center"><img src="/images/add-icon-green.svg" alt="Pluse icon" class="icon">Add Another Project</button>
                                    </div>
                                    <div class="col-12 col-lg-6 location-item">
                                        <h6>Location #1</h6>
                                        <div class="form-group-row form-row align-items-center">
                                            <div class="form-group col-auto info-field-wrap">
                                                <div class="form-group-row form-row">
                                                    <div class="form-group col-12 col-sm-6">
                                                        <input type="text" class="form-control" id="city1" placeholder="City" value="Sidney">
                                                        <label for="city1">City</label>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6">
                                                        <input type="text" class="form-control" id="zipCode1" placeholder="ZIP" value="NSW 2031">
                                                        <label for="zipCode1">ZIP</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-auto delete-button-wrap">
                                                <button class="btn remove-item"><img src="/images/delete-red.svg" alt="Delete icon red"></button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="addressLine1" placeholder="Address Line" value="50 Missenden Rd">
                                            <label for="addressLine1">Address Line</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6 location-item">
                                        <h6>Location #2</h6>
                                        <div class="form-group-row form-row align-items-center">
                                            <div class="form-group col-auto info-field-wrap">
                                                <div class="form-group-row form-row">
                                                    <div class="form-group col-12 col-sm-6">
                                                        <input type="text" class="form-control" id="city2" placeholder="City" value="Sidney">
                                                        <label for="city2">City</label>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6">
                                                        <input type="text" class="form-control" id="zipCode2" placeholder="ZIP" value="NSW 2031">
                                                        <label for="zipCode2">ZIP</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-auto delete-button-wrap">
                                                <button class="btn remove-item"><img src="/images/delete-red.svg" alt="Delete icon red"></button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="addressLine2" placeholder="Address Line" value="50 Missenden Rd">
                                            <label for="addressLine2">Address Line</label>
                                        </div>
                                        <button class="btn add-another d-flex align-items-center"><img src="/images/add-icon-green.svg" alt="Pluse icon" class="icon">Add Another</button>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <h6>Additional Data</h6>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="companyName1" placeholder="Company">
                                            <label for="companyName1">Company</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="companyEmail1" placeholder="Email" value="thomas.smith@gmail.com">
                                            <label for="companyEmail1">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <h6>Phone Number</h6>
                                        <div class="form-group-row form-row align-items-center">
                                            <div class="form-group col-auto info-field-wrap phone-number-field no-gutters d-flex">
                                                <div class="select-flag d-flex align-items-center col-auto">
                                                    <div class="dropdown-wrap position-relative">
                                                        <div class="selected-flag"><img src="/images/au.svg" alt=""></div>
                                                        <ul id="countryFlag2" class="dropdown-items position-absolute">
                                                            <li><img src="/images/au.svg" alt="Australia"> <span>+1</span></li>
                                                            <li><img src="/images/ca.svg" alt="Canada"> <span>+1</span></li>
                                                            <li><img src="/images/us.svg" alt="Us"> <span>+1</span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="form-group col-auto mb-0">
                                                    <input type="text" class="form-control" id="phoneNumber" placeholder="Phone Number" value="+61 123 456 789">
                                                    <label for="phoneNumber">Phone Number</label>
                                                </div>
                                            </div>
                                            <div class="form-group col-auto delete-button-wrap">
                                                <button class="btn remove-item"><img src="/images/delete-red.svg" alt="Delete icon red"></button>
                                            </div>
                                        </div>
                                        <div class="form-group-row form-row align-items-center">
                                            <div class="form-group col-auto info-field-wrap phone-number-field no-gutters d-flex">
                                                <div class="select-flag d-flex align-items-center col-auto">
                                                    <div class="dropdown-wrap position-relative">
                                                        <div class="selected-flag"><img src="/images/au.svg" alt=""></div>
                                                        <ul id="countryFlag3" class="dropdown-items position-absolute">
                                                            <li><img src="/images/au.svg" alt="Australia"> <span>+1</span></li>
                                                            <li><img src="/images/ca.svg" alt="Canada"> <span>+1</span></li>
                                                            <li><img src="/images/us.svg" alt="Us"> <span>+1</span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="form-group col-auto mb-0">
                                                    <input type="text" class="form-control" id="phoneNumber" placeholder="Phone Number" value="+61 123 456 789">
                                                    <label for="phoneNumber">Phone Number</label>
                                                </div>
                                            </div>
                                            <div class="form-group col-auto delete-button-wrap">
                                                <button class="btn remove-item"><img src="/images/delete-red.svg" alt="Delete icon red"></button>
                                            </div>
                                        </div>
                                        <button class="btn add-another d-flex align-items-center"><img src="/images/add-icon-green.svg" alt="Pluse icon" class="icon">Add Another</button>
                                    </div>
                                    <div class="col-12 col-lg-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn save-change-btn btn-primary btn--sqr">Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="emails profile-widget">
                        <div class="widget-heading d-flex align-items-center">
                            <h6>Emails</h6>
                            <button class="btn send-new-mail-btn btn-primary btn--round d-flex align-items-center ml-auto">
                                <img src="/images/plus-Icons.svg" alt="Plus icon" class="icon">
                                <span>Send New</span>
                            </button>
                        </div>
                        <div class="widget-bdoy">
                            <div class="send-email-form add-new">
                                <form>
                                    <label for="sendMailSubmit">Send Email</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="sendMailSubmit" placeholder="Subject">
                                        <label for="sendMailSubmit">Subject</label>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" id="sendMailText" placeholder="Your Message"></textarea>
                                        <label for="sendMailSubmit">Your Message</label>
                                    </div>
                                    <div class="btn-row d-flex">
                                        <button class="btn btn-secondary btn--sqr ml-auto cancel-btn">Cancel</button>
                                        <button class="btn btn-primary btn--sqr submit-btn">Send</button>
                                    </div>
                                </form>
                            </div>
                            <div class="email-wrapper scrollable-contents">
                                <ul class="email-items">
                                    <li class="email-item row no-gutters">
                                        <div class="col-auto name">
                                            <span>Thomas Smith</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p><span class="subject">Offer Calculations</span> - Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                        </div>
                                        <div class="col-auto attatchment ml-auto">
                                            <img src="/images/attached-icon.svg" alt="Attached icon">
                                        </div>
                                        <div class="col-auto time">
                                            <span>12:30</span>
                                        </div>
                                    </li>
                                    <li class="email-item row no-gutters">
                                        <div class="col-auto name">
                                            <span>Thomas Smith</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p><span class="subject">Offer Calculations</span> - Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                        </div>
                                        <div class="col-auto time ml-auto">
                                            <span>Mar 22</span>
                                        </div>
                                    </li>
                                    <li class="email-item row no-gutters">
                                        <div class="col-auto name">
                                            <span>Thomas Smith</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p><span class="subject">Offer Calculations</span> - Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                        </div>
                                        <div class="col-auto time ml-auto">
                                            <span>Mar 22</span>
                                        </div>
                                    </li>
                                    <li class="email-item row no-gutters">
                                        <div class="col-auto name">
                                            <span>Thomas Smith</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p><span class="subject">Offer Calculations</span> - Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                        </div>
                                        <div class="col-auto attatchment ml-auto">
                                            <img src="/images/attached-icon.svg" alt="Attached icon">
                                        </div>
                                        <div class="col-auto time">
                                            <span>Mar 22</span>
                                        </div>
                                    </li>
                                    <li class="email-item row no-gutters">
                                        <div class="col-auto name">
                                            <span>Thomas Smith</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p><span class="subject">Offer Calculations</span> - Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                        </div>
                                        <div class="col-auto attatchment ml-auto">
                                            <img src="/images/attached-icon.svg" alt="Attached icon">
                                        </div>
                                        <div class="col-auto time">
                                            <span>Mar 22</span>
                                        </div>
                                    </li>
                                    <li class="email-item row no-gutters">
                                        <div class="col-auto name">
                                            <span>Thomas Smith</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p><span class="subject">Offer Calculations</span> - Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                        </div>
                                        <div class="col-auto attatchment ml-auto">
                                            <img src="/images/attached-icon.svg" alt="Attached icon">
                                        </div>
                                        <div class="col-auto time">
                                            <span>Mar 22</span>
                                        </div>
                                    </li>
                                    <li class="email-item row no-gutters">
                                        <div class="col-auto name">
                                            <span>Thomas Smith</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p><span class="subject">Offer Calculations</span> - Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                        </div>
                                        <div class="col-auto attatchment ml-auto">
                                            <img src="/images/attached-icon.svg" alt="Attached icon">
                                        </div>
                                        <div class="col-auto time">
                                            <span>Mar 22</span>
                                        </div>
                                    </li>
                                    <li class="email-item row no-gutters">
                                        <div class="col-auto name">
                                            <span>Thomas Smith</span>
                                        </div>
                                        <div class="col-auto details">
                                            <p><span class="subject">Offer Calculations</span> - Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                        </div>
                                        <div class="col-auto attatchment ml-auto">
                                            <img src="/images/attached-icon.svg" alt="Attached icon">
                                        </div>
                                        <div class="col-auto time">
                                            <span>Mar 22</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tasks profile-widget">
                        <div class="widget-heading d-flex align-items-center">
                            <h6>Tasks (8)</h6>
                            <button class="btn add-task-btn btn-primary btn--round d-flex align-items-center ml-auto">
                                <img src="/images/plus-Icons.svg" alt="Plus icon" class="icon">
                                <span>Add New</span>
                            </button>
                        </div>
                        <div class="widget-bdoy">
                            <div class="add-task-form add-new">
                                <form>
                                    <label>Add New Task</label>
                                    <div class="form-group select-group">
                                        <select id="selectClient" class="form-control">
                                            <option selected="">None (Global Task)</option>
                                            <option>None (Global Task)</option>
                                            <option>None (Global Task)</option>
                                        </select>
                                        <label for="selectClient">Select Client</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="titleOfTask" placeholder="Title of Task">
                                        <label for="titleOfTask">Title of Task</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="descriptionOfTask" placeholder="Description of Task">
                                        <label for="descriptionOfTask">Description of Task</label>
                                    </div>
                                    <div class="btn-row d-flex">
                                        <button class="btn btn-secondary btn--sqr ml-auto cancel-btn">Cancel</button>
                                        <button class="btn btn-primary btn--sqr submit-btn">Add</button>
                                    </div>
                                </form>
                            </div>
                            <div class="task-wrapper scrollable-contents">
                                <ul class="task-items">
                                    <li class="task-item row no-gutters">
                                        <div class="col-auto select-task">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="task-1">
                                                <label class="custom-control-label" for="task-1"></label>
                                            </div>
                                        </div>
                                        <div class="col-auto details">
                                            <h6>Do something</h6>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In cursus vel arcu sit amet scelerisque. Curabitur ut purus non qu.</p>
                                        </div>
                                    </li>
                                    <li class="task-item row no-gutters client-dependent">
                                        <div class="col-auto select-task">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="task-2">
                                                <label class="custom-control-label" for="task-2"></label>
                                            </div>
                                        </div>
                                        <div class="col-auto details">
                                            <h6>Do something</h6>
                                            <p><span class="green-text">Andrew Smith</span> Lorem ipsum dolor sit amet, consectetur adipiscing elit. In cursus vel arcu sit amet scelerisque. Curabitur ut purus non qu.</p>
                                        </div>
                                    </li>
                                    <li class="task-item row no-gutters client-dependent done">
                                        <div class="col-auto select-task">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="task-3" checked="">
                                                <label class="custom-control-label" for="task-3"></label>
                                            </div>
                                        </div>
                                        <div class="col-auto details">
                                            <h6>Do something</h6>
                                            <p><span class="green-text">Andrew Smith</span> Lorem ipsum dolor sit amet, consectetur adipiscing elit. In cursus vel arcu sit amet scelerisque. Curabitur ut purus non qu.</p>
                                        </div>
                                    </li>
                                    <li class="task-item done row no-gutters">
                                        <div class="col-auto select-task">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="task-4" checked="">
                                                <label class="custom-control-label" for="task-4"></label>
                                            </div>
                                        </div>
                                        <div class="col-auto details">
                                            <h6>Do something</h6>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In cursus vel arcu sit amet scelerisque. Curabitur ut purus non qu.</p>
                                        </div>
                                    </li>
                                    <li class="task-item row no-gutters">
                                        <div class="col-auto select-task">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="task-5">
                                                <label class="custom-control-label" for="task-5"></label>
                                            </div>
                                        </div>
                                        <div class="col-auto details">
                                            <h6>Do something</h6>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In cursus vel arcu sit amet scelerisque. Curabitur ut purus non qu.</p>
                                        </div>
                                    </li>
                                    <li class="task-item row no-gutters">
                                        <div class="col-auto select-task">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="task-6">
                                                <label class="custom-control-label" for="task-6"></label>
                                            </div>
                                        </div>
                                        <div class="col-auto details">
                                            <h6>Do something</h6>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In cursus vel arcu sit amet scelerisque. Curabitur ut purus non qu.</p>
                                        </div>
                                    </li>
                                    <li class="task-item row no-gutters">
                                        <div class="col-auto select-task">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="task-7">
                                                <label class="custom-control-label" for="task-7"></label>
                                            </div>
                                        </div>
                                        <div class="col-auto details">
                                            <h6>Do something</h6>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In cursus vel arcu sit amet scelerisque. Curabitur ut purus non qu.</p>
                                        </div>
                                    </li>
                                    <li class="task-item row no-gutters">
                                        <div class="col-auto select-task">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="task-8">
                                                <label class="custom-control-label" for="task-8"></label>
                                            </div>
                                        </div>
                                        <div class="col-auto details">
                                            <h6>Do something</h6>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In cursus vel arcu sit amet scelerisque. Curabitur ut purus non qu.</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="lead-information profile-widget">
                        <div class="widget-heading d-flex align-items-center">
                            <h6>Lead Information</h6>
                        </div>
                        <div class="widget-bdoy">
                            <div class="row lead-information-row">
                                <div class="col-12 col-sm-6 col-lg-4 information-item">
                                    <div class="information">
                                        <figure class="icon">
                                            <img src="/images/lead-page-icon.svg" alt="Lead PAge icon">
                                        </figure>
                                        <div class="info">
                                            <span>Lead PAge</span>
                                            <p>/contact</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 information-item">
                                    <div class="information">
                                        <figure class="icon">
                                            <img src="/images/interaction-icon.svg" alt="First Interaction icon">
                                        </figure>
                                        <div class="info">
                                            <span>First Interaction</span>
                                            <p>Phone Call · Jan 5, 2021</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 information-item">
                                    <div class="information">
                                        <figure class="icon">
                                            <img src="/images/meeting-icon.svg" alt="Quote Meeting icon">
                                        </figure>
                                        <div class="info">
                                            <span>Quote Meeting</span>
                                            <p>Jan 10, 2021</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 information-item">
                                    <div class="information">
                                        <figure class="icon">
                                            <img src="/images/work-icon.svg" alt="Work Started icon">
                                        </figure>
                                        <div class="info">
                                            <span>Work Started</span>
                                            <p>Jan 20, 2021</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
