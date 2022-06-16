@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Sales Order Browse</li>
</ol>
@endsection

@section('content')
<form action="{{route('sanguaudit.index')}}" method="get">
    <div class="form-group row col-md-12">
        <label for="idsonbr" class="col-md-2 col-form-label text-md-right">{{ __('SO Number.') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="idsonbr" class="form-control" name="idsonbr" autofocus autocomplete="off">
                <option value=""> Select Data </option>
                @foreach($listso as $listsos)
                <option value="{{$listsos->id}}">{{$listsos->so_nbr}}</option>
                @endforeach
            </select>
        </div>
        <label for="custcode" class="col-md-2 col-form-label text-md-right">{{ __('Customer') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="custcode" class="form-control" name="custcode" autofocus autocomplete="off">
                <option value=""> Select Data </option>
                @foreach($listcust as $custs)
                <option value="{{$custs->cust_code}}">{{$custs->cust_code}} - {{$custs->cust_desc}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row col-md-12">
        <label for="idtruckdriver" class="col-md-2 col-form-label text-md-right">{{ __('Truck Driver') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="idtruckdriver" class="form-control" name="idtruckdriver" autofocus autocomplete="off">
                <option value=""> Select Data </option>
                @foreach($truckdriver as $truckdrivers)
                <option value="{{$truckdrivers->id}}">{{$truckdrivers->getTruck->truck_no_polis}}</option>
                @endforeach
            </select>
        </div>
        <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('') }}</label>
        <div class="col-md-4 col-lg-3">
            <button class="btn bt-action newUser" id="btnsearch" value="Search">Search</button>
            <button class="btn bt-action newUser" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i class="fa fa-sync"></i></button>
        </div>
    </div>
</form>


@include('transaksi.sangu.audittrail.index-table')

@endsection


@section('scripts')

<script type="text/javascript">
    $('#idsonbr, #custcode, #idtruckdriver').select2({
        width: '100%',
    });
    
    function resetSearch(){
        $('#idsonbr').val('');
        $('#custcode').val('');
        $('#idtruckdriver').val('');
    }

    $(document).ready(function(){
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let idsonbr = queryString.get('idsonbr');
        let customer = queryString.get('custcode');
        let idtruckdriver = queryString.get('idtruckdriver');

        $('#idsonbr').val(idsonbr).trigger('change');
        $('#custcode').val(customer).trigger('change');
        $('#idtruckdriver').val(idtruckdriver).trigger('change');
    });
    
    $(document).on('click', '#btnrefresh', function(){
        resetSearch();
    });


</script>
@endsection