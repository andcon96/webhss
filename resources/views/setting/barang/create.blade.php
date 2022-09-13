@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Barang Create</li>
</ol>
@endsection

@section('content')
<form action="{{ route('barangmt.store') }}" id="submit" method="POST">
    @csrf
    @method('POST')
    <div class="row offset-md-1 col-md-10">
        <div class="form-group row col-md-12">
            <label for="barang" class="col-md-2 col-form-label text-md-right">Barang</label>
            <div class="col-md-3">
               <input type="text" class="form-control" value="" name="barang">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10">
                <div class="float-right">
                    <a href="{{route('barangmt.index')}}" id="btnback" class="btn btn-success bt-action">Back</a>
                    <button type="submit" class="btn btn-success bt-action btn-focus" id="btnconf">Save</button>
                    <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                        <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection