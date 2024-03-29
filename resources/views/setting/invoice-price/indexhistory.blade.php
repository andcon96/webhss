@extends('layout.layout')

@section('menu_name', 'Invoice Price Maintenance')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Master</a></li>
        <li class="breadcrumb-item active">Invoice Price Maintenance</li>
    </ol>
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="col-md-12" style="display:block;max-height:30ch;overflow-y:scroll;white-space:nowrap">
        <h2>
            <center>History</center>
        </h2>
        <div class="table-responsive col-lg-12 col-md-12 mt-3">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Ship From</th>
                        <th>Ship To</th>
                        <th>Harga Tonase</th>
                        <th>Harga Rits</th>
                        <th>Tipe Truck</th>
                        <th>Active</th>
                        <th>Last Active</th>
                        <th>Deactivate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history_data as $index => $show)
                        <tr>
                            <td>
                                {{ $show->getIP->getCustomer->cust_desc ?? '' }}
                            </td>
                            <td>
                                {{ $show->getIP->getShipFrom->sf_desc ?? '' }}
                            </td>
                            <td>
                                {{ $show->getIP->getShipTo->cs_shipto_name ?? '' }}
                            </td>
                            <td>
                                {{ number_format($show->iph_tonase_price, 3) ?? '' }}
                            </td>
                            <td>
                                {{ number_format($show->iph_trip_price, 3) ?? '' }}
                            </td>
                            <td>
                                {{ $show->getTipeTruck->tt_desc ?? '' }}
                            </td>
                            <td>
                                {{ $show->iph_is_active == 1 ? 'Aktif' : 'Tidak Aktif' ?? '' }}
                            </td>
                            <td>
                                {{ $show->iph_last_active ?? '' }}
                            </td>
                            <td>
                                @if ($show->iph_is_active == 1)
                                    <input type="hidden" id="idhistory" value="{{ $show->id }}">
                                    <a href="javascript:void(0)" type="submit" id="btnsubmitchange" style="color:blue"><i
                                            class="fas fa-check" style="color=:white"></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <div class="col-md-12">
        <form class="form-horizontal" method="post" action="{{ route('invoiceprice.store') }}">
            {{ method_field('post') }}
            {{ csrf_field() }}

            <h2 class="mt-5">
                <center>Update Rute</center>
            </h2>
            <div class="col-12 form-group row mt-3">

                <div class="modal-body">
                    <div class="form-group row">
                        <label for="tipe" class="col-md-3 col-form-label text-md-right"
                            style="color: black">Customer</label>
                        <div class="col-md-5 {{ $errors->has('uname') ? 'has-error' : '' }}">
                            <input id="tipe" type="text" class="form-control"
                                value="{{ $rute->getCustomer->cust_code }} -- {{ $rute->getCustomer->cust_desc }}"
                                readonly>
                            <input type="hidden" name="idrute" value="{{ $rute->id }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="shipfrom" class="col-md-3 col-form-label text-md-right" style="color: black">Ship
                            From</label>
                        <div class="col-md-5">
                            <input id="shipfrom" type="text" class="form-control"
                                value="{{ $rute->getShipFrom->sf_desc }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="shipto" class="col-md-3 col-form-label text-md-right" style="color: black">Ship
                            To</label>
                        <div class="col-md-7">
                            <input id="shipto" type="text" class="form-control"
                                value="{{ $rute->getShipTo->cs_shipto_name }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tonaseprice" class="col-md-3 col-form-label text-md-right" style="color: black">Harga
                            Tonase</label>
                        <div class="col-md-7">
                            <input id="tonaseprice" type="number" class="form-control" name="tonaseprice" step=".001"
                                min="0" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ritsprice" class="col-md-3 col-form-label text-md-right" style="color: black">Harga
                            Rits</label>
                        <div class="col-md-7">
                            <input id="ritsprice" type="text" class="form-control" name="ritsprice" step=".001"
                                value="0" min="0" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tipetruck" class="col-md-3 col-form-label text-md-right" style="color: black">Tipe Truck</label>
                        <div class="col-md-2">
                            <select name="tipetruck" id="tipetruck" class="form-control">
                                    <option value="">None</option>
                                @foreach ($tipetruck as $tipetrucks)
                                    <option value="{{$tipetrucks->id}}">{{$tipetrucks->tt_desc}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="/invoiceprice/invoicepricedetail/{{ $id }}" class="btn btn-success bt-action"
                            id="btnback" color="white">Back</a>
                        <button type="submit" class="btn btn-success bt-action" id="btnconf">Save</button>
                        <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                            <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>



    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">Deactivate</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('changestatusinvoice') }}" id="formchange" class="form"
                    autocomplete="off">
                    @csrf

                    <div class="modal-body">
                        <input type="hidden" name="idhistory" id="temp_id" value="">

                        <div class="container">
                            <div class="row">
                                Apakah anda yakin mau menonaktifkan Harga Invoice ini?
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-info bt-action" id="d_btnclose"
                            data-dismiss="modal">Cancel</button>
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
    <script type="text/javascript">
        $('#tipetruck').select2({});
        $(document).on('keyup', '#ritsprice', function() {
            letterRegex = /[^\0-9\,]/;
            var data = $(this).val();

            var newdata = data.replace(/([^0-9.])/g, '');

            console.log(Number(newdata).toLocaleString('en-US'));

            $(this).val(Number(newdata).toLocaleString('en-US'));
        });

        $('form').submit(function(e) {
            document.getElementById('btnloading').style.display = '';
            document.getElementById('btnconf').style.display = 'none';
            document.getElementById('btnback').style.display = 'none';
        });


        $(document).on('click', '#btnsubmitchange', function($e) {
            var idthis = $(this).closest('td').find('#idhistory').val();
            document.getElementById('temp_id').value = idthis;

            $('#deleteModal').modal('show');
        });
    </script>
@endsection
