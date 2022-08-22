@extends('layout.layout')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Transaksi</a></li>
        <li class="breadcrumb-item active">Edit Report Biaya Maintenance</li>
    </ol>
@endsection

@section('content')
    <form action="{{ route('rbhist.update', $rbhist->id) }}" id="submit" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="form-group row col-md-12">
                <label for="truck" class="col-md-2 col-form-label text-md-right">Truck</label>
                <div class="col-md-3">
                    <input type="text" name="truck" id="truck" class="form-control"
                        value="{{ $rbhist->getTruck->truck_no_polis }}" readonly>
                </div>
                <label for="effdate" class="col-md-3 col-form-label text-md-right">Eff Date</label>
                <div class="col-md-3">
                    <input type="text" name="effdate" id="effdate" class="form-control"
                        value="{{ $rbhist->rb_eff_date }}">
                </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="tipe" class="col-md-2 col-form-label text-md-right">Tipe</label>
                <div class="col-md-3">
                    <select name="tipe" id="tipe" class="form-control" required>
                        <option value="">Select Data</option>
                        <option value="1" {{ $rbhist->rb_is_pemasukan == 1 ? 'Selected' : '' }}>Pemasukan</option>
                        <option value="0" {{ $rbhist->rb_is_pemasukan == 0 ? 'Selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="remark" class="col-md-2 col-form-label text-md-right">Remark</label>
                <div class="col-md-9">
                    <input type="text" name="remark" value="{{ $rbhist->rb_remark }}" class="form-control">
                </div>
            </div>
            <div class="form-group row offset-md-1 col-md-10">
                @include('transaksi.rbhist.edit-table')
            </div>
            <div class="form-group row col-md-12">
                <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                    <div class="float-right">
                        <a href="{{ route('rbhist.index') }}" id="btnback" class="btn btn-success bt-action">Back</a>
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
        $('#tipe').select2({
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

        var counter = 1;
        $(document).on('click', '#addrow', function() {
            var rowCount = $('#bodyrbhist tr').length;

            var currow = rowCount - 2;
            var lastline = parseInt($('#dataTable tr:eq(' + currow + ') td:eq(0) input[type="number"]').val()) + 1;

            if (lastline !== lastline) {
                // check apa NaN
                lastline = 1;
            }

            var newRow = $("<tr>");
            var cols = "";
            cols +=
                '<td data-title="Line" data-label="Line"><input type="text" class="form-control line" autocomplete="off" name="deskripsi[]" style="height:37px" value=""/></td>';
            cols += '<td><input type="hidden" name="iddeskripsi[]" value=""><input type="text" name="nominal[]" class="form-control nominal" min="0" value="0"></td>'
            cols +=
                '<td data-title="Action"><input type="button" class="ibtnDel btn btn-danger btn-focus"  value="Delete"></td>';
            cols += '</tr>'
            newRow.append(cols);
            $("#dataTable").append(newRow);
            counter++;
        });

        $("table#dataTable").on("click", ".ibtnDel", function(event) {
            var row = $(this).closest("tr");
            $(this).closest("tr").remove();
        });
    </script>
@endsection
