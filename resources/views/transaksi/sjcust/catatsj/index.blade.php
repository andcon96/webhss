@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Pelaporan Trip</li>
</ol>
@endsection

@section('content')
<form action="{{ route('updateCatatSJ') }}" method="POST" id="submit">
    @method('POST')
    @csrf
    <div class="row">
        <div class="form-group row col-md-12">
            <label for="sonbr" class="col-md-2 col-form-label text-md-right">Nomor SO</label>
            <div class="col-md-3">
                <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{$data->getSOMaster->so_nbr}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="sjnbr" class="col-md-3 col-form-label text-md-right">Nomor SPK</label>
            <div class="col-md-3">
                <input id="sjnbr" type="text" class="form-control" name="sjnbr" value="{{$data->sj_nbr}}" autocomplete="off" maxlength="24" autofocus readonly>
                <input type="hidden" name="sjid" value="{{$data->id}}">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="duedate" class="col-md-2 col-form-label text-md-right">Due Date</label>
            <div class="col-md-3">
                <input id="duedate" type="text" class="form-control" name="duedate" value="{{$data->getSOMaster->so_due_date}}" autocomplete="off" maxlength="24" autofocus disabled>
            </div>
            <label for="customer" class="col-md-3 col-form-label text-md-right">Customer</label>
            <div class="col-md-3">
                <input id="customer" type="text" class="form-control" name="customer" value="{{$data->getSOMaster->getCOMaster->co_cust_code}} -- {{$data->getSOMaster->getCOMaster->getCustomer->cust_desc}}" autocomplete="off" maxlength="24" autofocus readonly>
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
            <label for="type" class="col-md-2 col-form-label text-md-right">Type</label>
            <div class="col-md-3">
                <input id="type" type="text" class="form-control" name="type" value="{{$data->getSOMaster->getCOMaster->co_type}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <label for="duedate" class="col-md-4 col-form-label text-md-right">History & Pelaporan SJ</label>
        <div class="form-group row col-md-12">
            @include('transaksi.sjcust.catatsj.index-table')
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                <div class="float-right">
                    {{-- <a href="{{ route('laporsj.index',['truck' => $truck]) }}" id="btnback" class="btn btn-success bt-action">Back</a> --}}
                    <a href="{{ url()->previous() }}" id="btnback" class="btn btn-success bt-action">Back</a>

                    {{-- @if(Auth()->user()->id == $data->getTruck->getUserDriver->id) --}}
                    <button type="submit" class="btn btn-success bt-action btn-focus btnconf" id="btnconf">Lapor SJ</button>
                    {{-- @endif --}}
                    <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                        <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection