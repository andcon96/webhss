@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Pelaporan Trip</li>
</ol>
@endsection

@section('content')
<form action="{{ route('updateSJ') }}" method="POST" id="submit">
    @method('POST')
    @csrf
    <div class="row">
        <div class="form-group row col-md-12">
            <label for="sonbr" class="col-md-2 col-form-label text-md-right">Nomor SO</label>
            <div class="col-md-3">
                <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{$data->getSOMaster->so_nbr}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="sjnbr" class="col-md-3 col-form-label text-md-right">Nomor SPK</label>
            <div class="col-md-3">
                <input id="sjnbr" type="text" class="form-control" name="sjnbr" value="{{$data->sj_nbr}}" autocomplete="off" maxlength="24" autofocus readonly>
                <input type="hidden" name="idsjmstr" value="{{$data->id}}">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="domain" class="col-md-2 col-form-label text-md-right">Domain</label>
            <div class="col-md-3">
                <input id="domain" type="text" class="form-control" name="domain" value="{{$data->getTruck->truck_domain}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="truck" class="col-md-3 col-form-label text-md-right">Truck</label>
            <div class="col-md-3">
                <input id="truck" type="text" class="form-control" name="truck" value="{{$data->getTruck->truck_no_polis}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="shipfrom" class="col-md-2 col-form-label text-md-right">Ship From</label>
            <div class="col-md-3">
                <input id="shipfrom" type="hidden" class="form-control" name="shipfrom" value="{{$data->getSOMaster->so_ship_from}}" autocomplete="off" maxlength="24" autofocus readonly>
                <input id="" type="text" class="form-control" name="" value="{{$data->getSOMaster->so_ship_from}} -- {{$data->getSOMaster->getShipFrom->sf_desc ?? ''}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
            <div class="col-md-3">
                <input id="shipto" type="hidden" class="form-control" name="shipto" value="{{$data->getSOMaster->so_ship_to}}" autocomplete="off" maxlength="24" autofocus readonly>
                <input id="" type="text" class="form-control" name="" value="{{$data->getSOMaster->so_ship_to}} -- {{$data->getSOMaster->getShipTo->cs_shipto_name ?? ''}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="duedate" class="col-md-2 col-form-label text-md-right">Due Date</label>
            <div class="col-md-3">
                <input id="duedate" type="text" class="form-control" name="duedate" value="{{$data->getSOMaster->so_due_date}}" autocomplete="off" maxlength="24" autofocus disabled>
            </div>
            <label for="type" class="col-md-3 col-form-label text-md-right">Type</label>
            <div class="col-md-3">
                <input id="type" type="text" class="form-control" name="type" value="{{$data->getSOMaster->getCOMaster->co_type}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="customer" class="col-md-2 col-form-label text-md-right">Customer</label>
            <div class="col-md-3">
                <input id="customer" type="text" class="form-control" name="customer" value="{{$data->getSOMaster->getCOMaster->co_cust_code}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="effdate" class="col-md-3 col-form-label text-md-right">Eff Date</label>
            <div class="col-md-3">
                <input id="effdate" type="text" class="form-control" name="effdate" value="{{\Carbon\Carbon::now()->toDateString()}}" autocomplete="off" maxlength="24" autofocus>
                <input type="hidden" id="so_date" name="so_date" value="{{$data->getSOMaster->created_at->format('Y-m-d')}}">
                <input type="hidden" id="so_due_date" name="so_due_date" value="{{$data->getSOMaster->so_due_date}}">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="remarkCO" class="col-md-2 col-form-label text-md-right">Remark CO</label>
            <div class="col-md-9">
                <input id="remarkCO" type="text" class="form-control" value="{{$data->getSOMaster->getCOMaster->co_remark}}" autocomplete="off" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="remark" class="col-md-2 col-form-label text-md-right">Remark SJ</label>
            <div class="col-md-9">
                <input id="remark" type="text" class="form-control" name="remark" value="" autocomplete="off" autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="defaultsangu" class="col-md-2 col-form-label text-md-right">Default Sangu</label>
            <div class="col-md-3">
                <input id="defaultsangu" type="text" class="form-control" name="defaultsangu" value="{{number_format($data->getRuteHistory->history_sangu,0)}}" autocomplete="off" maxlength="24" readonly autofocus>
            </div>
            <label for="defaultkomisi" class="col-md-3 col-form-label text-md-right">Default Komisi</label>
            <div class="col-md-3">
                <input id="defaultkomisi" type="text" class="form-control" name="defaultkomisi" value="{{number_format($data->getRuteHistory->history_ongkos,0)}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="jmlhtrip" class="col-md-2 col-form-label text-md-right">Jumlah Trip</label>
            <div class="col-md-3">
                <input id="jmlhtrip" type="number" class="form-control" name="jmlhtrip" value="{{$data->sj_jmlh_trip}}" min="{{$data->sj_jmlh_trip}}" autocomplete="off" maxlength="24" autofocus >
            </div>
            <label for="totsangu" class="col-md-3 col-form-label text-md-right">Total Sangu</label>
            <div class="col-md-3">
                <input id="totsangu" type="text" class="form-control" name="totsangu" value="{{number_format($data->sj_default_sangu,0)}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>

        <div class="form-group row col-md-12">
            @include('transaksi.sjcust.laporsj-table-detail')
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                <div class="float-right">
                    {{-- <a href="{{ route('laporsj.index',['truck' => $truck]) }}" id="btnback" class="btn btn-success bt-action">Back</a> --}}
                    <a href="{{url()->previous()}}" id="btnback" class="btn btn-success bt-action">Back</a>
                    <button type="submit" class="btn btn-success bt-action btn-focus btnconf" id="btnconf">Confirm Shipment</button>
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
    $("#duedate").datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: '+0d',
        onClose: function() {
            $("#addrow").focus();
        }
    });
    $("#effdate").datepicker({
        dateFormat: 'yy-mm-dd',
    });

    $('#harga').select2({
        width: '100%'
    });

    function getDefaultSangu() {
        var tipebarang = $('#type').val();

        sum = 0;
        sum = $('#jmlhtrip').val();

        var hsangu = $('#defaultsangu').val();
        var hkomisi = $('#defaultkomisi').val();

        let total = (parseInt(hsangu.replace(',', '')) + parseInt(hkomisi.replace(',', ''))) * sum;

        total = Number(total).toLocaleString('en-US');

        $('#totsangu').val(total);
    }

    
    $(document).on('change keyup', '#jmlhtrip', function() {
        getDefaultSangu();

        let jmltrip = $(this).val();

        $('.qtyship').each(function(i, obj){
            let oldqty = $(this).closest('tr').find('.oldship').val();
            $(this).val(oldqty * jmltrip);
        });
    });

    $(document).on('change', '#harga',function(){
        var value = $(this).find(':selected').val();

        $('.price').val(value);

        value == 'Custom' ? $('.price').prop('readonly',false) : $('.price').prop('readonly',true)
    });
    
    $(document).on('keyup', '.sangu', function() {
        letterRegex = /[^\0-9\,]/;
        var data = $(this).val();

        var newdata = data.replace(/([^0-9])/g, '');

        console.log(Number(newdata).toLocaleString('en-US'));

        $(this).val(Number(newdata).toLocaleString('en-US'));
    });

    $(document).on('submit', '#submit', function(e) {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnback').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });

    $(document).on('click', '.btnconf', function(e){
        e.preventDefault();
        let data = $('.price').val();
        if(data == 0){
            swal.fire({
                title: 'Warning',
                text: 'Price cannot be 0 or Empty',
                type: 'warning',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        }else{
            Swal.fire({
                title: "Lapor Surat Jalan ?",
                text: "Pastikan Data Sudah Sesuai",
                type: "warning",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Lapor",
                closeOnConfirm: false
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $('#submit').submit();
                }
            })
        }
        
    });
</script>
@endsection