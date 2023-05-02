@extends('layout.layout')

@section('menu_name', 'Sales Order Maintenance')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Transaksi</a></li>
        <li class="breadcrumb-item active">Customer Order Maintenance</li>
    </ol>
@endsection

@section('content')

    <!-- Page Heading -->
    <div class="col-md-12 mb-3">
        <a href="{{ route('customerorder.create') }}" class="btn btn-info bt-action">Create CO</a>
    </div>
    <form action="{{ route('customerorder.index') }}" method="get">

        <div class="form-group row col-md-12">
            <label for="conumber" class="col-md-2 col-form-label text-md-right">{{ __('CO Number.') }}</label>
            <div class="col-md-4 col-lg-3">
                {{-- <input id="conumber" type="text" class="form-control" name="conumber" value="{{ request()->input('conumber') }}" autofocus autocomplete="off"> --}}
                <select id="conumber" class="form-control" name="conumber" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach ($listco as $conumbers)
                        <option value="{{ $conumbers->id }}">{{ $conumbers->co_nbr }}</option>
                    @endforeach
                </select>
            </div>
            <label for="s_customer" class="col-md-2 col-form-label text-md-right">{{ __('Customer') }}</label>
            <div class="col-md-4 col-lg-3">
                <select id="s_customer" class="form-control" name="s_customer" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach ($listcust as $custs)
                        <option value="{{ $custs->cust_code }}">{{ $custs->cust_code }} - {{ $custs->cust_desc }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('') }}</label>
            <div class="col-md-4 col-lg-3">
                <button class="btn bt-action newUser" id="btnsearch" value="Search">Search</button>
                <button class="btn bt-action newUser" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i
                        class="fa fa-sync"></i></button>
                <button class="btn bt-action newUser" id='btnexport' formtarget="_blank"
                    style="margin-left: 10px; width: 40px !important"><i class="fa fa-file-excel"></i></button>
            </div>
        </div>

    </form>

    @include('transaksi.customerorder.index-table')

    <!--View Modal-->
    <div id="myModal" class="modal fade bd-example-modal-lg" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-xl">
            <!-- konten modal-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">Customer Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="panel-body">
                    <!-- heading modal -->
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="conbr"
                                class="col-md-2 col-form-label text-md-right">{{ __('Nomor CO') }}</label>
                            <div class="col-md-3">
                                <input id="conbr" type="text" class="form-control" name="conbr" autocomplete="off"
                                    value="" readonly>
                            </div>

                            <label for="cust"
                                class="col-md-2 col-form-label text-md-right">{{ __('Customer') }}</label>
                            <div class="col-md-3">
                                <input id="cust" type="text" class="form-control" name="cust" autocomplete="off"
                                    value="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="status"
                                class="col-md-2 col-form-label text-md-right">{{ __('Status CO') }}</label>
                            <div class="col-md-3">
                                <input id="status" type="text" class="form-control" name="status" autocomplete="off"
                                    value="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="remark"
                                class="col-md-2 col-form-label text-md-right">{{ __('Remark CO') }}</label>
                            <div class="col-md-8">
                                <input id="remark" type="text" class="form-control" name="remark" autocomplete="off"
                                    value="" readonly>
                            </div>
                        </div>
                        <div id="form-group row">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Line</th>
                                        <th>Item Part</th>
                                        <th>Item UM</th>
                                        <th>Qty Order</th>
                                        <th>Qty Used</th>
                                    </tr>
                                </thead>
                                <tbody id="bodydetail">

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-info bt-action" id="btnclose"
                            data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                            <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="detailModal" class="modal fade bd-example-modal-lg" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-xl">
            <!-- konten modal-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">Customer Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="panel-body">
                    <!-- heading modal -->
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="conbr"
                                class="col-md-2 col-form-label text-md-right">{{ __('Nomor CO') }}</label>
                            <div class="col-md-3">
                                <input id="dconbr" type="text" class="form-control" name="conbr"
                                    autocomplete="off" value="" readonly>
                            </div>

                            <label for="cust"
                                class="col-md-2 col-form-label text-md-right">{{ __('Customer') }}</label>
                            <div class="col-md-3">
                                <input id="dcust" type="text" class="form-control" name="cust"
                                    autocomplete="off" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="status"
                                class="col-md-2 col-form-label text-md-right">{{ __('Status CO') }}</label>
                            <div class="col-md-3">
                                <input id="dstatus" type="text" class="form-control" name="status"
                                    autocomplete="off" value="" readonly>
                            </div>
                        </div>
                        <div id="form-group row">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Line</th>
                                        <th>Item Part</th>
                                        <th>Item UM</th>
                                        <th>Qty Order</th>
                                        <th>Qty Used</th>
                                    </tr>
                                </thead>
                                <tbody id="detailbd">

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-info bt-action" id="btnclose"
                            data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                            <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">Delete Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('customerorder.destroy', 'role') }}" method="post">

                    {{ method_field('delete') }}
                    {{ csrf_field() }}

                    <div class="modal-body">

                        <input type="hidden" name="_method" value="delete">

                        <input type="hidden" name="temp_id" id="temp_id" value="">

                        <div class="container">
                            <div class="row">
                                Are you sure you want to delete CO Number :&nbsp; <strong><a name="temp_uname"
                                        id="temp_uname"></a></strong>
                                &nbsp;?
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
        $('#s_customer, #conumber').select2({
            width: '100%',
        });

        function resetSearch() {
            $('#s_customer').val('');
            $('#conumber').val('');
        }

        $(document).on('click', '#btnexport', function(e) {
            e.preventDefault();
            let conbr = $('#conumber').val();
            let cust  = $('#s_customer').val();

            let datarequest  = "?conbr=:conbr&cust=:cust"; 
            datarequest = datarequest.replace(':conbr', conbr);
            datarequest = datarequest.replace(':cust', cust);
            
            
            let url = "{{ route('exportCO') }}" + datarequest;

            window.open(url, '_blank');
        })

        $(document).ready(function() {
            var cur_url = window.location.href;

            let paramString = cur_url.split('?')[1];
            let queryString = new URLSearchParams(paramString);

            let customer = queryString.get('s_customer');
            let conumber = queryString.get('conumber');

            $('#s_customer').val(customer).trigger('change');
            $('#conumber').val(conumber).trigger('change');
        });

        $(document).on('click', '.viewModal', function() { // Click to only happen on announce links
            var id = $(this).data('id');
            var conbr = $(this).data('conbr');
            var cust = $(this).data('cust');
            var status = $(this).data('status');
            var custdesc = $(this).data('custdesc');
            var remark = $(this).data('remark');

            document.getElementById("conbr").value = conbr;
            document.getElementById('cust').value = cust + ' - ' + custdesc;
            document.getElementById('status').value = status;
            document.getElementById('remark').value = remark;

            $.ajax({
                url: "/customerorder/getdetail/" + id,
                success: function(data) {
                    console.log(data);
                    $('#bodydetail').html('');
                    $('#bodydetail').html(data);
                }
            })

        });

        $(document).on('click', '.detailModal', function() { // Click to only happen on announce links
            var id = $(this).data('id');
            var conbr = $(this).data('conbr');
            var cust = $(this).data('cust');
            var status = $(this).data('status');
            var custdesc = $(this).data('custdesc');

            document.getElementById("dconbr").value = conbr;
            document.getElementById('dcust').value = cust + ' - ' + custdesc;
            document.getElementById('dstatus').value = status;

            $.ajax({
                url: "/customerorder/getalokasi/" + id,
                success: function(data) {
                    console.log(data);
                    $('#detailbd').html('');
                    $('#detailbd').html(data);
                }
            })

        });

        $(document).on('click', '.deleteModal', function() {
            var id = $(this).data('id');
            var conbr = $(this).data('conbr');
            var truck = $(this).data('polis');

            document.getElementById("temp_id").value = id;
            document.getElementById('temp_uname').text = conbr;
        });

        $(document).on('click', '#btnrefresh', function() {
            resetSearch();
        });
    </script>
@endsection
