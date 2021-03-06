@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Lapor Kerusakan - Assign Kerusakan</li>
</ol>
@endsection

@section('content')
<form action="{{ route('UpAssignKR',$data->id) }}" id="submit" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <input type="hidden" name="idmaster" value="{{$data->id}}">
        <div class="form-group row col-md-12">
            <label for="sonbr" class="col-md-2 col-form-label text-md-right">Kerusakan Nbr</label>
            <div class="col-md-3">
                <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{$data->kerusakan_nbr}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="truck" class="col-md-2 col-form-label text-md-right">Truck</label>
            <div class="col-md-3">
                <input id="truck" type="text" class="form-control" name="truck" value="{{$data->getTruckDriver->getTruck->truck_no_polis}}" autocomplete="off" maxlength="24" readonly>
            </div>
            <label for="driver" class="col-md-3 col-form-label text-md-right">Driver</label>
            <div class="col-md-3">
                <input id="driver" type="text" class="form-control" name="driver" value="{{$data->getTruckDriver->getUser->name}}" autocomplete="off" maxlength="24" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            @include('transaksi.kerusakan.show-table')
        </div>
        
        <div class="form-group row col-md-12">
            @include('transaksi.kerusakan.show-table-struktur')
        </div>
        

        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                <div class="float-right">
                    <a href="{{route('laporkerusakan.index')}}" id="btnback" class="btn btn-success bt-action">Back</a>
                    <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                        <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                    </button>
                </div>
            </div>
        </div>
    </div>

</form>
@endsection
