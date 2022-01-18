@extends('layouts.master')
@section('view_css')
<link rel="stylesheet" href="/js/select2/css/select2.min.css">
@endsection
@section('content')
    @if($auth_user->role == 'sales')
        @include('admin.left_sidebar_admin_menu',['active_page' => 'referrals'])
    @else
        @include('dashboard.left_sidebar_full_menu',['active_page' => 'referrals'])
    @endif
    <div class="col-md-auto col-12 content-wrap client-profile dashboard">
        <div class="content-inner">
            <div class="page-title d-flex align-items-center">
                <h2>Referrals</h2>
            </div>
            <div class="widget-box">
                <h3>Give free months</h3>
                <p>These users will receive emails about free months. Registered users will be able to activate these too.</p>
                @include('elements.alerts')
                <div class="fields-row">
                    {!! Form::open(['url' => 'admin/referrals', 'autocomplete' => 'off', 'id' => 'send_referral_form']) !!}
                        <div class="row">
                            <div class="col-12 col-xl-auto col-md-6">
                                <div class="form-group">
                                    {!! Form::select('total_months',$referral_months,null,['id' => 'total_months', 'required' => 'required']) !!}
                                    {!! Form::label('total_months','Type') !!}
                                </div>
                            </div>
                            <div class="col-12 col-xl-auto col-md-6">
                                <div class="form-group">
                                    {!! Form::text('name',null,['class' => 'form-control', 'id' => 'name', 'required' => 'required', 'placeholder' => 'First Name']) !!}
                                    {!! Form::label('name','First Name') !!}
                                </div>
                            </div>
                            <div class="col-12 col-xl-auto col-md-6">
                                <div class="form-group">
                                    {!! Form::email('email',null,['class' => 'form-control', 'id' => 'email', 'required' => 'required', 'placeholder' => 'Email']) !!}
                                    {!! Form::label('email','Email') !!}
                                </div>
                            </div>
                            <div class="col-12 col-xl-auto col-md-6 btn-col">
                                <button type="submit" class="btn btn--sqr btn-primary">Send Free Months</button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="widget-box">
                <h3>Giveaways</h3>
                <div class="list-items list-table">
                    <div class="row items-heading no-gutters">
                        <div class="col-auto lead-name">
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['name_asc','name_desc']) ? ($request['sort_by'] == 'name_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>First Name</span>
                                <a class="sort_by" data-type="{{ $request['sort_by'] == 'name_asc' ? 'name_desc' : 'name_asc' }}" href="#">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto value">
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['email_asc','email_desc']) ? ($request['sort_by'] == 'email_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>Email</span>
                                <a class="sort_by" data-type="{{ $request['sort_by'] == 'email_asc' ? 'email_desc' : 'email_asc' }}" href="#">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto source">
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['type_asc','type_desc']) ? ($request['sort_by'] == 'type_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>Type</span>
                                <a class="sort_by" data-type="{{ $request['sort_by'] == 'type_asc' ? 'type_desc' : 'type_asc' }}" href="#">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto lead-page">
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['sent_asc','sent_desc']) ? ($request['sort_by'] == 'sent_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>Sent</span>
                                <a class="sort_by" data-type="{{ $request['sort_by'] == 'sent_asc' ? 'sent_desc' : 'sent_asc' }}" href="#">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto status">
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['status_asc','status_desc']) ? ($request['sort_by'] == 'status_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>Status</span>
                                <a class="sort_by" data-type="{{ $request['sort_by'] == 'status_asc' ? 'status_desc' : 'status_asc' }}" href="#">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto duration">
                            <button class="btn sort-btn  {{ in_array($request['sort_by'],['accepted_asc','accepted_desc']) ? ($request['sort_by'] == 'accepted_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>Accepted on</span>
                                <a class="sort_by" data-type="{{ $request['sort_by'] == 'accepted_asc' ? 'accepted_desc' : 'accepted_asc' }}" href="#">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                    </div>
                    @foreach($referrals as $item)
                        <div class="row items-body no-gutters">
                            <div class="col-auto lead-name">
                                {{ $item->name }}
                            </div>
                            <div class="col-auto value">
                                <span>{{ $item->email }}</span>
                            </div>
                            <div class="col-auto source">
                                <span>{{ $item->months }} month{{ $item->months > 1 ? 's' : '' }} free</span>
                            </div>
                            <div class="col-auto lead-page">
                                <span>{{ $item->created_at->format('j F, Y') }}</span>
                            </div>
                            <div class="col-auto status">
                                @switch($item->status)
                                    @case('pending')
                                        <div class="dropdown-wrap select-dropdown work-in-progress">
                                            <button class="btn select-btn d-flex align-items-center">
                                                <span>Pending</span>
                                            </button>
                                        </div>
                                    @break
                                    @case('accepted')
                                        <div class="dropdown-wrap select-dropdown completed">
                                            <button class="btn select-btn d-flex align-items-center">
                                                <span>Accepted</span>
                                            </button>
                                        </div>
                                    @break
                                    @case('rejected')
                                        <div class="dropdown-wrap select-dropdown not-listed">
                                            <button class="btn select-btn d-flex align-items-center">
                                                <span>Rejected</span>
                                            </button>
                                        </div>
                                    @break
                                @endswitch

                            </div>
                            <div class="col-auto duration">
                                <span>
                                       @if($item->status == 'accepted')
                                        {{ $item->updated_at->format('j F, Y') }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                    @include('elements.pagination',['paginator' => $referrals])
                </div>
            </div>
        </div>
    </div>
@endsection
@section('view_script')
<script type="text/javascript" src="/js/select2/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#total_months').select2({
            width: '100%',
            minimumResultsForSearch: -1,
        });

        $(document).on('click','.sort_by',function(){
            console.log($(this).attr('data-type'));
            location.href = '/admin/referrals?sort_by=' + $(this).attr('data-type');
            return false;
        });

        $(document).on('click','.pagination a',function(){
            var page_ref = $(this).attr('href');
            location.href = '/admin/referrals?page=' + page_ref.split('page=')['1'] + '&sort_by={{ $request['sort_by'] }}';
            return false;
        });
    });
</script>
@endsection
