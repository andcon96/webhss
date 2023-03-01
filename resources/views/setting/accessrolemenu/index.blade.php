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
            <h5>
              <center><strong>All Role</strong></center>
              </h5>
              <hr>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('All Role') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbAll">
                <input type="checkbox" id="cbAll" value="SS" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>          
          <div class="form-group">
            <h5>
              <center><strong>Sales Order</strong></center>
              </h5>
              <hr>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('CO Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbCOMT">
                <input type="checkbox" id="cbCOMT" name="cbCOMT" value="SO04" />
                <div class="slider round"></div>
              </label>
            </div>
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
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('SJ Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbSJMT">
                <input type="checkbox" id="cbSJMT" name="cbSJMT" value="SO05" />
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

          {{-- <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Lapor Trip Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbTripLapor">
                <input type="checkbox" id="cbTripLapor" name="cbTripLapor" value="TR02" />
                <div class="slider round"></div>
              </label>
            </div>
          </div> --}}

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
            <label for="cbConfirmSJ" class="col-md-6 col-form-label text-md-right">{{ __('Confirm Surat Jalan') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbConfirmSJ">
                <input type="checkbox" id="cbConfirmSJ" name="cbConfirmSJ" value="TR06" />
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
              <center><strong>Invoice</strong></center>
              </h5>
              <hr>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Invoice') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbInvoice">
                <input type="checkbox" id="cbInvoice" name="cbInvoice" value="IV01" />
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
              <center><strong>Report</strong></center>
            </h6>
            <hr>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Report Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbRPMT">
                <input type="checkbox" id="cbRPMT" name="cbRPMT" value="RP01" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group">
            <h6>
              <center><strong>Cicilan</strong></center>
              </h5>
              <hr>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Cicilan') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbCicilan">
                <input type="checkbox" id="cbCicilan" name="cbCicilan" value="CI01" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Pembayaran Cicilan') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbBayarCicilan">
                <input type="checkbox" id="cbBayarCicilan" name="cbBayarCicilan" value="CI02" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group">
            <h5>
              <center><strong>Setting Web</strong></center>
              </h5>
              <hr>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('User Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMT01">
                <input type="checkbox" id="cbMT01" name="cbMT01" value="MT01" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Role Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMT02">
                <input type="checkbox" id="cbMT02" name="cbMT02" value="MT02" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Role Menu Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMT03">
                <input type="checkbox" id="cbMT03" name="cbMT03" value="MT03" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Ship From Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMT06">
                <input type="checkbox" id="cbMT06" name="cbMT06" value="MT06" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Kerusakan Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMT08">
                <input type="checkbox" id="cbMT08" name="cbMT08" value="MT08" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Struktur Kerusakan Maintenance') }}</label>
            <div class="col-md-6">  
              <label class="switch" for="cbMT09">
                <input type="checkbox" id="cbMT09" name="cbMT09" value="MT09" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Truck Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMT10">
                <input type="checkbox" id="cbMT10" name="cbMT10" value="MT10" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Tipe Truck Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMT11">
                <input type="checkbox" id="cbMT11" name="cbMT11" value="MT11" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Prefix Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMT12">
                <input type="checkbox" id="cbMT12" name="cbMT12" value="MT12" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('COGS Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMT14">
                <input type="checkbox" id="cbMT14" name="cbMT14" value="MT14" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Invoice Price Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMT15">
                <input type="checkbox" id="cbMT15" name="cbMT15" value="MT15" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Approval Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMT16">
                <input type="checkbox" id="cbMT16" name="cbMT16" value="MT16" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Driver') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMT19">
                <input type="checkbox" id="cbMT19" name="cbMT19" value="MT19" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Driver Nopol') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMT20">
                <input type="checkbox" id="cbMT20" name="cbMT20" value="MT20" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Gandengan Master') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMT21">
                <input type="checkbox" id="cbMT21" name="cbMT21" value="MT21" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Bank Account Master') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMT22">
                <input type="checkbox" id="cbMT22" name="cbMT22" value="MT22" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <h6>
              <center><strong>Setting QAD</strong></center>
            </h6>
            <hr>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Customer Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMQ01">
                <input type="checkbox" id="cbMQ01" name="cbMQ01" value="MQ01" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Ship To Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMQ02">
                <input type="checkbox" id="cbMQ02" name="cbMQ02" value="MQ02" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Item Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMQ03">
                <input type="checkbox" id="cbMQ03" name="cbMQ03" value="MQ03" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('WSA Qxtend Maintenance') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbMQ04">
                <input type="checkbox" id="cbMQ04" name="cbMQ04" value="MQ04" />
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
  $(document).on('change','#cbAll',function(e){
    if(document.getElementById("cbAll").checked == true)
    {
        document.getElementById("cbSOMT").checked = true; 
        document.getElementById("cbCOMT").checked = true; 
        document.getElementById("cbSJMT").checked = true;  
        document.getElementById("cbTripBrowse").checked = true;
        document.getElementById("cbTripLapor").checked = true;
        document.getElementById("cbSJLapor").checked = true;  
        document.getElementById("cbKerusakan").checked = true;
        document.getElementById("cbBiaya").checked = true;  
        document.getElementById("cbDRInOut").checked = true; 
        document.getElementById("cbInvoice").checked = true; 
        document.getElementById("cbCicilan").checked = true; 
        document.getElementById("cbBayarCicilan").checked = true; 
        document.getElementById("cbRPMT").checked = true;  
        document.getElementById("cbMT01").checked = true;  
        document.getElementById("cbMT02").checked = true;  
        document.getElementById("cbMT03").checked = true;  
        document.getElementById("cbMQ01").checked = true;  
        document.getElementById("cbMQ02").checked = true;  
        document.getElementById("cbMT06").checked = true;  
        document.getElementById("cbMQ03").checked = true;  
        document.getElementById("cbMT08").checked = true;  
        document.getElementById("cbMT09").checked = true;  
        document.getElementById("cbMT10").checked = true;  
        document.getElementById("cbMT11").checked = true;  
        document.getElementById("cbMT12").checked = true;  
        document.getElementById("cbMQ04").checked = true;  
        document.getElementById("cbMT14").checked = true;  
        document.getElementById("cbMT15").checked = true;  
        document.getElementById("cbMT16").checked = true;  
        document.getElementById("cbMT21").checked = true;  
        document.getElementById("cbMT22").checked = true;  
        document.getElementById("cbMT19").checked = true;  
        document.getElementById("cbMT20").checked = true;  
    }
    else{
      document.getElementById("cbSOMT").checked = false; 
        document.getElementById("cbCOMT").checked = false; 
        document.getElementById("cbSJMT").checked = false;  
        document.getElementById("cbTripBrowse").checked = false;
        document.getElementById("cbTripLapor").checked = false;
        document.getElementById("cbSJLapor").checked = false;  
        document.getElementById("cbKerusakan").checked = false;
        document.getElementById("cbBiaya").checked = false;  
        document.getElementById("cbDRInOut").checked = false;
        document.getElementById("cbInvoice").checked = false;  
        document.getElementById("cbCicilan").checked = false; 
        document.getElementById("cbBayarCicilan").checked = false; 
        document.getElementById("cbRPMT").checked = false;  
        document.getElementById("cbMT01").checked = false;  
        document.getElementById("cbMT02").checked = false;  
        document.getElementById("cbMT03").checked = false;  
        document.getElementById("cbMQ01").checked = false;  
        document.getElementById("cbMQ02").checked = false;  
        document.getElementById("cbMT06").checked = false;  
        document.getElementById("cbMQ03").checked = false;  
        document.getElementById("cbMT08").checked = false;  
        document.getElementById("cbMT09").checked = false;  
        document.getElementById("cbMT10").checked = false;  
        document.getElementById("cbMT11").checked = false;  
        document.getElementById("cbMT12").checked = false;  
        document.getElementById("cbMQ04").checked = false;  
        document.getElementById("cbMT14").checked = false;  
        document.getElementById("cbMT15").checked = false;  
        document.getElementById("cbMT16").checked = false;  
        document.getElementById("cbMT21").checked = false;  
        document.getElementById("cbMT22").checked = false;  
        document.getElementById("cbMT19").checked = false;  
        document.getElementById("cbMT20").checked = false;  
    }
  })

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
            // alert(data);
            var totmenu = data;
            // Centang Checkbox berdasarkan data

            if(totmenu.search("SO01") >= 0){
              document.getElementById("cbSOMT").checked = true;  
            }else{
              document.getElementById("cbSOMT").checked = false;
            }
            if(totmenu.search("SO04") >= 0){
              document.getElementById("cbCOMT").checked = true;  
            }else{
              document.getElementById("cbCOMT").checked = false;
            }
            if(totmenu.search("SO05") >= 0){
              document.getElementById("cbSJMT").checked = true;  
            }else{
              document.getElementById("cbSJMT").checked = false;
            }
            if(totmenu.search("IV01") >= 0){
              document.getElementById("cbInvoice").checked = true;  
            }else{
              document.getElementById("cbInvoice").checked = false;
            }
            if(totmenu.search("TR01") >= 0){
              document.getElementById("cbTripBrowse").checked = true;  
            }else{
              document.getElementById("cbTripBrowse").checked = false;
            }
            // if(totmenu.search("TR02") >= 0){
            //   document.getElementById("cbTripLapor").checked = true;  
            // }else{
            //   document.getElementById("cbTripLapor").checked = false;
            // }
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
            if(totmenu.search("TR06") >= 0){
              document.getElementById("cbConfirmSJ").checked = true;  
            }else{
              document.getElementById("cbConfirmSJ").checked = false;
            }

            if(totmenu.search("DR01") >= 0){
              document.getElementById("cbDRInOut").checked = true;  
            }else{
              document.getElementById("cbDRInOut").checked = false;
            }

            if(totmenu.search("RP01") >= 0){
              document.getElementById("cbRPMT").checked = true;  
            }else{
              document.getElementById("cbRPMT").checked = false;
            }

            if(totmenu.search("CI01") >= 0){
              document.getElementById("cbCicilan").checked = true;  
            }else{
              document.getElementById("cbCicilan").checked = false;
            }

            if(totmenu.search("CI02") >= 0){
              document.getElementById("cbBayarCicilan").checked = true;  
            }else{
              document.getElementById("cbBayarCicilan").checked = false;
            }

            if(totmenu.search("MT01") >= 0){
              document.getElementById("cbMT01").checked = true;  
            }else{
              document.getElementById("cbMT01").checked = false;
            }
            
            if(totmenu.search("MT02") >= 0){
              document.getElementById("cbMT02").checked = true;  
            }else{
              document.getElementById("cbMT02").checked = false;
            }
            if(totmenu.search("MT03") >= 0){
              document.getElementById("cbMT03").checked = true;  
            }else{
              document.getElementById("cbMT03").checked = false;
            }
            if(totmenu.search("MT06") >= 0){
              document.getElementById("cbMT06").checked = true;  
            }else{
              document.getElementById("cbMT06").checked = false;
            }
            if(totmenu.search("MT08") >= 0){
              document.getElementById("cbMT08").checked = true;  
            }else{
              document.getElementById("cbMT08").checked = false;
            }
            if(totmenu.search("MT09") >= 0){
              document.getElementById("cbMT09").checked = true;  
            }else{
              document.getElementById("cbMT09").checked = false;
            }
            if(totmenu.search("MT10") >= 0){
              document.getElementById("cbMT10").checked = true;  
            }else{
              document.getElementById("cbMT10").checked = false;
            }
            if(totmenu.search("MT11") >= 0){
              document.getElementById("cbMT11").checked = true;  
            }else{
              document.getElementById("cbMT11").checked = false;
            }
            if(totmenu.search("MT12") >= 0){
              document.getElementById("cbMT12").checked = true;  
            }else{
              document.getElementById("cbMT12").checked = false;
            }
            if(totmenu.search("MT14") >= 0){
              document.getElementById("cbMT14").checked = true;  
            }else{
              document.getElementById("cbMT14").checked = false;
            }
            if(totmenu.search("MT15") >= 0){
              document.getElementById("cbMT15").checked = true;  
            }else{
              document.getElementById("cbMT15").checked = false;
            }
            if(totmenu.search("MT16") >= 0){
              document.getElementById("cbMT16").checked = true;  
            }else{
              document.getElementById("cbMT16").checked = false;
            }
            if(totmenu.search("MT21") >= 0){
              document.getElementById("cbMT21").checked = true;  
            }else{
              document.getElementById("cbMT21").checked = false;
            }
            if(totmenu.search("MT22") >= 0){
              document.getElementById("cbMT22").checked = true;  
            }else{
              document.getElementById("cbMT22").checked = false;
            }
            if(totmenu.search("MT19") >= 0){
              document.getElementById("cbMT19").checked = true;  
            }else{
              document.getElementById("cbMT19").checked = false;
            }
            if(totmenu.search("MT20") >= 0){
              document.getElementById("cbMT20").checked = true;  
            }else{
              document.getElementById("cbMT20").checked = false;
            }

            
            if(totmenu.search("MQ01") >= 0){
              document.getElementById("cbMQ01").checked = true;  
            }else{
              document.getElementById("cbMQ01").checked = false;
            }
            if(totmenu.search("MQ02") >= 0){
              document.getElementById("cbMQ02").checked = true;  
            }else{
              document.getElementById("cbMQ02").checked = false;
            }
            if(totmenu.search("MQ03") >= 0){
              document.getElementById("cbMQ03").checked = true;  
            }else{
              document.getElementById("cbMQ03").checked = false;
            }
            if(totmenu.search("MQ04") >= 0){
              document.getElementById("cbMQ04").checked = true;  
            }else{
              document.getElementById("cbMQ04").checked = false;
            }
          }
      });
     
     });

</script>
@endsection