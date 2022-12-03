@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Browse Trip By Truck</li>
</ol>
@endsection


@section('content')

<form action="" method="GET" class="col-md-12">
    <div class="form-group row">
        <label for="polis" class="col-form-label col-md-2 text-md-right mt-2" style="margin-left:25px">{{ __('Truck') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12 mt-2">
            @if($userDriver)
            <input type="text" class="form-control" value="{{$userDriver->truck_no_polis}}" readonly>
            <input type="hidden" name="truck" value="{{$userDriver->id}}">
            @else
            <select name="truck" id="truck" class="form-control">
                <option value="">Select Data</option>
                @foreach($truck as $trucks)
                <option value="{{$trucks->id}}">{{$trucks->truck_no_polis}}</option>
                @endforeach
            </select>
            @endif
        </div>
        @if(!$userDriver)
        <label for="status" class="col-form-label col-md-2 text-md-right mt-2" style="margin-left:25px">{{ __('Status') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12 mt-2">
            <select name="status" id="status" class="form-control">
                <option value="">Select Data</option>
                <option value="Open">Open</option>
                <option value="Selesai">Selesai</option>
                <option value="Closed">Closed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>
        <label for="spk" class="col-form-label col-md-2 text-md-right mt-2" style="margin-left:25px">{{ __('SPK') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12 mt-2">
            <input type="text" class="form-control" name="spk" id="spk">
        </div>
        @endif
        <label for="spk" class="col-form-label col-md-2 text-md-right mt-2" style="margin-left:25px">{{ __('') }}</label>
        <div class="col-xs-12 mt-2">
            @if(!$userDriver)
            <input type="submit" class="btn bt-action newUser" id="btnsearch" value="Search" style="margin-left:15px;" />
            <button class="btn bt-action newUser" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i class="fa fa-sync"></i></button>
            @endif
        </div>
    </div>
</form>

@include('transaksi.trip.index-table')

@endsection


@section('scripts')
<script>
    $('#truck,#status').select2({
        width: '100%',
    });
    
    function resetSearch(){
        $('#status').val('');
        $('#spk').val('');
        $('#truck').val('');
    }

    $(document).on('click', '#btnrefresh', function(){
        resetSearch();
    });

    $(document).ready(function() {
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let truck = queryString.get('truck');
        let status = queryString.get('status');
        let spk = queryString.get('spk');

        $('#truck').val(truck).trigger('change');
        $('#status').val(status).trigger('change');
        $('#spk').val(spk);
    });
</script>
@endsection