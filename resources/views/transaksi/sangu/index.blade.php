@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Alokasi Sangu</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->
<form action="{{route('sosangu.index')}}" method="get">

    <div class="form-group row col-md-12">
        <label for="s_sonumber" class="col-md-2 col-form-label text-md-right">{{ __('SO Number.') }}</label>
        <div class="col-md-4 col-lg-3">
            <input id="s_sonumber" type="text" class="form-control" name="s_sonumber" value="{{ request()->input('s_sonumber') }}" autofocus autocomplete="off">
        </div>
        <label for="s_customer" class="col-md-2 col-form-label text-md-right">{{ __('Customer') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="s_customer" class="form-control" name="s_customer" autofocus autocomplete="off">
                <option value=""> Select Data </option>
                @foreach($cust as $custs)
                <option value="{{$custs->cust_code}}">{{$custs->cust_code}} - {{$custs->cust_desc}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row col-md-12">
        <label for="s_shipfrom" class="col-md-2 col-form-label text-md-right">{{ __('Ship From') }}</label>
        <div class="col-md-4 col-lg-3">
            <input id="s_shipfrom" type="text" class="form-control" name="s_shipfrom" autofocus autocomplete="off">
        </div>

        <label for="s_shipto" class="col-md-2 col-form-label text-md-right">{{ __('Ship To') }}</label>
        <div class="col-md-4 col-lg-3">
            <input id="s_shipto" type="text" class="form-control" name="s_shipto" autofocus autocomplete="off">
        </div>
    </div>

    <div class="form-group row col-md-12">
        <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('Status') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="s_status" class="form-control" name="s_status" autofocus autocomplete="off">
                <option value=""> --Select Status-- </option>
                <option value="New">New</option>
                <option value="Open">Open</option>
                <option value="Closed">Closed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>
        <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('') }}</label>
        <div class="col-md-4 col-lg-3">
            <button class="btn bt-action newUser" id="btnsearch" value="Search">Search</button>
            <button class="btn bt-action newUser" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i class="fa fa-sync"></i></button>
        </div>
    </div>
</form>

@include('transaksi.sangu.index-table')

@endsection


@section('scripts')

<script type="text/javascript">
    $('#s_customer, #s_status').select2({
        width: '100%',
    });
    
    function resetSearch(){
        $('#s_customer').val('');
        $('#s_shipfrom').val('');
        $('#s_shipto').val('');
        $('#s_status').val('');
        $('#s_sonumber').val('');
    }

    $(document).ready(function(){
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let customer = queryString.get('s_customer');
        let shipfrom = queryString.get('s_shipfrom');
        let shipto   = queryString.get('s_shipto');
        let status = queryString.get('s_status');

        $('#s_customer').val(customer).trigger('change');
        $('#s_status').val(status).trigger('change');
    });

    $(document).on('click', '#btnrefresh', function(){
        resetSearch();
    });
</script>
@endsection