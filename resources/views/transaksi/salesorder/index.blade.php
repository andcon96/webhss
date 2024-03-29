@extends('layout.layout')

@section('menu_name', 'Sales Order Maintenance')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Transaksi</a></li>
        <li class="breadcrumb-item active">Sales Order Maintenance</li>
    </ol>
@endsection

@section('content')

    <!-- Page Heading -->
    <div class="col-md-12 mb-3">
        <a href="{{ route('salesorder.create') }}" class="btn btn-info bt-action">Create SO</a>
    </div>
    <form action="{{ route('salesorder.index') }}" method="get">

        <div class="form-group row col-md-12">
            <label for="s_sonumber" class="col-md-2 col-form-label text-md-right">{{ __('SO Number.') }}</label>
            <div class="col-md-4 col-lg-3">
                <select id="s_sonumber" class="form-control" name="s_sonumber" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach ($salesord as $salesords)
                        <option value="{{ $salesords->id }}">{{ $salesords->so_nbr }}</option>
                    @endforeach
                </select>
            </div>
            <label for="s_conumber" class="col-md-2 col-form-label text-md-right">{{ __('CO Number.') }}</label>
            <div class="col-md-4 col-lg-3">
                <select id="s_conumber" class="form-control" name="s_conumber" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach ($custord as $custords)
                        <option value="{{ $custords->id }}">{{ $custords->co_nbr }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row col-md-12">
            <label for="s_shipfrom" class="col-md-2 col-form-label text-md-right">{{ __('Ship From') }}</label>
            <div class="col-md-4 col-lg-3">
                <select id="s_shipfrom" class="form-control" name="s_shipfrom" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    <option value="null">None</option>
                    @foreach ($shipfrom as $shipfroms)
                        <option value="{{ $shipfroms->sf_code }}">{{ $shipfroms->sf_code }} -- {{ $shipfroms->sf_desc }}
                        </option>
                    @endforeach
                </select>
            </div>

            <label for="s_shipto" class="col-md-2 col-form-label text-md-right">{{ __('Ship To') }}</label>
            <div class="col-md-4 col-lg-3">
                <select id="s_shipto" class="form-control" name="s_shipto" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach ($shipto as $shiptos)
                        <option value="{{ $shiptos->cs_shipto }}">{{ $shiptos->cs_shipto }} --
                            {{ $shiptos->cs_shipto_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row col-md-12">
            <label for="s_customer" class="col-md-2 col-form-label text-md-right">{{ __('Customer') }}</label>
            <div class="col-md-4 col-lg-3">
                <select id="s_customer" class="form-control" name="s_customer" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach ($cust as $custs)
                        <option value="{{ $custs->cust_code }}">{{ $custs->cust_code }} - {{ $custs->cust_desc }}
                        </option>
                    @endforeach
                </select>
            </div>
            <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('Status') }}</label>
            <div class="col-md-4 col-lg-3">
                <select id="s_status" class="form-control" name="s_status" autofocus autocomplete="off">
                    <option value=""> --Select Status-- </option>
                    <option value="Open">Open</option>
                    <option value="Closed">Closed</option>
                    <option value="Cancelled">Cancelled</option>
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

    @include('transaksi.salesorder.index-table')

    <!--View Modal-->
    <div id="myModal" class="modal fade bd-example-modal-lg" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-xl">
            <!-- konten modal-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">View SO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="panel-body">
                    <!-- heading modal -->
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="sonbr"
                                class="col-md-2 col-form-label text-md-right">{{ __('Nomor SO') }}</label>
                            <div class="col-md-3">
                                <input id="sonbr" type="text" class="form-control" name="sonbr"
                                    autocomplete="off" value="" readonly>
                            </div>

                            <label for="cust"
                                class="col-md-2 col-form-label text-md-right">{{ __('Customer') }}</label>
                            <div class="col-md-3">
                                <input id="cust" type="text" class="form-control" name="cust"
                                    autocomplete="off" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="shipfrom"
                                class="col-md-2 col-form-label text-md-right">{{ __('Ship From') }}</label>
                            <div class="col-md-3">
                                <input id="shipfrom" type="text" class="form-control" name="shipfrom"
                                    autocomplete="off" value="" readonly>
                            </div>
                            <label for="shipto"
                                class="col-md-2 col-form-label text-md-right">{{ __('Ship To') }}</label>
                            <div class="col-md-3">
                                <input id="shipto" type="text" class="form-control" name="shipto"
                                    autocomplete="off" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="type"
                                class="col-md-2 col-form-label text-md-right">{{ __('Type') }}</label>
                            <div class="col-md-3">
                                <input id="type" type="text" class="form-control" name="type"
                                    autocomplete="off" value="" readonly>
                            </div>

                            <label for="duedate"
                                class="col-md-2 col-form-label text-md-right">{{ __('Due Date') }}</label>
                            <div class="col-md-3">
                                <input id="duedate" type="text" class="form-control" name="duedate"
                                    autocomplete="off" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="barang"
                                class="col-md-2 col-form-label text-md-right">{{ __('Barang') }}</label>
                            <div class="col-md-3">
                                <input id="barang" type="text" class="form-control" name="barang"
                                    autocomplete="off" value="" readonly>
                            </div>
                            <label for="poaju"
                                class="col-md-2 col-form-label text-md-right">{{ __('PO/AJU') }}</label>
                            <div class="col-md-3">
                                <input id="poaju" type="text" class="form-control" name="poaju"
                                    autocomplete="off" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="remark"
                                class="col-md-2 col-form-label text-md-right">{{ __('Remark') }}</label>
                            <div class="col-md-8">
                                <input id="remark" type="text" class="form-control" name="remark"
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
                                        <th>Qty Ship</th>
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

    <!-- Delete Modal -->
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

                <form action="{{ route('salesorder.destroy', 'role') }}" method="post">

                    {{ method_field('delete') }}
                    {{ csrf_field() }}

                    <div class="modal-body">

                        <input type="hidden" name="_method" value="delete">

                        <input type="hidden" name="temp_id" id="temp_id" value="">

                        <div class="container">
                            <div class="row">
                                Are you sure you want to delete SO Number :&nbsp; <strong><a name="temp_uname"
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


    <div id="detailModal" class="modal fade bd-example-modal-lg" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-xl">
            <!-- konten modal-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">Alokasi SO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="panel-body">
                    <!-- heading modal -->
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="dsonbr"
                                class="col-md-2 col-form-label text-md-right">{{ __('Nomor SO') }}</label>
                            <div class="col-md-3">
                                <input id="dsonbr" type="text" class="form-control" name="dsonbr"
                                    autocomplete="off" value="" readonly>
                            </div>

                            <label for="dcust"
                                class="col-md-2 col-form-label text-md-right">{{ __('Customer') }}</label>
                            <div class="col-md-3">
                                <input id="dcust" type="text" class="form-control" name="dcust"
                                    autocomplete="off" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dshipfrom"
                                class="col-md-2 col-form-label text-md-right">{{ __('Ship From') }}</label>
                            <div class="col-md-3">
                                <input id="dshipfrom" type="text" class="form-control" name="dshipfrom"
                                    autocomplete="off" value="" readonly>
                            </div>
                            <label for="dshipto"
                                class="col-md-2 col-form-label text-md-right">{{ __('Ship To') }}</label>
                            <div class="col-md-3">
                                <input id="dshipto" type="text" class="form-control" name="dshipto"
                                    autocomplete="off" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dtype"
                                class="col-md-2 col-form-label text-md-right">{{ __('Type') }}</label>
                            <div class="col-md-3">
                                <input id="dtype" type="text" class="form-control" name="dtype"
                                    autocomplete="off" value="" readonly>
                            </div>

                            <label for="dduedate"
                                class="col-md-2 col-form-label text-md-right">{{ __('Due Date') }}</label>
                            <div class="col-md-3">
                                <input id="dduedate" type="text" class="form-control" name="dduedate"
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
                                        <th>Qty Ship</th>
                                    </tr>
                                </thead>
                                <tbody id="dbodydetail">

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


@endsection


@section('scripts')

    <script type="text/javascript">
        $('#s_customer, #s_status, #s_sonumber, #s_conumber, #s_shipfrom, #s_shipto').select2({
            width: '100%',
        });

        function resetSearch() {
            $('#s_customer').val('');
            $('#s_shipfrom').val('');
            $('#s_shipto').val('');
            $('#s_status').val('');
            $('#s_sonumber').val('');
            $('#s_conumber').val('');
        }
        
        $(document).on('click', '#btnexport', function(e) {
            e.preventDefault();
            
            let s_sonumber  = $('#s_sonumber').val();
            let s_conumber  = $('#s_conumber').val();
            let s_shipfrom  = $('#s_shipfrom').val();
            let s_shipto    = $('#s_shipto').val();
            let s_customer  = $('#s_customer').val();
            let s_status    = $('#s_status').val();
            
            let datarequest  = "?s_sonumber=:s_sonumber&s_conumber=:s_conumber&s_shipfrom=:s_shipfrom&s_shipto=:s_shipto&s_customer=:s_customer&s_status=:s_status"; 
            datarequest = datarequest.replace(':s_sonumber', s_sonumber);
            datarequest = datarequest.replace(':s_conumber', s_conumber);
            datarequest = datarequest.replace(':s_shipfrom', s_shipfrom);
            datarequest = datarequest.replace(':s_shipto', s_shipto);
            datarequest = datarequest.replace(':s_customer', s_customer);
            datarequest = datarequest.replace(':s_status', s_status);
            
            let url = "{{ route('exportSO') }}" + datarequest;
            
            window.open(url, '_blank');
        });

        $(document).ready(function() {
            var cur_url = window.location.href;

            let paramString = cur_url.split('?')[1];
            let queryString = new URLSearchParams(paramString);

            let customer = queryString.get('s_customer');
            let shipfrom = queryString.get('s_shipfrom');
            let shipto = queryString.get('s_shipto');
            let status = queryString.get('s_status');
            let sonbr = queryString.get('s_sonumber');
            let conbr = queryString.get('s_conumber');

            $('#s_customer').val(customer).trigger('change');
            $('#s_status').val(status).trigger('change');
            $('#s_sonumber').val(sonbr).trigger('change');
            $('#s_conumber').val(conbr).trigger('change');
            $('#s_shipfrom').val(shipfrom).trigger('change');
            $('#s_shipto').val(shipto).trigger('change');
        });

        $(document).on('click', '.viewModal', function() { // Click to only happen on announce links
            var id = $(this).data('id');
            var sonbr = $(this).data('sonbr');
            var cust = $(this).data('cust');
            var type = $(this).data('type');
            var shipfrom = $(this).data('shipfrom');
            var shipfromdesc = $(this).data('shipfromdesc');
            var shipto = $(this).data('shipto');
            var shiptodesc = $(this).data('shiptodesc');
            var duedate = $(this).data('duedate');
            var custdesc = $(this).data('custdesc');
            var remark = $(this).data('remark');
            var barang = $(this).data('barang');
            var poaju = $(this).data('poaju');

            document.getElementById("sonbr").value = sonbr;
            document.getElementById('cust').value = cust + ' - ' + custdesc;
            document.getElementById('type').value = type;
            document.getElementById('shipfrom').value = shipfrom + ' - ' + shipfromdesc;
            document.getElementById('shipto').value = shipto + ' - ' + shiptodesc;
            document.getElementById('duedate').value = duedate;
            document.getElementById('remark').value = remark;
            document.getElementById("barang").value = barang;
            document.getElementById("poaju").value = poaju;


            $.ajax({
                url: "/salesorder/getdetail/" + id,
                success: function(data) {
                    console.log(data);
                    $('#bodydetail').html('');
                    $('#bodydetail').html(data);
                }
            })

        });

        $(document).on('click', '.deleteModal', function() {
            var id = $(this).data('id');
            var sonbr = $(this).data('sonbr');
            var truck = $(this).data('polis');

            document.getElementById("temp_id").value = id;
            document.getElementById('temp_uname').text = sonbr;
        });

        $(document).on('click', '.detailModal', function() {
            var id = $(this).data('id');
            var sonbr = $(this).data('sonbr');
            var cust = $(this).data('cust');
            var type = $(this).data('type');
            var shipfrom = $(this).data('shipfrom');
            var shipfromdesc = $(this).data('shipfromdesc');
            var shipto = $(this).data('shipto');
            var shiptodesc = $(this).data('shiptodesc');
            var duedate = $(this).data('duedate');
            var custdesc = $(this).data('custdesc');

            document.getElementById("dsonbr").value = sonbr;
            document.getElementById('dcust').value = cust + ' - ' + custdesc;
            document.getElementById('dtype').value = type;
            document.getElementById('dshipfrom').value = shipfrom + ' - ' + shipfromdesc;
            document.getElementById('dshipto').value = shipto + ' - ' + shiptodesc;
            document.getElementById('dduedate').value = duedate;


            $.ajax({
                url: "/salesorder/getalokasi/" + id,
                success: function(data) {
                    console.log(data);
                    $('#dbodydetail').html('');
                    $('#dbodydetail').html(data);
                }
            })
        });

        $(document).on('click', '#btnrefresh', function() {
            resetSearch();
        });
    </script>
@endsection
