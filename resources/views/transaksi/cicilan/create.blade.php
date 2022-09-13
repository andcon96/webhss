@extends('layout.layout')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Transaksi</a></li>
        <li class="breadcrumb-item active">Cicilan Maintenance</li>
    </ol>
@endsection

@section('content')
    <form action="{{ route('cicilanmt.store') }}" id="submit" method="POST">
        @csrf
        @method('POST')
        <div class="row">
            <div class="form-group row col-md-12">
                <label for="drivernopol" class="col-md-2 col-form-label text-md-right">Truck & Driver</label>
                <div class="col-md-3">
                    <select name="drivernopol" id="drivernopol" class="form-control" required>
                        <option value="">Select Data</option>
                        @foreach ($drivernopol as $drivernopols)
                            <option value="{{ $drivernopols->id }}">{{ $drivernopols->getTruck->truck_no_polis ?? '' }} --
                                {{ $drivernopols->getDriver->driver_name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <label for="effdate" class="col-md-3 col-form-label text-md-right">Eff Date</label>
                <div class="col-md-3">
                    <input type="text" name="effdate" id="effdate" class="form-control" autocomplete="off">
                </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="nominal" class="col-md-2 col-form-label text-md-right" autocomplete="off">Nominal</label>
                <div class="col-md-3">
                    <input type="text" name="nominal" class="form-control nominal" value="0">
                </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="remark" class="col-md-2 col-form-label text-md-right">Remark</label>
                <div class="col-md-9">
                    <input type="text" name="remark" class="form-control" autocomplete="off">
                </div>
            </div>

            <div class="form-group row col-md-12">
                <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                    <div class="float-right">
                        <a href="{{ route('cicilanmt.index') }}" id="btnback" class="btn btn-success bt-action">Back</a>
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
        $('#drivernopol').select2({
            width: '100%'
        });

        $("#effdate").datepicker({
            dateFormat: 'yy-mm-dd'
        }).datepicker("setDate","0");

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
