@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Surat Jalan Maintenance</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->

<form action="{{route('suratjalan.index')}}" method="get">

    <div class="form-group row col-md-12">
        <label for="conumber" class="col-md-2 col-form-label text-md-right">{{ __('CO Number.') }}</label>
        <div class="col-md-4 col-lg-3">
            {{-- <input id="conumber" type="text" class="form-control" name="conumber" value="{{ request()->input('conumber') }}" autofocus autocomplete="off"> --}}
            <select id="conumber" class="form-control" name="conumber" autofocus autocomplete="off">
                <option value=""> Select Data </option>
                @foreach($listco as $conumbers)
                <option value="{{$conumbers->id}}">{{$conumbers->co_nbr}}</option>
                @endforeach
            </select>
        </div>
        <label for="s_customer" class="col-md-2 col-form-label text-md-right">{{ __('Customer') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="s_customer" class="form-control" name="s_customer" autofocus autocomplete="off">
                <option value=""> Select Data </option>
                @foreach($listcust as $custs)
                <option value="{{$custs->cust_code}}">{{$custs->cust_code}} - {{$custs->cust_desc}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row col-md-12">
        <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('') }}</label>
        <div class="col-md-4 col-lg-3">
            <button class="btn bt-action newUser" id="btnsearch" value="Search">Search</button>
            <button class="btn bt-action newUser" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i class="fa fa-sync"></i></button>
        </div>
    </div>


</form>

@include('transaksi.sj.index-table')

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
                        <label for="sonbr" class="col-md-2 col-form-label text-md-right">{{ __('Nomor SO') }}</label>
                        <div class="col-md-3">
                            <input id="sonbr" type="text" class="form-control" name="sonbr" autocomplete="off" value="" readonly>
                        </div>
                        <label for="sjnbr" class="col-md-2 col-form-label text-md-right">{{ __('Nomor SJ') }}</label>
                        <div class="col-md-3">
                            <input id="sjnbr" type="text" class="form-control" name="sjnbr" autocomplete="off" value="" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="cust" class="col-md-2 col-form-label text-md-right">{{ __('Customer') }}</label>
                        <div class="col-md-3">
                            <input id="cust" type="text" class="form-control" name="cust" autocomplete="off" value="" readonly>
                        </div>

                        <label for="shipto" class="col-md-2 col-form-label text-md-right">{{ __('Ship To') }}</label>
                        <div class="col-md-3">
                            <input id="shipto" type="text" class="form-control" name="shipto" autocomplete="off" value="" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="truck" class="col-md-2 col-form-label text-md-right">{{ __('Truck') }}</label>
                        <div class="col-md-3">
                            <input id="truck" type="text" class="form-control" name="cust" autocomplete="off" value="" readonly>
                        </div>

                        <label for="pengurus" class="col-md-2 col-form-label text-md-right">{{ __('Pengurus') }}</label>
                        <div class="col-md-3">
                            <input id="pengurus" type="text" class="form-control" name="pengurus" autocomplete="off" value="" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="trip" class="col-md-2 col-form-label text-md-right">{{ __('Total Trip') }}</label>
                        <div class="col-md-3">
                            <input id="trip" type="text" class="form-control" name="cust" autocomplete="off" value="" readonly>
                        </div>

                        <label for="sangu" class="col-md-2 col-form-label text-md-right">{{ __('Total Sangu') }}</label>
                        <div class="col-md-3">
                            <input id="sangu" type="text" class="form-control" name="sangu" autocomplete="off" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="status" class="col-md-2 col-form-label text-md-right">{{ __('Status SJ') }}</label>
                        <div class="col-md-3">
                            <input id="status" type="text" class="form-control" name="status" autocomplete="off" value="" readonly>
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
                                </tr>
                            </thead>
                            <tbody id="bodydetail">

                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info bt-action" id="btnclose" data-dismiss="modal">Cancel</button>
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
    $('#s_customer, #conumber').select2({
        width: '100%',
    });
    
    function resetSearch(){
        $('#s_customer').val('');
        $('#conumber').val('');
    }

    $(document).ready(function(){
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
        var sonbr = $(this).data('sonbr');
        var sjnbr = $(this).data('sjnbr');
        var cust = $(this).data('cust');
        var shipto = $(this).data('shipto');
        var status = $(this).data('status');
        var truck = $(this).data('truck');
        var pengurus = $(this).data('pengurus');
        var trip = $(this).data('trip');
        var sangu = $(this).data('sangu');
        

        document.getElementById("sonbr").value = sonbr;
        document.getElementById("sjnbr").value = sjnbr;
        document.getElementById("cust").value = cust;
        document.getElementById("shipto").value = shipto;
        document.getElementById("status").value = status;
        document.getElementById("truck").value = truck;
        document.getElementById("pengurus").value = pengurus;
        document.getElementById("trip").value = trip;
        document.getElementById("sangu").value = sangu;

        $.ajax({
            url: "/suratjalan/getdetail/" + id,
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

    $(document).on('click', '#btnrefresh', function(){
        resetSearch();
    });


</script>
@endsection