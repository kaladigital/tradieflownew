@extends('layouts.master')
@section('view_css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
@endsection
@section('content')
    @include('admin.left_sidebar_admin_menu',['active_page' => 'users'])
    <div class="col-md-auto col-12 content-wrap">
        <div class="content-inner">
            @include('elements.alerts')
            <h2 class="page-title">Users</h2>
            <div class="content-widget row no-gutters">
                <div class="w-100">
                   <table class="table table-bordered table-responsive" id="user_table">
                       <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Industry</th>
                                <th>How can we help your business</th>
                                <th>Company Name</th>
                                <th>Signup Date</th>
                                <th>Notes</th>
                                <th>Status</th>
                            </tr>
                       </thead>
                       <tbody>
                        @foreach($users as $item)
                            <tr class="user_row_item" data-id="{{ $item->user_id }}">
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->industries }}</td>
                                <td>{{ $item->business_types }}</td>
                                <td>{{ $item->UserInvoiceSetting ? $item->UserInvoiceSetting->company_name : '' }}</td>
                                <td>{{ $item->created_at->format('F j, Y') }}</td>
                                <td>
                                    {!! Form::textarea('admin_note_'.$item->user_id,$item->note,['class' => 'form-control note_box', 'autocomplete' => 'off']) !!}
                                    <button type="button" class="btn btn-sm btn-primary update_note_btn" style="display:none;">Update</button>
                                </td>
                                <td>
                                    {!! Form::select('admin_user_status_'.$item->user_id,$admin_user_statuses,$item->admin_status, ['class' => 'form-control change_user_status', 'autocomplete' => 'off']) !!}
                                    <br>
                                    <a href="/admin/impersonate/{{ $item->user_id }}" class="btn btn--round btn-primary">Impersonate</a>
                                </td>
                            </tr>
                        @endforeach
                       </tbody>
                   </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('view_script')
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('change','.change_user_status',function(){
            var user_id = $(this).closest('.user_row_item').attr('data-id');
            $.post('/admin/user/update/status',{ user_id: user_id, status: $(this).val() },function(data){
                if (data.status) {
                    App.render_message('success','Status updated successfully');
                }
                else{
                    App.render_message('error',data.error);
                }
            },'json');
            return false;
        });

        $(document).on('keyup','.note_box',function(){
            $(this).closest('.user_row_item').find('.update_note_btn').show();
            return false;
        });

        $(document).on('click','.update_note_btn',function(){
            var closest_obj = $(this).closest('.user_row_item');
            var note = closest_obj.find('.note_box').val();
            var $this = $(this);
            $.post('/admin/user/update/note',{ user_id: closest_obj.attr('data-id'), note: note },function(data){
                if (data.status) {
                    $this.hide();
                    App.render_message('success','Note updated successfully');
                }
                else{
                    App.render_message('error',data.error);
                }
            },'json');
            return false;
        });

        $('#user_table').DataTable();
    });
</script>
@endsection
