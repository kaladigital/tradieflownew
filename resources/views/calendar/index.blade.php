@extends('layouts.master')
@section('view_css')
<link rel="stylesheet" href="/js/jquery-confirm/jquery-confirm.min.css">
<link rel="stylesheet" href="/js/fullcalendar/main.css">
<link rel="stylesheet" href="/js/jquery-datetimepicker/jquery.datetimepicker.min.css">
<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css">
<style>
    .fc-header-toolbar{
        display:none !important;
    }
</style>
@endsection
@section('content')
    @include('dashboard.left_sidebar_full_menu',['active_page' => 'calendar'])
    <div class="col-md-auto col-12 content-wrap calendar-main-page">
        <div class="content-inner">
            <div class="row">
                <div class="col-12 col-lg-auto small-calendar-col">
                    <h2>Calendar</h2>
                    <div class="profile-widget">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-lg-12">
                                <div class="date-picker">
                                    <div id="calendar-datepicker"></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-12">
                                <h6>Filter Categories</h6>
                                <ul class="category-list">
                                    <li class="category-item quote-meeting">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input filter_checkbox" data-type="quote-meeting" id="quote-meeting-category" autocomplete="off">
                                            <label class="custom-control-label" for="quote-meeting-category">
                                                Quote Meeting
                                            </label>
                                        </div>
                                    </li>
                                    <li class="category-item work">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input filter_checkbox" data-type="work-in-progress" id="work-category" autocomplete="off">
                                            <label class="custom-control-label" for="work-category">Work</label>
                                        </div>
                                    </li>
                                    <li class="category-item reminder">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input filter_checkbox" data-type="remind-me" id="reminder-category" autocomplete="off">
                                            <label class="custom-control-label" for="reminder-category">Reminder</label>
                                        </div>
                                    </li>
                                    <li class="category-item other">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input filter_checkbox" data-type="other" id="other-category" autocomplete="off">
                                            <label class="custom-control-label" for="other-category">Other</label>
                                        </div>
                                    </li>
                                </ul>
{{--                                <h6>Other Calendars</h6>--}}
{{--                                <ul class="other-calendar">--}}
{{--                                    <li>--}}
{{--                                        <div class="custom-control custom-checkbox">--}}
{{--                                            <input type="checkbox" class="custom-control-input" id="other-calendar-1">--}}
{{--                                            <label class="custom-control-label" for="other-calendar-1">mytradie@email.com</label>--}}
{{--                                        </div>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <div class="custom-control custom-checkbox">--}}
{{--                                            <input type="checkbox" class="custom-control-input" id="other-calendar-2">--}}
{{--                                            <label class="custom-control-label" for="other-calendar-2">mycompany@email.com</label>--}}
{{--                                        </div>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-auto mt-5 mt-lg-0 large-calendar-col">
                    <div class="calendar-header d-flex align-items-center">
                        <h3 class="calendar-title" id="calendar_custom_title"></h3>
                        <div class="btn-group d-flex align-items-center" id="calendar_btn_container" style="display:none !important;">
                            <button class="btn btn-prev calendar_week_change" data-type="previous">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.2068 13.7042C11.6003 13.3106 11.6003 12.6725 11.2068 12.2789L6.9306 8.00274L11.2067 3.72152C11.6 3.32769 11.6 2.68919 11.2067 2.29537C10.8133 1.90155 10.1756 1.90154 9.78225 2.29537L4.80749 7.2761C4.8026 7.2808 4.79775 7.28555 4.79294 7.29037C4.39935 7.68395 4.39935 8.32208 4.79294 8.71566L9.78147 13.7042C10.1751 14.0978 10.8132 14.0978 11.2068 13.7042Z" fill="#43D14F"></path>
                                </svg>
                            </button>
                            <button class="btn btn-next calendar_week_change" data-type="next">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.79323 2.29581C4.39965 2.68939 4.39965 3.32752 4.79323 3.7211L9.0694 7.99726L4.79331 12.2785C4.39996 12.6723 4.39996 13.3108 4.79331 13.7046C5.18666 14.0985 5.8244 14.0985 6.21775 13.7046L11.1925 8.7239C11.1974 8.7192 11.2022 8.71445 11.2071 8.70963C11.6006 8.31605 11.6006 7.67792 11.2071 7.28434L6.21853 2.29581C5.82495 1.90222 5.18682 1.90222 4.79323 2.29581Z" fill="#43D14F"></path>
                                </svg>
                            </button>
                        </div>
                        <button type="button" id="add_event" class="btn add-new-btn btn-primary btn--round d-flex">
                            <img src="/images/plus-Icon-white.svg" alt="Plus icon" class="icon">
                            <span>Add New</span>
                        </button>
                        <button type="button" id="today_btn" style="display:none;" class="btn btn-outline btn--round">Today</button>
                        <!-- <div class="select-date d-flex align-items-center">
                           <select class="selectView" name="selectView">
                              <option value="weekly">Weekly</option>
                              <option value="daily">Daily</option>
                              <option value="monthly">Monthly</option>
                           </select>
                        </div> -->
                    </div>
                    <div id="calendar" class="fc fc-media-screen fc-direction-ltr fc-theme-standard fc-liquid-hack"><div class="fc-header-toolbar fc-toolbar "><div class="fc-toolbar-chunk"><h2 class="fc-toolbar-title">Oct 4 – 10, 2021</h2></div><div class="fc-toolbar-chunk"></div><div class="fc-toolbar-chunk"><button disabled="" class="fc-today-button fc-button fc-button-primary" type="button">today</button><div class="fc-button-group"><button class="fc-prev-button fc-button fc-button-primary" type="button" aria-label="prev"><span class="fc-icon fc-icon-chevron-left"></span></button><button class="fc-next-button fc-button fc-button-primary" type="button" aria-label="next"><span class="fc-icon fc-icon-chevron-right"></span></button></div></div></div><div class="fc-view-harness fc-view-harness-active" style="height: 646.667px;"><div class="fc-timegrid fc-timeGridWeek-view fc-view"><table class="fc-scrollgrid  fc-scrollgrid-liquid"><tbody><tr class="fc-scrollgrid-section fc-scrollgrid-section-header "><td><div class="fc-scroller-harness"><div class="fc-scroller" style="overflow: hidden scroll;"><table class="fc-col-header " style="width: 857px;"><colgroup><col style="width: 52px;"></colgroup><tbody><tr><th class="fc-timegrid-axis"><div class="fc-timegrid-axis-frame"></div></th><th class="fc-col-header-cell fc-day fc-day-mon fc-day-past" data-date="2021-10-04"><div class="fc-scrollgrid-sync-inner"><a class="fc-col-header-cell-cushion ">Mon 10/4</a></div></th><th class="fc-col-header-cell fc-day fc-day-tue fc-day-past" data-date="2021-10-05"><div class="fc-scrollgrid-sync-inner"><a class="fc-col-header-cell-cushion ">Tue 10/5</a></div></th><th class="fc-col-header-cell fc-day fc-day-wed fc-day-past" data-date="2021-10-06"><div class="fc-scrollgrid-sync-inner"><a class="fc-col-header-cell-cushion ">Wed 10/6</a></div></th><th class="fc-col-header-cell fc-day fc-day-thu fc-day-today " data-date="2021-10-07"><div class="fc-scrollgrid-sync-inner"><a class="fc-col-header-cell-cushion ">Thu 10/7</a></div></th><th class="fc-col-header-cell fc-day fc-day-fri fc-day-future" data-date="2021-10-08"><div class="fc-scrollgrid-sync-inner"><a class="fc-col-header-cell-cushion ">Fri 10/8</a></div></th><th class="fc-col-header-cell fc-day fc-day-sat fc-day-future" data-date="2021-10-09"><div class="fc-scrollgrid-sync-inner"><a class="fc-col-header-cell-cushion ">Sat 10/9</a></div></th><th class="fc-col-header-cell fc-day fc-day-sun fc-day-future" data-date="2021-10-10"><div class="fc-scrollgrid-sync-inner"><a class="fc-col-header-cell-cushion ">Sun 10/10</a></div></th></tr></tbody></table></div></div></td></tr><tr class="fc-scrollgrid-section fc-scrollgrid-section-body "><td><div class="fc-scroller-harness"><div class="fc-scroller" style="overflow: hidden scroll;"><div class="fc-daygrid-body fc-daygrid-body-unbalanced fc-daygrid-body-natural" style="width: 857px;"><table class="fc-scrollgrid-sync-table" style="width: 857px;"><colgroup><col style="width: 52px;"></colgroup><tbody><tr><td class="fc-timegrid-axis fc-scrollgrid-shrink"><div class="fc-timegrid-axis-frame fc-scrollgrid-shrink-frame fc-timegrid-axis-frame-liquid"><span class="fc-timegrid-axis-cushion fc-scrollgrid-shrink-cushion fc-scrollgrid-sync-inner">all-day</span></div></td><td class="fc-daygrid-day fc-day fc-day-mon fc-day-past" data-date="2021-10-04"><div class="fc-daygrid-day-frame fc-scrollgrid-sync-inner"><div class="fc-daygrid-day-events"><div class="fc-daygrid-day-bottom" style="margin-top: 0px;"></div></div><div class="fc-daygrid-day-bg"></div></div></td><td class="fc-daygrid-day fc-day fc-day-tue fc-day-past" data-date="2021-10-05"><div class="fc-daygrid-day-frame fc-scrollgrid-sync-inner"><div class="fc-daygrid-day-events"><div class="fc-daygrid-day-bottom" style="margin-top: 0px;"></div></div><div class="fc-daygrid-day-bg"></div></div></td><td class="fc-daygrid-day fc-day fc-day-wed fc-day-past" data-date="2021-10-06"><div class="fc-daygrid-day-frame fc-scrollgrid-sync-inner"><div class="fc-daygrid-day-events"><div class="fc-daygrid-day-bottom" style="margin-top: 0px;"></div></div><div class="fc-daygrid-day-bg"></div></div></td><td class="fc-daygrid-day fc-day fc-day-thu fc-day-today " data-date="2021-10-07"><div class="fc-daygrid-day-frame fc-scrollgrid-sync-inner"><div class="fc-daygrid-day-events"><div class="fc-daygrid-day-bottom" style="margin-top: 0px;"></div></div><div class="fc-daygrid-day-bg"></div></div></td><td class="fc-daygrid-day fc-day fc-day-fri fc-day-future" data-date="2021-10-08"><div class="fc-daygrid-day-frame fc-scrollgrid-sync-inner"><div class="fc-daygrid-day-events"><div class="fc-daygrid-day-bottom" style="margin-top: 0px;"></div></div><div class="fc-daygrid-day-bg"></div></div></td><td class="fc-daygrid-day fc-day fc-day-sat fc-day-future" data-date="2021-10-09"><div class="fc-daygrid-day-frame fc-scrollgrid-sync-inner"><div class="fc-daygrid-day-events"><div class="fc-daygrid-day-bottom" style="margin-top: 0px;"></div></div><div class="fc-daygrid-day-bg"><div class="fc-daygrid-bg-harness" style="left: 0px; right: -116px;"><div class="fc-non-business"></div></div></div></div></td><td class="fc-daygrid-day fc-day fc-day-sun fc-day-future" data-date="2021-10-10"><div class="fc-daygrid-day-frame fc-scrollgrid-sync-inner"><div class="fc-daygrid-day-events"><div class="fc-daygrid-day-bottom" style="margin-top: 0px;"></div></div><div class="fc-daygrid-day-bg"></div></div></td></tr></tbody></table></div></div></div></td></tr><tr class="fc-scrollgrid-section"><td class="fc-timegrid-divider fc-cell-shaded"></td></tr><tr class="fc-scrollgrid-section fc-scrollgrid-section-body  fc-scrollgrid-section-liquid"><td><div class="fc-scroller-harness fc-scroller-harness-liquid"><div class="fc-scroller fc-scroller-liquid-absolute" style="overflow: hidden scroll;"><div class="fc-timegrid-body" style="width: 857px;"><div class="fc-timegrid-slots"><table class="" style="width: 857px;"><colgroup><col style="width: 52px;"></colgroup><tbody><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="00:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">12am</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="00:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="00:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="00:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="01:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">1am</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="01:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="01:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="01:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="02:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">2am</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="02:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="02:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="02:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="03:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">3am</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="03:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="03:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="03:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="04:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">4am</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="04:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="04:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="04:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="05:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">5am</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="05:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="05:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="05:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="06:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">6am</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="06:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="06:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="06:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="07:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">7am</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="07:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="07:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="07:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="08:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">8am</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="08:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="08:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="08:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="09:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">9am</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="09:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="09:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="09:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="10:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">10am</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="10:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="10:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="10:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="11:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">11am</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="11:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="11:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="11:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="12:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">12pm</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="12:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="12:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="12:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="13:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">1pm</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="13:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="13:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="13:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="14:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">2pm</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="14:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="14:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="14:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="15:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">3pm</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="15:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="15:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="15:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="16:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">4pm</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="16:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="16:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="16:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="17:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">5pm</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="17:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="17:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="17:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="18:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">6pm</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="18:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="18:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="18:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="19:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">7pm</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="19:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="19:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="19:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="20:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">8pm</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="20:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="20:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="20:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="21:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">9pm</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="21:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="21:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="21:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="22:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">10pm</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="22:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="22:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="22:30:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-scrollgrid-shrink" data-time="23:00:00"><div class="fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"><div class="fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion">11pm</div></div></td><td class="fc-timegrid-slot fc-timegrid-slot-lane " data-time="23:00:00"></td></tr><tr><td class="fc-timegrid-slot fc-timegrid-slot-label fc-timegrid-slot-minor" data-time="23:30:00"></td><td class="fc-timegrid-slot fc-timegrid-slot-lane fc-timegrid-slot-minor" data-time="23:30:00"></td></tr></tbody></table></div><div class="fc-timegrid-cols"><table style="width: 857px;"><colgroup><col style="width: 52px;"></colgroup><tbody><tr><td class="fc-timegrid-col fc-timegrid-axis"><div class="fc-timegrid-col-frame"><div class="fc-timegrid-now-indicator-container"></div></div></td><td class="fc-timegrid-col fc-day fc-day-mon fc-day-past" data-date="2021-10-04"><div class="fc-timegrid-col-frame"><div class="fc-timegrid-col-bg"><div class="fc-timegrid-bg-harness" style="top: 0px; bottom: -336px;"><div class="fc-non-business"></div></div></div><div class="fc-timegrid-col-events"></div><div class="fc-timegrid-col-events"></div><div class="fc-timegrid-now-indicator-container"></div></div></td><td class="fc-timegrid-col fc-day fc-day-tue fc-day-past" data-date="2021-10-05"><div class="fc-timegrid-col-frame"><div class="fc-timegrid-col-bg"><div class="fc-timegrid-bg-harness" style="top: 0px; bottom: -336px;"><div class="fc-non-business"></div></div></div><div class="fc-timegrid-col-events"></div><div class="fc-timegrid-col-events"></div><div class="fc-timegrid-now-indicator-container"></div></div></td><td class="fc-timegrid-col fc-day fc-day-wed fc-day-past" data-date="2021-10-06"><div class="fc-timegrid-col-frame"><div class="fc-timegrid-col-bg"><div class="fc-timegrid-bg-harness" style="top: 0px; bottom: -336px;"><div class="fc-non-business"></div></div></div><div class="fc-timegrid-col-events"></div><div class="fc-timegrid-col-events"></div><div class="fc-timegrid-now-indicator-container"></div></div></td><td class="fc-timegrid-col fc-day fc-day-thu fc-day-today " data-date="2021-10-07"><div class="fc-timegrid-col-frame"><div class="fc-timegrid-col-bg"><div class="fc-timegrid-bg-harness" style="top: 0px; bottom: -336px;"><div class="fc-non-business"></div></div></div><div class="fc-timegrid-col-events"></div><div class="fc-timegrid-col-events"></div><div class="fc-timegrid-now-indicator-container"></div></div></td><td class="fc-timegrid-col fc-day fc-day-fri fc-day-future" data-date="2021-10-08"><div class="fc-timegrid-col-frame"><div class="fc-timegrid-col-bg"><div class="fc-timegrid-bg-harness" style="top: 0px; bottom: -336px;"><div class="fc-non-business"></div></div></div><div class="fc-timegrid-col-events"></div><div class="fc-timegrid-col-events"></div><div class="fc-timegrid-now-indicator-container"></div></div></td><td class="fc-timegrid-col fc-day fc-day-sat fc-day-future" data-date="2021-10-09"><div class="fc-timegrid-col-frame"><div class="fc-timegrid-col-bg"><div class="fc-timegrid-bg-harness" style="top: 0px; bottom: -1008px;"><div class="fc-non-business"></div></div></div><div class="fc-timegrid-col-events"></div><div class="fc-timegrid-col-events"></div><div class="fc-timegrid-now-indicator-container"></div></div></td><td class="fc-timegrid-col fc-day fc-day-sun fc-day-future" data-date="2021-10-10"><div class="fc-timegrid-col-frame"><div class="fc-timegrid-col-bg"><div class="fc-timegrid-bg-harness" style="top: 0px; bottom: -1008px;"><div class="fc-non-business"></div></div></div><div class="fc-timegrid-col-events"></div><div class="fc-timegrid-col-events"></div><div class="fc-timegrid-now-indicator-container"></div></div></td></tr></tbody></table></div></div></div></div></td></tr></tbody></table></div></div></div>
                    <div class="preview-event-wrapper position-absolute">
                        <div class="card preview-event-card">
                            <div class="card-header d-flex align-items-center">
                                <button class="btn call-btn">
                                    <img class="green" src="../images/calendar-event-call.svg" alt="Call icon green">
                                    <img class="gray" src="../images/calendar-event-call-gray.svg" alt="Call icon gray">
                                </button>
                                <button class="btn send-mail-btn">
                                    <img class="green" src="../images/calendar-event-email.svg" alt="Email icon green">
                                    <img class="gray" src="../images/calendar-event-email-gray.svg" alt="Email icon gray">
                                </button>
                                <button class="btn send-message-btn">
                                    <img class="green" src="../images/calendar-event-text-message.svg" alt="Email icon green">
                                    <img class="gray" src="../images/calendar-event-text-message-gray.svg" alt="Email icon gray">
                                </button>
                                <button class="btn edit-btn ml-auto">
                                    <img src="../images/calendar-event-edit.svg" alt="Edit icon">
                                </button>
                                <button class="btn delete-btn">
                                    <img src="../images/calendar-event-delete.svg" alt="Delete icon">
                                </button>
                                <button class="btn delete-btn">
                                    <img src="../images/close-icon-black.svg" alt="Close icon">
                                </button>
                            </div>
                            <div class="card-body reminder">
                                <div class="info">
                                    <h6>Call Brandon</h6>
                                    <p class="time">Tue, 4 February · 12:00 - 13:00</p>
                                </div>
                                <div class="info">
                                    <p class="label">Category</p>
                                    <p>Brandon Culhane · Follow-up</p>
                                </div>
                                <div class="info">
                                    <p class="label">Location</p>
                                    <p>580 George St, Sydney NSW 2000</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal add-new-event fade" id="add_event_modal" tabindex="-1" aria-labelledby="add-new-event" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="tab-pane fade show active" id="v-pills-client-data" role="tabpanel" aria-labelledby="v-pills-help-tab">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ring-modal-label">Add New Event</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <img src="/landing_media/img/_src/svg/close-icon.svg" alt="close">
                        </button>
                    </div>
                    <div class="modal-body ui-front" style="height:600px;overflow-y:scroll;">
                        <section class="client-data">
                            {!! Form::open(['url' => 'client','class' => 'data-form', 'id' => 'add_event_form', 'autocomplete' => 'off']) !!}
                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <div class="modal-small-title">Client's Data</div>
                                    <div class="form-group inline-group">
                                        {!! Form::text('client_search',null,['class' => 'form-control', 'id' => 'client_search']) !!}
                                        {!! Form::label('client_search','Search For Names or Type Number') !!}
                                        <a href="" id="add_manual_client">Add</a>
                                    </div>
                                    <div class="add_event_container" style="display:none;">
                                        <h5>OR</h5>
                                        <div class="form-group inline-group">
                                            {!! Form::text('full_name',null,['class' => 'form-control', 'id' => 'full_name', 'readonly' => 'readonly']) !!}
                                            {!! Form::label('full_name','Full Name') !!}
                                        </div>
                                        <div class="form-group inline-group" id="phone_container">
                                            {!! Form::text('phone',null,['class' => 'form-control', 'id' => 'phone']) !!}
                                            {!! Form::label('phone','Phone') !!}
                                        </div>
                                        <div class="form-group inline-group">
                                            {!! Form::text('event_upfront',null,['class' => 'form-control', 'id' => 'event_upfront']) !!}
                                            {!! Form::label('event_upfront','Upfront') !!}
                                        </div>
                                        <div class="form-group inline-group">
                                            {!! Form::text('event_ongoing',null,['class' => 'form-control', 'id' => 'event_ongoing']) !!}
                                            {!! Form::label('event_ongoing','Ongoing') !!}
                                        </div>
                                        <div class="modal-small-title">Event Details</div>
                                        <div class="form-group">
                                            {!! Form::text('event_start_date',null,['class' => 'form-control', 'id' => 'event_start_date']) !!}
                                            {!! Form::label('event_start_date','Starts') !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::text('event_end_date',null,['class' => 'form-control', 'id' => 'event_end_date']) !!}
                                            {!! Form::label('event_end_date','Ends') !!}
                                        </div>
                                        <div class="modal-small-title">Event Type</div>
                                        <div class="form-group stage-field not-listed">
                                            {!! Form::select('event_status',$event_types,null,['class' => 'form-control', 'id' => 'event_status']) !!}
                                        </div>
                                        <div class="form-group event_other_container" style="display:none;">
                                            {!! Form::text('event_other',null,['class' => 'form-control', 'id' => 'event_other']) !!}
                                        </div>
                                        <div class="modal-small-title">Location</div>
                                        <section class="event_location_container"></section>
                                        <a class="btn add-field" id="add_event_location">
                                            <span class="plus">
                                                <img src="/landing_media/img/_src/svg/plus-icon.svg" alt="plus">
                                            </span>
                                            Add Another Value
                                        </a>
                                        <button class="btn btn-transparent btn-lg-close-event modal_btn" data-dismiss="modal" aria-label="Close">Cancel</button>
                                        <button class="btn btn-default btn-lg-add-event modal_btn" id="add_event_btn">Add Event</button>
                                    </div>
                                </div>
                            </div>
                            <div id="event_create_loader" style="display:none;">
                                <img src="/images/loader.png" width="24px" class="float-left">
                                <span class="float-left ml-1 loader-text">Processing</span>
                            </div>
                            <div style="margin-top: 100px;"></div>
                            {!! Form::hidden('client_id',null,['id' => 'client_id']) !!}
                            {!! Form::close() !!}
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('view_script')
<script src="/js/jquery-confirm/jquery-confirm.min.js"></script>
<script src="/js/fullcalendar/main.js"></script>
<script type="text/javascript" src="/js/jquery.inputmask.min.js"></script>
<script type="text/javascript" src="/js/jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
        window.selected_client = {};
        $('#calendar-datepicker').datepicker({
            showOtherMonths: true,
            dateFormat: 'yy-mm-dd',
            onSelect: function(dateText) {
                console.log(dateText)
            }
        });

        $('#event_upfront').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });

        $('#event_ongoing').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            eventSources: [
                {
                    url: '/calendar/events',
                    extraParams: function() {
                        var filters = [];
                        $('.filter_checkbox').each(function(){
                            if ($(this).prop('checked')) {
                                filters.push($(this).attr('data-type'));
                            }
                        });
                        return {
                            filter: filters.join(',')
                        };
                    }
                }
            ],
            initialView: 'timeGridWeek',
            firstDay: '1',
            headerToolbar: {
                start: 'title',
                center: '',
                end: 'today prev,next'
            },
            businessHours: {
                startTime: '08:00',
                endTime: '24:00',
            },
            datesSet: function (element) {
                $('#calendar_custom_title').text($('.fc-toolbar-title').text());
                $('#calendar_btn_container').show();
                if ($('.fc-today-button').attr('disabled')) {
                    $('#today_btn').hide();
                }
                else{
                    $('#today_btn').fadeIn();
                }
            }
        });

        calendar.render();

        $('#event_start_date').datetimepicker({
            format: 'D, j F, Y H:i',
            lang:'en',
            validateOnBlur: false,
            closeOnTimeSelect: true
        });

        $('#event_end_date').datetimepicker({
            format: 'D, j F, Y H:i',
            lang:'en',
            validateOnBlur: false,
            closeOnTimeSelect: true
        });

        $('#client_search').autocomplete({
            autoFill: true,
            source: function( request, response ) {
                $.ajax( {
                    url: "/calendar/client/search",
                    dataType: 'json',
                    async: false,
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        var clients = [];
                        if (data.clients.length) {
                            $.each(data.clients,function(key,value){
                                if (value.company) {
                                    value.name += ' (' + value.company + ')';
                                }
                                clients.push({
                                    label : value.name,
                                    value : value.client_id
                                });
                            });
                        }
                        else{
                            $('#client_id').val('');
                            $('#add_manual_client').show();
                        }
                        return response(clients);
                    }
                } );
            },
            minLength: 0,
            select: function(event, ui) {
                $('#client_search').val('').blur();
                $('#full_name').val(ui.item.label).prop('readonly','readonly');
                $('#phone_container').hide();
                $('#phone').val('');
                $('#client_id').val(ui.item.value);
                $('#add_manual_client').hide();
                $('.add_event_container').show();
                return false;
            }
        }).bind('focus',function(){ $(this).autocomplete("search"); } );

        /**Filter Management*/
        $(document).on('change','.filter_checkbox',function(){
            calendar.refetchEvents()
            return false;
        });

        /**Calendar Management*/
        $(document).on('click','.calendar_week_change',function(){
            var data_type = $(this).attr('data-type');
            if (data_type == 'previous') {
                calendar.prev();
            }
            else{
                calendar.next();
            }
            return false;
        });

        $(document).on('click','#today_btn',function(){
            $('.fc-today-button').trigger('click');
            return false;
        });

        $(document).on('change','#client_search',function(){
            $('#client_id').val('');
            $('#add_manual_client').show();
            return false;
        });

        $(document).on('click','#add_event',function(){
            $('.event_location_container').html($('#event_location_item_template').html());
            $('.add_event_container').hide();
            $('#add_manual_client').hide();
            $('#phone').val('');
            $('#phone_container').show();
            $('#add_event_modal').modal('show');
            return false;
        });

        $(document).on('click','#add_manual_client',function(){
            var client_name = $.trim($('#client_search').val());
            if (client_name.length) {
                $('#full_name').val(client_name).removeAttr('readonly');
                $('#phone').val('');
                $('#phone_container').show();
                $('.add_event_container').show();
                $('#client_search').val('');
                $(this).hide();
            }
            else{
                $('#client_search').focus();
            }
            $('#client_id').val('');
            return false;
        });

        $(document).on('change','#event_status',function(){
            $(this).closest('.stage-field').removeClass('lead-stage')
                .removeClass('quote-meeting')
                .removeClass('work-in-progress')
                .removeClass('cancelled')
                .removeClass('completed')
                .removeClass('follow-up')

            var closest_obj = $(this).closest('.stage-field');
            switch ($(this).val()) {
                case 'quote-meeting':
                    closest_obj.addClass('quote-meeting');
                    $('.event_other_container').hide();
                break;
                case 'work-in-progress':
                    closest_obj.addClass('work-in-progress');
                    $('.event_other_container').hide();
                break;
                case 'remind-me':
                case 'other':
                    closest_obj.addClass('cancelled');
                    $('.event_other_container').show();
                break;
            }
            return false;
        });

        $(document).on('click','.delete_event_location_item',function(){
            $(this).closest('.event_location_item').remove();
            return false;
        });

        $(document).on('click','#add_event_btn',function(){
            $('#add_event_form').trigger('submit');
            return false;
        });

        $(document).on('click','#add_event_location',function(){
            $('.event_location_container').append($('#event_location_item_template').html());
            return false;
        });

        $(document).on('submit','#add_event_form',function(){
            var start_date = $('#event_start_date').val();
            if (!start_date) {
                $('#event_start_date').focus();
                return false;
            }

            var end_date = $('#event_end_date').val();
            if (!end_date) {
                $('#event_end_date').focus();
                return false;
            }

            var locations = [];
            $('.event_location_item').each(function(){
                var city = $('.event_city').val();
                var zip = $('.event_zip').val();
                var address = $('.event_address').val();
                if (city.length || zip.length || address.length) {
                    locations.push({
                        city: city,
                        zip: zip,
                        address: address
                    });
                }
            });

            var form_data = {
                name : $('#full_name').val(),
                start_date: start_date,
                end_date: end_date,
                upfront_value: $('#event_upfront').val(),
                ongoing_value: $('#event_ongoing').val(),
                status: $('#event_status').val(),
                other_text: $('#event_other').val(),
                locations: locations,
                client_id : $('#client_id').val(),
                phone : $('#phone').val()
            }

            $.post('/client/add/event',form_data,function(data){
                if (data.status) {
                    $('#add_event_modal').modal('hide');
                    $('#add_event_form')['0'].reset();
                    App.render_message('success','New event added successfully');
                    calendar.refetchEvents();
                }
                else{
                    App.render_message('info',data.error);
                }
            },'json');
            return false;
        });
    });
</script>
<script type="text/template" id="event_location_item_template">
    <div class="row event_location_item">
        <div class="col-md-5">
            <div class="form-group">
                <input type="text" name="event_city" class="form-control event_city">
                <label>City</label>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <input type="text" name="event_zip" class="form-control event_zip">
                <label>ZIP</label>
            </div>
        </div>
        <div class="col-md-2 delete-item">
            <a href="" class="delete_event_location_item">Delete</a>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <input type="text" name="event_address" class="form-control event_address">
                <label>Address Line</label>
            </div>
        </div>
    </div>
</script>
@endsection
