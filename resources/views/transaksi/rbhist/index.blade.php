@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Report Biaya Maintenance</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->
<div class="col-md-12 mb-3">
    <a href="{{route('rbhist.create') }}" class="btn btn-info bt-action">Create Report</a>
</div>
<form action="{{route('rbhist.index')}}" method="get">

    <div class="form-group row col-md-12">
        <label for="truck" class="col-md-2 col-form-label text-md-right">{{ __('Truck') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="truck" class="form-control" name="truck" autofocus autocomplete="off">
                <option value=""> Select Data </option>
                @foreach($truck as $trucks)
                <option value="{{$trucks->id}}">{{$trucks->truck_no_polis}}</option>
                @endforeach
            </select>
        </div>
        <label for="datefrom" class="col-md-2 col-form-label text-md-right">{{ __('Date From') }}</label>
        <div class="col-md-4 col-lg-3">
            <input id="datefrom" type="text" class="form-control" name="datefrom" autocomplete="off" value="">
        </div>
    </div>
    <div class="form-group row col-md-12">
        <label for="dateto" class="col-md-2 col-form-label text-md-right">{{ __('Date To') }}</label>
        <div class="col-md-4 col-lg-3">
            <input id="dateto" type="text" class="form-control" name="dateto" autocomplete="off" value="">
        </div>
        <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('') }}</label>
        <div class="col-md-4 col-lg-3">
            <button class="btn bt-action newUser" id="btnsearch" value="Search">Search</button>
            <button class="btn bt-action newUser" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i class="fa fa-sync"></i></button>
        </div>
    </div>

</form>

@include('transaksi.rbhist.index-table')

@endsection


@section('scripts')

<script type="text/javascript">
    $('#truck').select2({
        width: '100%',
    });
    
    $("#datefrom, #dateto").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    
    function resetSearch(){
        $('#datefrom').val('');
        $('#dateto').val('');
        $('#truck').val('').trigger('change');
    }

    $(document).on('click', '#btnrefresh', function(){
        resetSearch();
    });

    $(document).ready(function(){
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let truck = queryString.get('truck');
        let datefrom = queryString.get('datefrom');
        let dateto = queryString.get('dateto');

        $('#truck').val(truck).trigger('change');
        $('#datefrom').val(datefrom);
        $('#dateto').val(dateto);
    });


    $(document).on('click', '#btnconf', function(e){
        e.preventDefault();
        Swal.fire({
            title: "Cancel Laporan ?",
            text: "Data akan dicancel dan tidak bisa diulang",
            type: "warning",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Confirm",
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