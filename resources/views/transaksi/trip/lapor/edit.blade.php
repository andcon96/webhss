@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Pelaporan Trip</li>
</ol>
@endsection

@section('content')
<form action="{{ route('laportrip.update',$data->id) }}" id="submit" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <input type="hidden" name="idtruck" value="{{$data->sj_truck_id}}">
        <input type="hidden" name="idsjmaster" value="{{$data->id}}">
        <input type="hidden" name="idsomaster" value="{{$data->getSOMaster->id}}">
        <div class="form-group row col-md-12">
            <label for="sonbr" class="col-md-2 col-form-label text-md-right">Nomor SO</label>
            <div class="col-md-3">
                <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{$data->getSOMaster->so_nbr}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="customer" class="col-md-3 col-form-label text-md-right">Customer</label>
            <div class="col-md-3">
                <input id="customer" type="text" class="form-control" name="customer" value="{{$data->getSOMaster->getCOMaster->co_cust_code}} - {{$data->getSOMaster->getCOMaster->getCustomer->cust_desc}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="shipfrom" class="col-md-2 col-form-label text-md-right">Ship From</label>
            <div class="col-md-3">
                <input id="shipfrom" type="text" class="form-control" name="shipfrom" value="{{$data->getSOMaster->so_ship_from}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
            <div class="col-md-3">
                <input id="shipto" type="text" class="form-control" name="shipto" value="{{$data->getSOMaster->so_ship_to}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="duedate" class="col-md-2 col-form-label text-md-right">Due Date</label>
            <div class="col-md-3">
                <input id="duedate" type="text" class="form-control" name="duedate" value="{{$data->getSOMaster->so_due_date}}" autocomplete="off" maxlength="24" autofocus disabled>
            </div>
            <label for="type" class="col-md-3 col-form-label text-md-right">Type</label>
            <div class="col-md-3">
                <input id="type" type="text" class="form-control" name="type" value="{{$data->getSOMaster->getCOMaster->co_type}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="mobileonly">
            <div class="form-group ml-4">
                <label class="col-form-label text-md-right"><h5>Detail SO</h5></label>
            </div>
        </div>
        <div class="form-group row col-md-12">
            @include('transaksi.trip.lapor.edit-table')
        </div>
            <label for="duedate" class="col-md-2 col-form-label text-md-right">Pencatatan Trip</label>
        <div class="form-group row col-md-12">
            @if(Auth()->user()->role_id == 1)
            @include('transaksi.trip.lapor.edit-catatan-admin-trip-table')
            @else
            @include('transaksi.trip.lapor.edit-catatan-trip-table')
            @endif
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                <div class="float-right">
                    <a href="{{Route('laportrip.index',['truck' => $data->sj_truck_id])}}" id="btnback" class="btn btn-success bt-action">Back</a>
                    
                    <button type="submit" class="btn btn-success bt-action btn-focus btnconf" id="btnconf">Lapor Trip</button>
                    
                    <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                        <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                    </button>
                </div>
            </div>
        </div>
    </div>

</form>
@endsection


@section('scripts')
<script>
    $("#duedate").datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: '+0d',
        onClose: function() {
            $("#addrow").focus();
        }
    });

    $(document).on('submit', '#submit', function(e) {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnback').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });

    $(document).on('click', '.btnconf', function(e){
        e.preventDefault();
        Swal.fire({
            title: "Lapor Trip ?",
            text: "Data akan disimpan dan tidak bisa diulang",
            type: "warning",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Lapor",
            closeOnConfirm: false
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $('#submit').submit();
            }
        })
    });
</script>
@endsection