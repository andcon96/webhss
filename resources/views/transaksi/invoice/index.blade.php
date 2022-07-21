@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Invoice Maintenance</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->
<div class="col-md-12 mb-3">
    <a href="{{route('invoicemt.create') }}" class="btn btn-info bt-action">Create Invoice</a>
</div>
<form action="{{route('invoicemt.index')}}" method="get">

    <div class="form-group row col-md-12">
        <label for="ivnbr" class="col-md-2 col-form-label text-md-right">{{ __('Invoice Number') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="ivnbr" class="form-control" name="ivnbr" autofocus autocomplete="off">
                <option value=""> Select Data </option>
                @foreach($list_invoice as $invoices)
                <option value="{{$invoices->id}}">{{$invoices->im_nbr}}</option>
                @endforeach
            </select>
        </div>
        <label for="sonbr" class="col-md-2 col-form-label text-md-right">{{ __('SO Number') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="sonbr" class="form-control" name="sonbr" autofocus autocomplete="off">
                <option value=""> Select Data </option>
                @foreach($list_sonbr as $sonbrs)
                <option value="{{$sonbrs->id}}">{{$sonbrs->so_nbr}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row col-md-12">
      <label for="datefrom" class="col-md-2 col-form-label text-md-right">{{ __('Date From') }}</label>
      <div class="col-md-4 col-lg-3">
          <input id="datefrom" type="text" class="form-control" name="datefrom" autocomplete="off" value="">
      </div>
        <label for="dateto" class="col-md-2 col-form-label text-md-right">{{ __('Date To') }}</label>
        <div class="col-md-4 col-lg-3">
            <input id="dateto" type="text" class="form-control" name="dateto" autocomplete="off" value="">
        </div>
    </div>
    <div class="form-group row col-md-12">
      <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('') }}</label>
      <div class="col-md-4 col-lg-3">
          <button class="btn bt-action newUser" id="btnsearch" value="Search">Search</button>
          <button class="btn bt-action newUser" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i class="fa fa-sync"></i></button>
      </div>
    </div>


</form>

@include('transaksi.invoice.index-table')

@endsection


@section('scripts')

<script type="text/javascript">
    $('#ivnbr, #sonbr').select2({
        width: '100%',
    });
    
    $("#datefrom, #dateto").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    
    function resetSearch(){
        $('#datefrom').val('');
        $('#dateto').val('');
        $('#sonbr').val('').trigger('change');
        $('#ivnbr').val('').trigger('change');
    }

    $(document).on('click', '#btnrefresh', function(){
        resetSearch();
    });

    $(document).ready(function(){
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let sonbr = queryString.get('sonbr');
        let ivnbr = queryString.get('ivnbr');
        let datefrom = queryString.get('datefrom');
        let dateto = queryString.get('dateto');

        $('#ivnbr').val(ivnbr).trigger('change');
        $('#sonbr').val(sonbr).trigger('change');
        $('#datefrom').val(datefrom);
        $('#dateto').val(dateto);
    });


    $(document).on('click', '#btnconf', function(e){
        e.preventDefault();
        Swal.fire({
            title: "Cancel Invoice ?",
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