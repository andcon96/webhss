@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Sales Order Maintenance</li>
</ol>
@endsection

@section('content')
<form action="{{ route('coUpdateSO') }}" id="submit" method="POST">
    @csrf
    @method('POST')
    <div class="row">
        <div class="form-group row col-md-12">
            <label for="conbr" class="col-md-2 col-form-label text-md-right">CO Number</label>
            <div class="col-md-3">
                <input type="hidden" name="idcomstr" value="{{$data->id}}">
                <input id="conbr" type="text" class="form-control" name="conbr" value="{{$data->co_nbr}}" autocomplete="off" maxlength="24" required readonly autofocus>
            </div>
            <label for="customer" class="col-md-3 col-form-label text-md-right">Customer</label>
            <div class="col-md-3">
                <input id="customer" type="text" class="form-control" name="customer" value="{{$data->co_cust_code}} -- {{$data->getCustomer->cust_desc}}" autocomplete="off" maxlength="24" required readonly autofocus>
                <input type="hidden" value="{{$data->co_cust_code}}" name="custcode" id="custcode">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="type" class="col-md-2 col-form-label text-md-right">Type</label>
            <div class="col-md-3">
                <input id="type" type="text" class="form-control" name="type" value="{{$data->co_type}}" autocomplete="off" maxlength="24" readonly required autofocus>
            </div>
            <label for="barang" class="col-md-3 col-form-label text-md-right">Barang</label>
            <div class="col-md-3">
                <input id="barang" type="text" class="form-control" name="barang" value="{{$data->getBarang->barang_deskripsi ?? ''}}" autocomplete="off" readonly autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="shipfrom" class="col-md-2 col-form-label text-md-right">Ship From</label>
            <div class="col-md-3">
                <select name="shipfrom" id="shipfrom" class="form-control" required > <!-- Update 230523 -->
                        <option value="">None</option>
                    @foreach($shipfrom as $shipfroms)
                        <option value="{{$shipfroms->sf_code}}">{{$shipfroms->sf_code}} -- {{$shipfroms->sf_desc}}</option>
                    @endforeach
                </select>
            </div>
            <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
            <div class="col-md-3">
                <select name="shipto" id="shipto" class="form-control" required>
                    @foreach($shipto as $shiptos)
                        <option value="{{$shiptos->cs_shipto}}">{{$shiptos->cs_shipto}} -- {{$shiptos->cs_shipto_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="duedate" class="col-md-2 col-form-label text-md-right">Due Date</label>
            <div class="col-md-3">
                <input id="duedate" type="text" class="form-control" name="duedate" value="{{\Carbon\Carbon::now()->addDays(1)->toDateString()}}" autocomplete="off" maxlength="24" required autofocus>
            </div>
            <label for="poaju" class="col-md-3 col-form-label text-md-right">PO/AJU</label>
            <div class="col-md-3">
                <input id="poaju" type="text" class="form-control" name="poaju" value="" autocomplete="off" maxlength="50" autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="remark" class="col-md-2 col-form-label text-md-right">Remark</label>
            <div class="col-md-9">
                <input id="remark" type="text" class="form-control" name="remark" value="" autocomplete="off" maxlength="50" autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12">
            @include('transaksi.customerorder.salesorder.create-table')
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                <div class="float-right">
                    <a href="{{route('customerorder.index')}}" id="btnback" class="btn btn-success bt-action">Back</a>
                    <button type="submit" class="btn btn-success bt-action btn-focus" id="btnconf">Save</button>
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
    $('#shipto,#shipfrom').select2({
        width: '100%'
    });
    
    $("#duedate").datepicker({
        dateFormat: 'yy-mm-dd',
        onClose: function() {
            $("#addrow").focus();
        }
    });

    function selectRefresh() {
        $('.selectpicker').selectpicker().focus();
    }

    $(document).on('submit', '#submit', function(e) {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnback').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });
</script>
@endsection