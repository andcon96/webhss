@extends('layout.layout')

@section('menu_name', 'Cicilan Maintenance')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Transaksi</a></li>
        <li class="breadcrumb-item active">Bayar Cicilan Maintenance - Edit</li>
    </ol>
@endsection

@section('content')
    <div class="row col-md-12">
      @include('transaksi.cicilan-bayar.edit-table-history')
    </div>

    <form action="{{ route('bayarcicilanmt.update', $cicilan->id) }}" id="submit" method="POST">
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
               <label for="totcil" class="col-md-2 col-form-label text-md-right">Total Cicilan</label>
               <div class="col-md-3">
                   <input id="totcil" type="text" class="form-control nominal" name="totcil"
                       value="{{number_format($cicilan->cicilan_nominal,0)}}" autocomplete="off" readonly>
               </div>
               <label for="totpaid" class="col-md-3 col-form-label text-md-right">Total Bayar</label>
               <div class="col-md-3">
                   <input id="totpaid" type="text" class="form-control nominal" name="totpaid"
                       value="{{number_format($totalbayar,0)}}" autocomplete="off" readonly>
               </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="effdate" class="col-md-2 col-form-label text-md-right">Tanggal Bayar</label>
                <div class="col-md-3">
                    <input id="effdate" type="text" class="form-control" name="effdate" style="background-color: white"
                        value="{{ \Carbon\Carbon::now()->toDateString() }}" autocomplete="off" maxlength="24" readonly>
                </div>
                <label for="nominal" class="col-md-3 col-form-label text-md-right">Nominal</label>
                <div class="col-md-3">
                    <input id="nominal" type="text" class="form-control nominal" name="nominal"
                        value="" autocomplete="off" maxlength="24">
                </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="remark" class="col-md-2 col-form-label text-md-right">Remark</label>
                <div class="col-md-9">
                    <input id="remark" type="text" class="form-control" name="remark"
                        value="" autocomplete="off" maxlength="50">
                </div>
            </div>

            <div class="form-group row col-md-12">
                <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                    <div class="float-right">
                        <a href="{{ route('bayarcicilanmt.index') }}" id="btnback" class="btn btn-success bt-action">Back</a>
                        <button type="submit" class="btn btn-success bt-action btn-focus" id="btnconf">Save</button>
                        <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                            <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>

    
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form action="{{route('updateHistoryCicilan')}}" method="post">
                    {{ method_field('post') }}
                    {{ csrf_field() }}

                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">Detail Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="detid" id="detid">
                        <div class="container">
                            <div class="row col-md-12">
                                <label for="dettruck" class="col-md-3 col-form-label text-md-right">Truck</label>
                                <div class="col-md-5">
                                    <input id="dettruck" type="text" class="form-control" name="dettruck" value=""
                                        autocomplete="off" maxlength="24" readonly required autofocus>
                                </div>
                            </div>
                            <div class="row col-md-12 mt-3">
                                <label for="detdriver" class="col-md-3 col-form-label text-md-right">Driver</label>
                                <div class="col-md-5">
                                    <input id="detdriver" type="text" class="form-control" name="detdriver" value=""
                                        autocomplete="off" maxlength="24" readonly required autofocus>
                                </div>
                            </div>
                            <div class="row col-md-12 mt-3">
                                <label for="deteffdate" class="col-md-3 col-form-label text-md-right">Tanggal Bayar</label>
                                <div class="col-md-5">
                                    <input id="deteffdate" type="text" class="form-control" name="deteffdate" value=""
                                        autocomplete="off" maxlength="24" autofocus>
                                </div>
                            </div>
                            <div class="row col-md-12 mt-3">
                                <label for="dettotpaid" class="col-md-3 col-form-label text-md-right">Total Bayar</label>
                                <div class="col-md-5">
                                    <input id="dettotpaid" type="text" class="form-control nominal" name="dettotpaid" value=""
                                        autocomplete="off" maxlength="24" autofocus>
                                </div>
                            </div>
                            <div class="row col-md-12 mt-3">
                                <label for="detremark" class="col-md-3 col-form-label text-md-right">Remarks</label>
                                <div class="col-md-5">
                                    <input id="detremark" type="text" class="form-control" name="detremark" value=""
                                        autocomplete="off" maxlength="24" autofocus>
                                </div>
                            </div>
                            <div class="row col-md-12 mt-3">
                                <label for="detisactive" class="col-md-3 col-form-label text-md-right">Active</label>
                                <div class="col-md-3">
                                    <select name="detisactive" id="detisactive" class="form-control selectdrop">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info bt-action" id="v_btnclose" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success bt-action" id="d_btnconf">Save</button>
                        <button type="button" class="btn bt-action" id="d_btnloading" style="display:none">
                            <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
        $('.selectdrop').select2({
            width: '100%'
        });
        $("#effdate,#deteffdate").datepicker({
            dateFormat: 'yy-mm-dd',
            onClose: function() {
                $("#addrow").focus();
            }
        });

        $(document).on('click', '.editmodal', function(){
            let id = $(this).data('id');
            let truck = $(this).data('truck');
            let driver = $(this).data('driver');
            let tglbayar = $(this).data('tglbayar');
            let nominal = $(this).data('nominal');
            let remarks = $(this).data('remarks');
            let isactive = $(this).data('isactive');

            $('#detid').val(id);
            $('#dettruck').val(truck);
            $('#detdriver').val(driver);
            $('#deteffdate').val(tglbayar);
            $('#dettotpaid').val(nominal);
            $('#detremark').val(remarks);
            $('#detisactive').val(isactive).trigger('change');
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
