@extends('layout.layout')

@section('menu_name','Users Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
  <li class="breadcrumb-item active">Approval Maintenance</li>
</ol>
@endsection

@section('content')

<input type="hidden" id="tmp_username" />
<input type="hidden" id="tmp_name" />

<div class="col-md-12">
  <div class="col-md-2 mt-2">
    <button class="btn bt-action newUser mb-3" data-toggle="modal" data-target="#createModal">
      Create Approval
    </button>
  </div>
</div>
<form class="form-horizontal" role="form" method="get" action="{{route('approvalmt.index')}}">
  <div class="form-group row col-md-9 offset-3">
  
    <label for="s_name" class="col-md-1 mt-2 col-form-label">{{ __('Name') }}</label>
    <div class="col-md-3 mt-2">
      <input id="s_name" type="text" class="form-control" name="s_name" value="{{request()->input('s_name')}}" autocomplete="off" autofocus>
    </div>


    <div class="col-md-2 offset-md-1 mt-2">
      <button type="submit" class="btn bt-ref" id="btnsearch"  >Search</button>
      <button class="btn bt-action ml-2" id='btnrefresh' style="width: 40px !important"><i class="fa fa-sync"></i></button>
    </div>
  
  </div>
</form>
<div class="table-responsive tag-container col-lg-12 col-md-12">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th>Nama Approval</th>
        <th>Email</th>
        <th width="7%">Edit</th>
        <th width="7%">Delete</th>
      </tr>
    </thead>
    <tbody>
      @include('setting.approval.table')
    </tbody>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
  </table>

</div>
{{-- create modal --}}
<div id="createModal" class="modal fade bd-example-modal-lg" role="dialog" data-backdrop="static">
  <div class="modal-dialog modal-lg">
      <!-- konten modal-->
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title text-center" id="exampleModalLabel">Create Driver</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="panel-body">
              <!-- heading modal -->
              <form class="form-horizontal" role="form" method="POST" action="{{route('approvalmt.store')}}">
                  {{ method_field('post') }}
                  {{ csrf_field() }}
                  <div class="modal-body">
                      <div class="form-group row">
                          <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('Name') }}</label>
                          <div class="col-md-7">
                              <input type="text" class="col-md-8 form-control" name="name" placeholder="Name" required>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('Email') }}</label>
                          <div class="col-md-7">
                            
                              <input type="email" class="col-md-8 form-control" name="email" placeholder="Email" required >
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
</div>

{{-- edit modal --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Edit User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form class="form-horizontal" method="post" action="{{route('approvalmt.update', 'Edit')}}">
        @method('put')
        {{ csrf_field() }}
        <input type="hidden" name="e_id" id="e_id">
        <div class="modal-body">
          <div class="form-group row">
              <label for="e_name" class="col-md-3 col-form-label text-md-right">{{ __('Name') }}</label>
              <div class="col-md-7">
                  <input type="text" name="e_name" id="e_name" class="col-md-8" placeholder="Name" required>
              </div>
          </div>
          <div class="form-group row">
              <label for="e_email" class="col-md-3 col-form-label text-md-right">{{ __('Email') }}</label>
              <div class="col-md-7">
                
                  <input type="e_email" name="e_email" id="e_email" class="col-md-8" placeholder="Email" required >
              
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

      <form action="{{route('approvalmt.destroy', 'Delete')}}" method="post">
        @method('delete')
        {{ csrf_field() }}

        <div class="modal-body">

          <input type="hidden" name="d_id" id="d_id" value="">
          

          <div class="container">
            <div class="row">
              Are you sure you want to delete approval :&nbsp; <b><a name="d_name" id="d_name"></a></b> &nbsp;?
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


<div id="loader" class="lds-dual-ring hidden overlay"></div>

@endsection

@section('scripts')

<script type="text/javascript">

  $(document).ready(function() {


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


  $(document).on('click', '.deleteapproval', function() { // Click to only happen on announce links

    //alert('tst');
    var uid = $(this).data('id');
    var name = $(this).data('name');
    var email = $(this).data('email');
    

    document.getElementById("d_id").value = uid;
    
    document.getElementById("d_name").innerHTML = name;
    

  });
  $(document).on('click', '.editapproval', function() { // Click to only happen on announce links

      // alert('tst');
      var uid = $(this).data('id');
      var name = $(this).data('name');
      var email = $(this).data('email');


      document.getElementById("e_name").value = name;
      document.getElementById("e_id").value = uid;
      document.getElementById("e_email").value = email;
  });

  $(document).on('click','#btnrefresh',function(){
    document.getElementById('s_name').value = '';
  })

</script>

@endsection