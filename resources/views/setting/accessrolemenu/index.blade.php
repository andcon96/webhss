@extends('layout.layout')

@section('menu_name','Role Menu Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
  <li class="breadcrumb-item active">Role Menu Maintenance</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->
<div class="table-responsive col-lg-12 col-md-12 mt-3">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th>Role</th>
        <th>Role Type</th>
        <th>Edit</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($roleAccess as $show)
      <tr>
        <td data-title="Role">{{str_replace('_', ' ', $show->getRole->role)}}</td>
        <td data-title="Role Type">{{str_replace('_', ' ', $show->role_type)}}</td>
        <td data-title="Edit" class="action">
          @if($show->getRole->role !== 'Super_User')
          <a href="" class="editUser" data-toggle="modal" data-target="#editModal" data-id="{{$show->id}}"
            data-role="{{$show->getRole->role}}" data-desc="{{$show->role_type}}"><i class="fas fa-edit"></i></a>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>




<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  
  aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Edit Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{route('accessrolemenu.update', 'test')}}" method="post">

        {{ method_field('patch') }}
        {{ csrf_field() }}

        <input type="hidden" name="edit_id" id="edit_id" value="">

        <div class="modal-body">
          <div class="form-group row">
            <label for="role" class="col-md-3 col-form-label text-md-right">{{ __('Role') }}</label>
            <div class="col-md-7">
              <input id="role" type="text" class="form-control" name="role" value="" disabled>
            </div>
          </div>
          <div class="form-group row">
            <label for="desc" class="col-md-3 col-form-label text-md-right">{{ __('Desc') }}</label>
            <div class="col-md-7">
              <input id="desc" type="text" class="form-control" name="desc" value="" disabled>
            </div>
          </div>

          <div class="form-group">
            <h5>
              <center><strong>Menu Access</strong></center>
            </h5>
            <hr>
          </div>

          <div class="form-group">
            <h6>
              <center><strong>Sales Order</strong></center>
              </h5>
              <hr>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('SO Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbSOMT">
                <input type="checkbox" id="cbSOMT" name="cbSOMT" value="SO01" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('SO Alokasi Sangu') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbSOAloSangu">
                <input type="checkbox" id="cbSOAloSangu" name="cbSOAloSangu" value="SO02" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('SO Sangu Browse') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbSOBR">
                <input type="checkbox" id="cbSOBR" name="cbSOBR" value="SO03" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group">
            <h6>
              <center><strong>Trip</strong></center>
              </h5>
              <hr>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Trip Browse') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbTripBrowse">
                <input type="checkbox" id="cbTripBrowse" name="cbTripBrowse" value="TR01" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Lapor Trip Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbTripLapor">
                <input type="checkbox" id="cbTripLapor" name="cbTripLapor" value="TR02" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Lapor Surat Jalan') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbSJLapor">
                <input type="checkbox" id="cbSJLapor" name="cbSJLapor" value="TR03" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Lapor Kerusakan') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbKerusakan">
                <input type="checkbox" id="cbKerusakan" name="cbKerusakan" value="TR04" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <label for="cbBiaya" class="col-md-6 col-form-label text-md-right">{{ __('Lapor Biaya Tambahan') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbBiaya">
                <input type="checkbox" id="cbBiaya" name="cbBiaya" value="TR05" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <h6>
              <center><strong>Driver Check In / Out</strong></center>
            </h6>
            <hr>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Driver Check In / Out') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbDRInOut">
                <input type="checkbox" id="cbDRInOut" name="cbDRInOut" value="DR01" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <h6>
              <center><strong>Fitur Tambahan</strong></center>
            </h6>
            <hr>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Pindah Domain') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbPdDomain">
                <input type="checkbox" id="cbPdDomain" name="cbPdDomain" value="PD01" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-info bt-action" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success bt-action">Save</button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection

@section('scripts')


<!-- Pass Value Modal Edit & Checkbox Setting -->
<script type="text/javascript">
  $(document).on('click','.editUser',function(){ // Click to only happen on announce links
     
     //alert('tst');
     
     var idrole = $(this).data('id');
     var role = $(this).data('role');
     var desc = $(this).data('desc');
     if (desc == "Super_User") {
       desc = 'Super User'
     }

     document.getElementById("edit_id").value = idrole;
     document.getElementById("role").value = role;
     document.getElementById("desc").value = desc;


     jQuery.ajax({
          type : "get",
          url : "{{route("accessmenu") }}",
          data:{
            search : idrole,
          },
          success:function(data){
            // /alert(data);
            var totmenu = data;
            
            // Centang Checkbox berdasarkan data

            if(totmenu.search("SO01") >= 0){
              document.getElementById("cbSOMT").checked = true;  
            }else{
              document.getElementById("cbSOMT").checked = false;
            }
            if(totmenu.search("SO02") >= 0){
              document.getElementById("cbSOAloSangu").checked = true;  
            }else{
              document.getElementById("cbSOAloSangu").checked = false;
            }
            if(totmenu.search("SO03") >= 0){
              document.getElementById("cbSOBR").checked = true;  
            }else{
              document.getElementById("cbSOBR").checked = false;
            }
            if(totmenu.search("TR01") >= 0){
              document.getElementById("cbTripBrowse").checked = true;  
            }else{
              document.getElementById("cbTripBrowse").checked = false;
            }
            if(totmenu.search("TR02") >= 0){
              document.getElementById("cbTripLapor").checked = true;  
            }else{
              document.getElementById("cbTripLapor").checked = false;
            }
            if(totmenu.search("TR03") >= 0){
              document.getElementById("cbSJLapor").checked = true;  
            }else{
              document.getElementById("cbSJLapor").checked = false;
            }
            if(totmenu.search("TR04") >= 0){
              document.getElementById("cbKerusakan").checked = true;  
            }else{
              document.getElementById("cbKerusakan").checked = false;
            }
            if(totmenu.search("TR05") >= 0){
              document.getElementById("cbBiaya").checked = true;  
            }else{
              document.getElementById("cbBiaya").checked = false;
            }
            if(totmenu.search("DR01") >= 0){
              document.getElementById("cbDRInOut").checked = true;  
            }else{
              document.getElementById("cbDRInOut").checked = false;
            }
            if(totmenu.search("PD01") >= 0){
              document.getElementById("cbPdDomain").checked = true;  
            }else{
              document.getElementById("cbPdDomain").checked = false;
            }
          }
      });
     
     });

</script>
@endsection