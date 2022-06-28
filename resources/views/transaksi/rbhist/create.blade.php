@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Report Biaya Maintenance</li>
</ol>
@endsection

@section('content')
<form action="{{ route('rbhist.store') }}" id="submit" method="POST">
    @csrf
    @method('POST')
    <div class="row">
        <div class="form-group row col-md-12">
            <label for="truck" class="col-md-2 col-form-label text-md-right">Truck</label>
            <div class="col-md-3">
                <select name="truck" id="truck" class="form-control" required>
                    <option value="">Select Data</option>
                    @foreach($truck as $trucks)
                    <option value="{{$trucks->id}}">{{$trucks->truck_no_polis}}</option>
                    @endforeach
                </select>
            </div>
            <label for="effdate" class="col-md-3 col-form-label text-md-right">Eff Date</label>
            <div class="col-md-3">
                <input type="text" name="effdate" id="effdate" class="form-control" value="{{\Carbon\Carbon::now()->toDateString()}}">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="nominal" class="col-md-2 col-form-label text-md-right">Nominal</label>
            <div class="col-md-3">
                <input type="text" name="nominal" class="form-control nominal" min="0">
            </div>
            <label for="tipe" class="col-md-3 col-form-label text-md-right">Tipe</label>
            <div class="col-md-3">
                <select name="tipe" id="tipe" class="form-control" required>
                    <option value="">Select Data</option>
                    <option value="1">Pemasukan</option>
                    <option value="0">Pengeluaran</option>
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="remark" class="col-md-2 col-form-label text-md-right">Remark</label>
            <div class="col-md-9">
                <input type="text" name="remark" class="form-control">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                <div class="float-right">
                    <a href="{{route('rbhist.index')}}" id="btnback" class="btn btn-success bt-action">Back</a>
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
    $('#truck, #tipe').select2({
        width: '100%'
    });
    
    $("#effdate").datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $(document).on('keyup', '.nominal', function() {
        var data = $(this).val();

        var newdata = data.replace(/([^ 0-9])/g, '');

        $(this).val(Number(newdata).toLocaleString('en-US'));
    });

    $(document).on('submit', '#submit', function(e) {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnback').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });
</script>
@endsection