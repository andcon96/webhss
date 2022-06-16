@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">History Check In Out</li>
</ol>
@endsection

@section('content')
    <div class="row">
        <input type="hidden" name="idmaster" value="{{$data->id}}">
        <div class="form-group row col-md-12">
            <label for="sonbr" class="col-md-2 col-form-label text-md-right">No Polis</label>
            <div class="col-md-3">
                <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{$data->truck_no_polis}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="mobileonly">
            <div class="form-group ml-4">
                <label class="col-form-label text-md-right"><h4>Detail SO</h4></label>
            </div>
        </div>
        <div class="form-group row col-md-12">
            @include('transaksi.checkinout.show-table')
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                <div class="float-right">
                    <a href="{{route('checkinout.index')}}" id="btnback" class="btn btn-success bt-action">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
