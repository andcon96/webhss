@extends('layout.layout')

@section('menu_name', 'Cicilan Maintenance')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Transaksi</a></li>
        <li class="breadcrumb-item active">Cicilan Maintenance - Edit</li>
    </ol>
@endsection

@section('content')
    <form action="{{ route('cicilanmt.update', $cicilan->id) }}" id="submit" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <input type="hidden" name="idmaster" value="{{ $cicilan->id }}">
            <div class="form-group row col-md-12">
                <label for="truck" class="col-md-2 col-form-label text-md-right">Truck</label>
                <div class="col-md-3">
                    <input id="truck" type="text" class="form-control" name="truck"
                        value="{{ $cicilan->getDriverNopol->getTruck->truck_no_polis ?? '' }}" readonly>
                </div>
                <label for="driver" class="col-md-3 col-form-label text-md-right">Driver</label>
                <div class="col-md-3">
                    <input id="driver" type="text" class="form-control" name="driver"
                        value="{{ $cicilan->getDriverNopol->getDriver->driver_name }}" readonly>
                </div>
            </div>
            <div class="form-group row col-md-12">
               <label for="totpaid" class="col-md-2 col-form-label text-md-right">Total Paid</label>
               <div class="col-md-3">
                   <input id="totpaid" type="text" class="form-control nominal" name="totpaid"
                       value="{{ number_format($totalbayar, 3) }}" autocomplete="off" readonly>
               </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="effdate" class="col-md-2 col-form-label text-md-right">Eff Date</label>
                <div class="col-md-3">
                    <input id="effdate" type="text" class="form-control" name="effdate" style="background-color: white"
                        value="{{ $cicilan->cicilan_eff_date }}" autocomplete="off" maxlength="24" readonly>
                </div>
                <label for="nominal" class="col-md-3 col-form-label text-md-right">Nominal</label>
                <div class="col-md-3">
                    <input id="nominal" type="text" class="form-control nominal" name="nominal"
                        value="{{ number_format($cicilan->cicilan_nominal, 3) }}" autocomplete="off" maxlength="24">
                </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="remark" class="col-md-2 col-form-label text-md-right">Remark</label>
                <div class="col-md-9">
                    <input id="remark" type="text" class="form-control" name="remark"
                        value="{{ $cicilan->cicilan_remarks }}" autocomplete="off" maxlength="50">
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
        $('.selectdrop').select2({
            width: '100%'
        });
        $("#effdate").datepicker({
            dateFormat: 'yy-mm-dd',
            onClose: function() {
                $("#addrow").focus();
            }
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
