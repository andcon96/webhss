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
<div class="col-md-12 mb-3">
    <a href="{{route('CreateSJ') }}" class="btn btn-info bt-action">Create SJ</a>
</div>
<form action="{{route('suratjalan.index')}}" method="get">

    <div class="form-group row col-md-12">
        <label for="sonumber" class="col-md-2 col-form-label text-md-right">{{ __('SO Number.') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="sonumber" class="form-control" name="sonumber" autofocus autocomplete="off">
                <option value=""> Select Data </option>
                @foreach($listso as $sonumbers)
                <option value="{{$sonumbers->id}}">{{$sonumbers->so_nbr}}</option>
                @endforeach
            </select>
        </div>
        <label for="sjnumber" class="col-md-2 col-form-label text-md-right">{{ __('SJ Number') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="sjnumber" class="form-control" name="sjnumber" autofocus autocomplete="off">
                <option value=""> Select Data </option>
                @foreach($listsj as $listsjs)
                <option value="{{$listsjs->id}}">{{$listsjs->sj_nbr}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row col-md-12">
        <label for="s_customer" class="col-md-2 col-form-label text-md-right">{{ __('Customer') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="s_customer" class="form-control" name="s_customer" autofocus autocomplete="off">
                <option value=""> Select Data </option>
                @foreach($listcust as $custs)
                <option value="{{$custs->cust_code}}">{{$custs->cust_code}} - {{$custs->cust_desc}}</option>
                @endforeach
            </select>
        </div>
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
                <h5 class="modal-title text-center" id="exampleModalLabel">Surat Jalan</h5>
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
                                    <th>Qty Ship</th>
                                    <th>Qty Confirm</th>
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

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Delete Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{route('suratjalan.destroy', 'role')}}" method="post">

                {{ method_field('delete') }}
                {{ csrf_field() }}

                <div class="modal-body">

                    <input type="hidden" name="_method" value="delete">

                    <input type="hidden" name="temp_id" id="temp_id" value="">

                    <div class="container">
                        <div class="row">
                            Are you sure you want to delete SJ  Number :&nbsp; <strong><a name="temp_uname" id="temp_uname"></a></strong>
                            &nbsp;?
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info bt-action" id="d_btnclose" data-dismiss="modal">Cancel</button>
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
    $('#s_customer, #sonumber, #sjnumber').select2({
        width: '100%',
    });
    
    function resetSearch(){
        $('#s_customer').val('');
        $('#sonumber').val('');
        $('#sjnumber').val('');
    }

    $(document).ready(function(){
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let customer = queryString.get('s_customer');
        let sonumber = queryString.get('sonumber');
        let sjnumber = queryString.get('sjnumber');

        $('#s_customer').val(customer).trigger('change');
        $('#sonumber').val(sonumber).trigger('change');
        $('#sjnumber').val(sjnumber).trigger('change');
    });

    $(document).on('click', '.viewModal', function() { // Click to only happen on announce links
        var id = $(this).data('id');
        var sonbr = $(this).data('sonbr');
        var sjnbr = $(this).data('sjnbr');
        var cust = $(this).data('cust');
        var custdesc = $(this).data('custdesc');
        var shipto = $(this).data('shipto');
        var status = $(this).data('status');
        var truck = $(this).data('truck');
        var pengurus = $(this).data('pengurus');
        var trip = $(this).data('trip');
        var sangu = $(this).data('sangu');
        

        document.getElementById("sonbr").value = sonbr;
        document.getElementById("sjnbr").value = sjnbr;
        document.getElementById("cust").value = cust + ' - ' + custdesc;
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