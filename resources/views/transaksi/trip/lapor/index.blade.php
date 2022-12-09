@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Pelaporan Trip</li>
</ol>
@endsection


@section('content')

<form action="" method="GET" class="col-md-12">
    <div class="form-group row">
        <label for="polis" class="col-form-label text-md-right mt-2" style="margin-left:25px">{{ __('Truck') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12 mt-2">
            @if(!$userDriver)
            {{-- <select name="truck" id="truck" class="form-control truck">
                <option value="">Select Data</option>
                    @foreach($truck as $trucks)
                    <option value="{{$trucks->id}}">{{$trucks->truck_no_polis}}</option>
                    @endforeach
            </select> --}}
            <select name="nomorso" id="nomorso" class="form-control nomorso">
                <option value="">Select Data</option>
                    @foreach($nomorso as $sonbr)
                    <option value="{{$nomorso->getSOMaster->id}}">{{$nomorso->getSOMaster->sonbr}}</option>
                    @endforeach
            </select>
            @else
            <input type="text" class="form-control" value="{{$userDriver->truck_no_polis}}" readonly>
            <input type="hidden" name="truck" value="{{$userDriver->id}}">
            @endif
        </div>
        <div class="col-xs-12 mt-2" id='btn'>
            @if(!$userDriver)
            <input type="submit" class="btn bt-ref" id="btnsearch" value="Search" style="margin-left:15px;" />
            @endif
        </div>
    </div>
</form>

@if(!$userDriver)
    @include('transaksi.trip.lapor.index-table')
@else
    @include('transaksi.trip.lapor.index-table-driver')
@endif

@endsection


@section('scripts')
<script>
    $('.truck').select2({
        width: '100%',
    });

    $(document).ready(function() {
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let truck = queryString.get('truck');

        $('#truck').val(truck).trigger('change');
    });
</script>
@endsection