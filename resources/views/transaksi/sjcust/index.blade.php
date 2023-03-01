@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Lapor Surat Jalan</li>
</ol>
@endsection


@section('content')

<form action="" method="GET">
    <div class="form-group row">
        <label for="sonumber" class="col-md-2 col-form-label text-md-right mt-1" style="margin-left:25px">{{ __('SO Number') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12 mt-2">
            <select name="sonumber" id="sonumber" class="form-control selectdrop">
                <option value="">Select Data</option>
                @foreach($so as $sos)
                <option value="{{$sos->id}}">{{$sos->so_nbr}}</option>
                @endforeach
            </select>
        </div>
        <label for="sjnbr" class="col-md-2 col-form-label text-md-right mt-1" style="margin-left:25px">{{ __('SJ Number') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12 mt-2">
            <select name="sjnbr" id="sjnbr" class="form-control selectdrop">
                <option value="">Select Data</option>
                @foreach($sj as $sjs)
                <option value="{{$sjs->id}}">{{$sjs->sj_nbr}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="shipfrom" class="col-md-2 col-form-label text-md-right mt-1" style="margin-left:25px">{{ __('Ship From') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12 mt-2">
            <select name="shipfrom" id="shipfrom" class="form-control selectdrop">
                <option value="">Select Data</option>
                @foreach($shipfrom as $shipfroms)
                <option value="{{$shipfroms->sf_code}}">{{$shipfroms->sf_code}} -- {{$shipfroms->sf_desc}}</option>
                @endforeach
            </select>
        </div>
        <label for="shipto" class="col-md-2 col-form-label text-md-right mt-1" style="margin-left:25px">{{ __('Ship To') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12 mt-2">
            <select name="shipto" id="shipto" class="form-control selectdrop">
                <option value="">Select Data</option>
                @foreach($shipto as $shiptos)
                <option value="{{$shiptos->cs_shipto}}">{{$shiptos->cs_shipto}} -- {{$shiptos->cs_shipto_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="polis" class="col-md-2 col-form-label text-md-right mt-1" style="margin-left:25px">{{ __('Truck') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12 mt-2">
            <select name="truck" id="truck" class="form-control">
                <option value="">Select Data</option>
                @foreach($truck as $trucks)
                <option value="{{$trucks->id}}">{{$trucks->truck_no_polis}}</option>
                @endforeach
            </select>
        </div>
        <label for="customer" class="col-md-2 col-form-label text-md-right mt-1" style="margin-left:25px">{{ __('Customer') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12 mt-2">
            <select name="customer" id="customer" class="form-control selectdrop">
                <option value="">Select Data</option>
                @foreach($customer as $customers)
                <option value="{{$customers->cust_code}}">{{$customers->cust_code}} -- {{$customers->cust_desc}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="status" class="col-md-2 col-form-label text-md-right mt-1" style="margin-left:25px">{{ __('Status') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12 mt-2">
            <select name="status" id="status" class="form-control selectdrop">
                <option value="">Select Data</option>
                <option value="Open">Open</option>
                <option value="Selesai">Selesai</option>
                <option value="Closed">Closed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>
        <label for="kapal" class="col-md-2 col-form-label text-md-right mt-1" style="margin-left:25px">{{ __('Kapal') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12 mt-2">
            <input type="text" class="form-control" name="kapal" id="kapal">
        </div>
    </div>
    <div class="form-group row">
        <label for="shipto" class="col-md-2 col-form-label text-md-right" style="margin-left:25px">{{ __('') }}</label>
        <div class="col-xs-12" id='btn'>
            <input type="submit" class="btn bt-action" id="btnsearch" value="Search" style="margin-left:15px;" />
            <button class="btn bt-action" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i class="fa fa-sync"></i></button>
        </div>
    </div>
</form>

@include('transaksi.sjcust.index-table')

@endsection


@section('scripts')
<script>
    $('#truck,.selectdrop').select2({
        width: '100%',
    });

    $(document).ready(function() {
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let truck = queryString.get('truck');
        let customer = queryString.get('customer');
        let shipto = queryString.get('shipto');
        let shipfrom = queryString.get('shipfrom');
        let status = queryString.get('status');
        let kapal = queryString.get('kapal');
        let sonumber = queryString.get('sonumber');
        let sjnbr = queryString.get('sjnbr');

        $('#truck').val(truck).trigger('change');
        $('#customer').val(customer).trigger('change');
        $('#shipto').val(shipto).trigger('change');
        $('#shipfrom').val(shipfrom).trigger('change');
        $('#status').val(status).trigger('change');
        $('#kapal').val(kapal);
        $('#sonumber').val(sonumber).trigger('change');
        $('#sjnbr').val(sjnbr).trigger('change');
    });

    $('#btnrefresh').on('click',function(){
        $('#truck').val('');
        $('#customer').val('');
        $('#shipto').val('');
        $('#shipfrom').val('');
        $('#status').val('');
        $('#kapal').val('');
        $('#sonumber').val('');
        $('#sjnbr').val('');
    });
</script>
@endsection