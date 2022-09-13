@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Barang - Edit {{$barang->barang_deskripsi}}</li>
</ol>
@endsection

@section('content')
<form action="{{ route('barangmt.update',$barang->id) }}" id="submit" method="POST">
    @csrf
    @method('PUT')
    <div class="row offset-md-1 col-md-10">
        <input type="hidden" name="idmaster" value="{{$barang->id}}">
        <div class="form-group row col-md-12">
            <label for="deskripsi" class="col-md-2 col-form-label text-md-right">Barang</label>
            <div class="col-md-3">
                <input id="deskripsi" type="text" class="form-control" name="deskripsi" value="{{$barang->barang_deskripsi}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="active" class="col-md-2 col-form-label text-md-right">Active</label>
            <div class="col-md-3">
                <select name="active" id="active" class="form-control">
                    <option value="1" {{$barang->barang_is_active == 1 ? 'Selected' : ''}}>Yes</option>
                    <option value="0" {{$barang->barang_is_active == 0 ? 'Selected' : ''}}>No</option>
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10" style="margin-top:90px;">
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


@section('scripts')
<script>

    $(document).on('submit', '#submit', function(e) {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnback').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });
</script>
@endsection