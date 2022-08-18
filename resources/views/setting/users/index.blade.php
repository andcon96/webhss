@extends('layout.layout')

@section('menu_name','Users Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
  <li class="breadcrumb-item active">User Maintenance</li>
</ol>
@endsection

@section('content')

<input type="hidden" id="tmp_username" />
<input type="hidden" id="tmp_name" />

<div class="form-group row col-md-12">
  <div class="col-md-2 mt-2">
    <button class="btn bt-action newUser mb-3" data-toggle="modal" data-target="#createModal">
      Create User
    </button>
  </div>
  <label for="s_username" class="col-md-1 mt-2 col-form-label">{{ __('Username') }}</label>
  <div class="col-md-2 mt-2">
    <input id="s_username" type="text" class="form-control" name="s_username" autocomplete="off" autofocus>
  </div>

  <label for="s_name" class="col-md-1 mt-2 col-form-label">{{ __('Name') }}</label>
  <div class="col-md-2 mt-2">
    <input id="s_name" type="text" class="form-control" name="s_name" autocomplete="off" autofocus>
  </div>

  <div class="col-md-2 offset-md-1 mt-2">
    <input type="button" class="btn bt-ref" id="btnsearch" value="Search" />
    <button class="btn bt-action ml-2" id='btnrefresh' style="width: 40px !important"><i class="fa fa-sync"></i></button>
  </div>
</div>

<div class="table-responsive tag-container col-lg-12 col-md-12">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th>Nama User</th>
        <th>Kode User</th>
        <th>Role</th>
        <th>Role Type</th>
        <th>Status</th>
        <th width="7%">Edit</th>
        <th width="7%">Pass</th>
        <th width="7%">Active</th>
      </tr>
    </thead>
    <tbody>
      @include('setting.users.table')
    </tbody>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
  </table>

</div>

<div class="modal fade" id="createModal" role="dialog" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Create User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form class="form-horizontal" method="post" action="{{route('usermaint.store')}}">
        {{ method_field('post') }}
        {{ csrf_field() }}

        <div class="modal-body">
          <div class="form-group row">
            {{-- <label for="domain" class="col-md-3 col-form-label text-md-right">Domain</label>
            <div class="col-md-7">
              <select id="domain" class="form-control role" name="domain" required autofocus>
                <option value=""> Select Data </option>
                @foreach($domain as $domains)
                <option value="{{$domains->domain_code}}">{{$domains->domain_code}} -- {{$domains->domain_desc}}</option>
                @endforeach
              </select>
            </div> --}}
          </div>
          <div class="form-group row">
            <label for="username" class="col-md-3 col-form-label text-md-right">Kode User</label>
            <div class="col-md-5 {{ $errors->has('uname') ? 'has-error' : '' }}">
              <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" autocomplete="off" required autofocus>
            </div>
          </div>
          <div class="form-group row">
            <label for="name" class="col-md-3 col-form-label text-md-right">Nama User</label>
            <div class="col-md-5">
              <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" autocomplete="off" autofocus required>
            </div>
          </div>
          <div class="form-group row">
            <label for="role" class="col-md-3 col-form-label text-md-right">Role</label>
            <div class="col-md-7">
              <select id="role" class="form-control role" name="role" required autofocus>
                <option value=""> Select Data </option>
                <option value="User"> User </option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="roletype" class="col-md-3 col-form-label text-md-right">Role Access</label>
            <div class="col-md-7">
              <select id="roletype" class="form-control roletype" name="roletype" required autofocus>
                <option value=""> Select Data </option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="password" class="col-md-3 col-form-label text-md-right">Password</label>
            <div class="col-md-5">
              <input id="password" type="password" class="form-control" name="password" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="password-confirm" class="col-md-3 col-form-label text-md-right">Confirm Password</label>
            <div class="col-md-5">
              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-info bt-action" id="btnclose" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success bt-action" id="btnconf">Save</button>
          <button type="button" class="btn bt-action" id="btnloading" style="display:none">
            <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Edit User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form class="form-horizontal" method="post" action="{{route('usermaint.update', 'Edit')}}">
        @method('put')
        {{ csrf_field() }}

        <div class="modal-body">
          <input type="hidden" name='t_id' id='t_id' />
          <input type="hidden" name='role' id='t_role'>
          <input type="hidden" name='d_suppid' id='t_suppid'>
          <input type="hidden" name='d_suppname' id='t_suppname'>
          <input type="hidden" name="lastPage" value="{{$users->lastPage()}}" />
          {{-- <div class="form-group row">
            <label for="t_domain" class="col-md-3 col-form-label text-md-right">Domain</label>
            <div class="col-md-7 {{ $errors->has('d_uname') ? 'has-error' : '' }}">
              <select id="t_domain" class="form-control roletype" name="domain" required autofocus>
                <option value=""> Select Data </option>
                @foreach($domain as $domains)
                  <option value="{{$domains->domain_code}}">{{$domains->domain_code}} - {{$domains->domain_desc}}</option>
                @endforeach
              </select>
            </div>
          </div> --}}
          <div class="form-group row">
            <label for="d_uname" class="col-md-3 col-form-label text-md-right">Kode User</label>
            <div class="col-md-7 {{ $errors->has('d_uname') ? 'has-error' : '' }}">
              <input id="d_uname" type="text" class="form-control" name="d_uname" value="{{ old('d_uname') }}" readonly autofocus>
            </div>
          </div>
          <div class="form-group row">
            <label for="d_name" class="col-md-3 col-form-label text-md-right">Nama User</label>
            <div class="col-md-7">
              <input id="d_name" type="text" class="form-control" autocomplete="off" name="name" value="{{ old('name') }}" autofocus required>
            </div>
          </div>
          <div class="form-group row">
            <label for="d_email" class="col-md-3 col-form-label text-md-right">Role Type</label>
            <div class="col-md-7">
              <select id="t_roletype" class="form-control roletype" name="roletype" required autofocus>
                <option value=""> Select Data </option>
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-info bt-action" id='e_btnclose' data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success bt-action" id='e_btnconf'>Save</button>
          <button type="button" class="btn bt-action" id="e_btnloading" style="display:none">
            <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Status User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{route('usermaint.destroy', 'Delete')}}" method="post">
        @method('delete')
        {{ csrf_field() }}

        <div class="modal-body">

          <input type="hidden" name="temp_id" id="temp_id" value="">
          <input type="hidden" name="temp_active" id="temp_active">

          <div class="container">
            <div class="row">
              Are you sure you want to &nbsp; <a name="temp_status" id="temp_status"></a> &nbsp; user :&nbsp; <a name="temp_uname" id="temp_uname"></a> &nbsp;?
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-info bt-action" id="d_btnclose" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger bt-action" id="d_btnconf">Save</button>
          <button type="button" class="btn bt-action" id="d_btnloading" style="display:none">
            <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="changepassModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form class="form-horizontal" method="post" action="/adminchangepass">

        {{ csrf_field() }}

        <div class="modal-body">
          <input type='hidden' name='c_id' id='c_id' />

          <div class="form-group row">
            <label for="c_uname" class="col-md-3 col-form-label text-md-right">Kode User</label>
            <div class="col-md-7 {{ $errors->has('d_uname') ? 'has-error' : '' }}">
              <input id="c_uname" type="text" class="form-control" name="c_uname" value="{{ old('d_uname') }}" readonly autofocus>
            </div>
          </div>
          <div class="form-group row">
            <label for="c_password" class="col-md-3 col-form-label text-md-right">Password</label>
            <div class="col-md-6">
              <input id="c_password" type="password" class="form-control" name="c_password" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="c_password-confirm" class="col-md-3 col-form-label text-md-right">Confirm Password</label>
            <div class="col-md-6">
              <input id="c_password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
          </div>


        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-info bt-action" id="c_btnclose" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success bt-action" id="c_btnconf">Save</button>
          <button type="button" class="btn bt-action" id="c_btnloading" style="display:none">
            <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<div id="loader" class="lds-dual-ring hidden overlay"></div>

@endsection

@section('scripts')

<script type="text/javascript">

  function fetch_data(page, username, name) {
    $.ajax({
      url: "/user/getdata?page=" + page + "&username=" + username + "&name=" + name,
      beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
        $('#loader').removeClass('hidden')
      },
      success: function(data) {
        console.log(data);
        $('tbody').html('');
        $('tbody').html(data);
      },
      complete: function() { // Set our complete callback, adding the .hidden class and hiding the spinner.
        $('#loader').addClass('hidden')
      },
    })
  }


  $(document).on('click', '#btnsearch', function() {
    var username = $('#s_username').val();
    var name = $('#s_name').val();

    // var column_name = $('#hidden_column_name').val();
    // var sort_type = $('#hidden_sort_type').val();
    var page = 1;

    document.getElementById('tmp_username').value = username;
    document.getElementById('tmp_name').value = name;


    fetch_data(page, username, name);
  });


  $(document).on('click', '.pagination a', function(event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    // $('#hidden_page').val(page);
    // var column_name = $('#hidden_column_name').val();
    // var sort_type = $('#hidden_sort_type').val();

    var username = $('#tmp_username').val();
    var name = $('#tmp_name').val();

    fetch_data(page, username, name);
  });

  $(document).on('click', '#btnrefresh', function() {
    var username = '';
    var name = '';

    var page = 1;

    document.getElementById('s_username').value = '';
    document.getElementById('s_name').value = '';

    document.getElementById('tmp_username').value = username;
    document.getElementById('tmp_name').value = name;


    fetch_data(page, username, name);
  });


  $(document).on('click', '.newUser', function() {
    document.getElementById('username').value = '';
    document.getElementById('name').value = '';
    // document.getElementById('domain').value = '';
    document.getElementById('email').value = '';
    document.getElementById('password').value = '';
    document.getElementById('password-confirm').value = '';
  });

  $(document).on('click', '.editUser', function() { // Click to only happen on announce links

    // alert('123');
    var uid = $(this).data('id');
    var username = $(this).data('uname');
    var name = $(this).data('name');
    var dept = $(this).data('dept');
    // var domain = $(this).data('domain');
    var email = $(this).data('email');

    var role_type = $(this).data('roletype');
    var role = $(this).data('role');


    document.getElementById("t_id").value = uid;
    document.getElementById("d_uname").value = username;
    document.getElementById("d_name").value = name;
    // document.getElementById("d_domain").value = domain;
    document.getElementById("t_role").value = role;
    $('#e_dept').val(dept);
    // $('#t_domain').val(domain).trigger('change');

    jQuery.ajax({
      type: "get",
      url: "{{URL::to("searchoptionuser") }}",
      data: {
        search: role,
      },
      success: function(data) {
        console.log(data);
        $('#t_roletype').find('option').remove().end().append('<option value="">Select Data</option>');
        for (var i = 0; i < data.length; i++) {
          if (role_type == data[i].role_type) {
            $('#t_roletype').append('<option value="' + data[i].id + '" selected>' + data[i].role_type + '</option>');
          } else {
            $('#t_roletype').append('<option value="' + data[i].id + '">' + data[i].role_type + '</option>');
          }
        }
      }
    });

  });

  $(document).ready(function() {
    // $("#domain,#role,#roletype,.roletype").select2({
    //   width: '100%'
    // });
    $("#role,#roletype,.roletype").select2({
      width: '100%'
    });

    $("#role").change(function() {
      var value = $(this).val();
      jQuery.ajax({
        type: "get",
        url: "{{URL::to("searchoptionuser") }}",
        data: {
          search: value,
        },
        success: function(data) {
          console.log(data);
          $('#roletype').find('option').remove().end().append('<option value="">Select Data</option>');
          for (var i = 0; i < data.length; i++) {
            if (data[i].role_type == 'Super_User') {
              $('#roletype').append('<option value="' + data[i].id + '">Super User</option>');
            } else {
              $('#roletype').append('<option value="' + data[i].id + '">' + data[i].role_type + '</option>');
            }
          }

          $('#roletype').trigger('change');
        }
      });
    });
    $('form').on("submit", function() {
      document.getElementById('btnclose').style.display = 'none';
      document.getElementById('btnconf').style.display = 'none';
      document.getElementById('btnloading').style.display = '';
      document.getElementById('e_btnclose').style.display = 'none';
      document.getElementById('e_btnconf').style.display = 'none';
      document.getElementById('e_btnloading').style.display = '';
      document.getElementById('d_btnclose').style.display = 'none';
      document.getElementById('d_btnconf').style.display = 'none';
      document.getElementById('d_btnloading').style.display = '';
      document.getElementById('c_btnclose').style.display = 'none';
      document.getElementById('c_btnconf').style.display = 'none';
      document.getElementById('c_btnloading').style.display = '';
    });
  });

  $(document).on('click', '.deleteUser', function() { // Click to only happen on announce links

    //alert('tst');
    var uid = $(this).data('id');
    var uname = $(this).data('role');
    var status = $(this).data('status');
    var active = $(this).data('active');

    document.getElementById("temp_id").value = uid;
    document.getElementById("temp_active").value = active;
    document.getElementById("temp_uname").innerHTML = uname;
    document.getElementById("temp_status").innerHTML = status;

  });

  $(document).on('click', '.changepass', function() { // Click to only happen on announce links

    var uid = $(this).data('id');
    var uname = $(this).data('uname');

    document.getElementById("c_id").value = uid;
    document.getElementById("c_uname").value = uname;
  });
</script>

@endsection