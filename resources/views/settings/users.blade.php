@extends('settings.index')
@section('right-content')
    <div class="tab-content">
        <div class="wrap-content">
            {{ Form::model($users,['action' => ['SettingsController@updateUsers'],'method' => 'patch', 'autocomplete' => 'off','class'=>'data-form needs-validation']) }}
            <div class="main-content">
                <h3>Users</h3>
                <h4>User Roles and Permissions</h4>
                <p>You can set up the different users and their permissions to the system.</p>
                <div id="user_content">
                    @foreach($users as $key => $user)
                        <div class="form-items">
                            <div class="form-row">
                                <div class="col-md-1 text-center">
                                    <button type="button"
                                            class="btn delete-icon {{ ($user->user_id==$auth_user->user_id) ?' disabled':' remove_user' }}"
                                            data-id="{{ $user->user_id }}">
                                        <svg width="18" height="20" viewBox="0 0 18 20" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            @if($user->user_id == $auth_user->user_id)
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                      d="M12.125 2.5H15.5625C16.4254 2.5 17.125 3.19957 17.125 4.0625V5.3125C17.125 5.6577 16.8452 5.9375 16.5 5.9375H1.5C1.1548 5.9375 0.875 5.6577 0.875 5.3125V4.0625C0.875 3.19957 1.57457 2.5 2.4375 2.5H5.875V1.875C5.875 0.839453 6.71445 0 7.75 0H10.25C11.2855 0 12.125 0.839453 12.125 1.875V2.5ZM7.75 1.25C7.40547 1.25 7.125 1.53047 7.125 1.875V2.5H10.875V1.875C10.875 1.53047 10.5945 1.25 10.25 1.25H7.75ZM1.86579 7.39211C1.86048 7.2807 1.94934 7.1875 2.06087 7.1875H15.9387C16.0502 7.1875 16.1391 7.2807 16.1338 7.39211L15.6181 18.2141C15.5705 19.2156 14.7478 20 13.7455 20H4.25407C3.25173 20 2.42907 19.2156 2.38141 18.2141L1.86579 7.39211ZM12.1248 8.125C11.7795 8.125 11.4998 8.40469 11.4998 8.75V16.875C11.4998 17.2203 11.7795 17.5 12.1248 17.5C12.4701 17.5 12.7498 17.2203 12.7498 16.875V8.75C12.7498 8.40469 12.4701 8.125 12.1248 8.125ZM8.99977 8.125C8.65446 8.125 8.37477 8.40469 8.37477 8.75V16.875C8.37477 17.2203 8.65446 17.5 8.99977 17.5C9.34509 17.5 9.62477 17.2203 9.62477 16.875V8.75C9.62477 8.40469 9.34509 8.125 8.99977 8.125ZM5.87477 8.125C5.52946 8.125 5.24977 8.40469 5.24977 8.75V16.875C5.24977 17.2203 5.52946 17.5 5.87477 17.5C6.22009 17.5 6.49977 17.2203 6.49977 16.875V8.75C6.49977 8.40469 6.22009 8.125 5.87477 8.125Z"
                                                      fill="#86969E"></path>
                                            @else
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                      d="M13.125 2.5H16.5625C17.4254 2.5 18.125 3.19957 18.125 4.0625V5.3125C18.125 5.6577 17.8452 5.9375 17.5 5.9375H2.5C2.1548 5.9375 1.875 5.6577 1.875 5.3125V4.0625C1.875 3.19957 2.57457 2.5 3.4375 2.5H6.875V1.875C6.875 0.839453 7.71445 0 8.75 0H11.25C12.2855 0 13.125 0.839453 13.125 1.875V2.5ZM8.75 1.25C8.40547 1.25 8.125 1.53047 8.125 1.875V2.5H11.875V1.875C11.875 1.53047 11.5945 1.25 11.25 1.25H8.75ZM2.86579 7.39211C2.86048 7.2807 2.94934 7.1875 3.06087 7.1875H16.9387C17.0502 7.1875 17.1391 7.2807 17.1338 7.39211L16.6181 18.2141C16.5705 19.2156 15.7478 20 14.7455 20H5.25407C4.25173 20 3.42907 19.2156 3.38141 18.2141L2.86579 7.39211ZM13.1248 8.125C12.7795 8.125 12.4998 8.40469 12.4998 8.75V16.875C12.4998 17.2203 12.7795 17.5 13.1248 17.5C13.4701 17.5 13.7498 17.2203 13.7498 16.875V8.75C13.7498 8.40469 13.4701 8.125 13.1248 8.125ZM9.99977 8.125C9.65446 8.125 9.37477 8.40469 9.37477 8.75V16.875C9.37477 17.2203 9.65446 17.5 9.99977 17.5C10.3451 17.5 10.6248 17.2203 10.6248 16.875V8.75C10.6248 8.40469 10.3451 8.125 9.99977 8.125ZM6.87477 8.125C6.52946 8.125 6.24977 8.40469 6.24977 8.75V16.875C6.24977 17.2203 6.52946 17.5 6.87477 17.5C7.22009 17.5 7.49977 17.2203 7.49977 16.875V8.75C7.49977 8.40469 7.22009 8.125 6.87477 8.125Z"
                                                      fill="#FB275D"/></path>
                                            @endif
                                        </svg>
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::select('role['.$user->user_id.']', ['user'=>'User', 'financial-user'=>'Financial User','non-financial-user'=>'Non Financial User'], $user->role, ['id' => 'users_role_'.$user->user_id, 'class' => 'form-control customize-select',]) }}
                                        {{ Form::label('users_role_'.$user->user_id, 'Role') }}
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        {{ Form::email('email['.$user->user_id.']',$user->email,                                         ['id' => 'users_email_'.$key, 'class' => 'form-control','required'=>'required','placeholder'=>'mark.tradie@tradie.com',]) }}
                                        {{ Form::label('users_email_'.$key, 'Email') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button id="new_user_create" type="button" class="btn btn-default btn-add btn-add-item btn-extra-large">
                    Add New Item
                </button>
                <div id="remove_id_input">

                </div>
                <div class="bottom-control">
                    {{ Form::button('Cancel', ['class' => 'btn btn-medium btn-default btn-transparent']) }}
                    {{ Form::submit('Save', ['class' => 'btn btn-medium btn-default']) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
@section('view_script')
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('click', '.remove_user', function () {
                let remove_user_id = $(this).attr('data-id');
                if (remove_user_id != '') {
                    let bool = confirm('Delete User');
                    if (bool) {
                        if (!$(this).attr('data-target')) {
                            $('#remove_id_input').append(`<input type="hidden" name="removes_id[]" value="${remove_user_id}">`)
                        }
                        $(this).parent().parent().parent().remove();
                    }
                }
            });
            $(document).on('click','#new_user_create',function () {
                var val = ''+Math.ceil(Math.random()*1000);
                $('#user_content').append(`
                    <div class="form-items">
                         <div class="form-row">
                              <div class="col-md-1 text-center">
                                  <button type="button" class="btn delete-icon remove_user" data-id="${val}" data-target='false'>
                                     <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path fill-rule="evenodd" clip-rule="evenodd"
                                                      d="M13.125 2.5H16.5625C17.4254 2.5 18.125 3.19957 18.125 4.0625V5.3125C18.125 5.6577 17.8452 5.9375 17.5 5.9375H2.5C2.1548 5.9375 1.875 5.6577 1.875 5.3125V4.0625C1.875 3.19957 2.57457 2.5 3.4375 2.5H6.875V1.875C6.875 0.839453 7.71445 0 8.75 0H11.25C12.2855 0 13.125 0.839453 13.125 1.875V2.5ZM8.75 1.25C8.40547 1.25 8.125 1.53047 8.125 1.875V2.5H11.875V1.875C11.875 1.53047 11.5945 1.25 11.25 1.25H8.75ZM2.86579 7.39211C2.86048 7.2807 2.94934 7.1875 3.06087 7.1875H16.9387C17.0502 7.1875 17.1391 7.2807 17.1338 7.39211L16.6181 18.2141C16.5705 19.2156 15.7478 20 14.7455 20H5.25407C4.25173 20 3.42907 19.2156 3.38141 18.2141L2.86579 7.39211ZM13.1248 8.125C12.7795 8.125 12.4998 8.40469 12.4998 8.75V16.875C12.4998 17.2203 12.7795 17.5 13.1248 17.5C13.4701 17.5 13.7498 17.2203 13.7498 16.875V8.75C13.7498 8.40469 13.4701 8.125 13.1248 8.125ZM9.99977 8.125C9.65446 8.125 9.37477 8.40469 9.37477 8.75V16.875C9.37477 17.2203 9.65446 17.5 9.99977 17.5C10.3451 17.5 10.6248 17.2203 10.6248 16.875V8.75C10.6248 8.40469 10.3451 8.125 9.99977 8.125ZM6.87477 8.125C6.52946 8.125 6.24977 8.40469 6.24977 8.75V16.875C6.24977 17.2203 6.52946 17.5 6.87477 17.5C7.22009 17.5 7.49977 17.2203 7.49977 16.875V8.75C7.49977 8.40469 7.22009 8.125 6.87477 8.125Z"
                                                      fill="#FB275D"/></path></svg>
                                   </button>
                              </div>
                              <div class="col-md-4">
                              <div class="form-group">
                                    <select id="users_role_${val}" class="form-control customize-select" name="role[${val}]"><option value="user" selected="selected">User</option><option value="financial-user">Financial User</option><option value="non-financial-user">Non Financial User</option></select>
                                    <label for="users_role_${val}">Role</label>
                              </div>
                              </div>
                              <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="users_email_${val}">Email</label>
                                        <input id="users_email_${val}" class="form-control" required="required" placeholder="mark.tradie@tradie.com" name="email[${val}]" type="email" value="">
                                    </div>
                              </div>
                         </div>
                    </div>
                `)
            })
        })
    </script>
@endsection
