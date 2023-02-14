@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">SPK Maintenance - Edit {{$data->sj_nbr}}</li>
</ol>
@endsection

@section('content')
<form action="{{ route('suratjalan.update',$data->id) }}" id="submit" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <input type="hidden" name="idmaster" value="{{$data->id}}">
        <input type="hidden" id="idtipesangu" name="sj_default_sangu_type" value="{{$data->sj_default_sangu_type}}">
        <input type="hidden" name="idtruck" id="idtruck" value="{{$data->getTruck->truck_tipe_id}}">
        <div class="form-group row col-md-12">
            <label for="sonbr" class="col-md-2 col-form-label text-md-right">Nomor SO</label>
            <div class="col-md-3">
                <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{$data->getSOMaster->so_nbr}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="sjnbr" class="col-md-3 col-form-label text-md-right">Nomor SPK</label>
            <div class="col-md-3">
                <input id="sjnbr" type="text" class="form-control" name="sjnbr" value="{{$data->sj_nbr}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="customer" class="col-md-2 col-form-label text-md-right">Customer</label>
            <div class="col-md-3">
                <input id="customer" type="text" class="form-control" name="customer" value="{{$data->getSOMaster->getCOMaster->co_cust_code}} - {{$data->getSOMaster->getCOMaster->getCustomer->cust_desc ?? ''}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="duedate" class="col-md-3 col-form-label text-md-right">Due Date</label>
            <div class="col-md-3">
                <input id="duedate" type="text" class="form-control" name="duedate" value="{{$data->getSOMaster->so_due_date}}" autocomplete="off" maxlength="24" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="shipfrom" class="col-md-2 col-form-label text-md-right">Ship From</label>
            <div class="col-md-3">
                <select name="shipfrom" id="shipfrom" class="form-control selectdrop">
                    <option value="">Select Data</option>
                    @foreach($shipfrom as $shipfroms)
                    <option value="{{$shipfroms->sf_code}}" data-id="{{$shipfroms->id}}" {{$shipfroms->sf_code == $data->getSOMaster->so_ship_from ? 'Selected' : ''}}>{{$shipfroms->sf_code}} -- {{$shipfroms->sf_desc}}</option>
                    @endforeach
                </select>
            </div>
            <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
            <div class="col-md-3">
                <select name="shipto" id="shipto" class="form-control selectdrop">
                    <option value="">Select Data</option>
                    @foreach($shipto as $shiptos)
                    <option value="{{$shiptos->cs_shipto}}" data-id="{{$shiptos->id}}" {{$shiptos->cs_shipto == $data->getSOMaster->so_ship_to ? 'Selected' : ''}}>{{$shiptos->cs_shipto}} -- {{$shiptos->cs_shipto_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="type" class="col-md-2 col-form-label text-md-right">Type</label>
            <div class="col-md-3">
                <input id="type" type="text" class="form-control" name="type" value="{{$data->getSOMaster->getCOMaster->co_type}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="truck" class="col-md-3 col-form-label text-md-right">Truck</label>
            <div class="col-md-3">
                {{-- <input id="truck" type="text" class="form-control" name="truck" value="{{$data->getTruck->truck_no_polis}}" autocomplete="off" maxlength="24" autofocus readonly> --}}
                
                <select name="truck" id="truck" class="form-control selectdrop">
                    <option value="">Select Data</option>
                    @foreach ($truck as $trucks)
                        <option value="$trucks->id" {{$trucks->id == $data->getTruck->id ? 'Selected' : ''}}>{{$trucks->truck_no_polis}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="barang" class="col-md-2 col-form-label text-md-right">Barang</label>
            <div class="col-md-3">
                <input id="barang" type="text" class="form-control" name="type" value="{{$data->getSOMaster->getCOMaster->getBarang->barang_deskripsi ?? ''}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="kapal" class="col-md-3 col-form-label text-md-right">Kapal</label>
            <div class="col-md-3">
                <input id="kapal" name="kapal" class="form-control" type="text" value="{{$data->getSOMaster->getCOMaster->co_kapal ?? ''}}" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            @include('transaksi.sj.edit-table')
        </div>
        <div class="form-group row col-md-12">
            <label for="listsan" class="col-md-2 col-form-label text-md-right">List Sangu</label>
            <div class="col-md-3">
                <select name="listsan" id="listsan" class="form-control selectdrop">
                    <option value="">Select Data</option>
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="defaultsangu" class="col-md-2 col-form-label text-md-right">Total Default Sangu</label>
            <div class="col-md-3">
                <input id="defaultsangu" type="text" class="form-control" name="defaultsangu" value="{{number_format($data->sj_default_sangu,0)}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="trip" class="col-md-3 col-form-label text-md-right">Trip</label>
            <div class="col-md-3">
                <input id="trip" type="number" class="form-control qtyord" name="trip" value="{{$data->sj_jmlh_trip ?? 0}}" autocomplete="off" maxlength="24" autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12" id="container">
            <label for="sangutruck" class="col-md-2 col-form-label text-md-right">Default Sangu</label>
            <div class="col-md-3">
                <input id="sangutruck" type="text" class="form-control" name="sangutruck" value="{{number_format($data->getRuteHistory->history_sangu ?? 0,0)}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="komisitruck" class="col-md-3 col-form-label text-md-right">Default Komisi</label>
            <div class="col-md-3">
                <input id="komisitruck" type="text" class="form-control" name="komisitruck" value="{{number_format($data->getRuteHistory->history_ongkos ?? 0,0)}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="totsangu" class="col-md-2 col-form-label text-md-right">Total Sangu</label>
            <div class="col-md-3">
                <input id="totsangu" type="text" class="form-control sangu" name="totsangu" value="{{number_format($data->sj_tot_sangu ?? 0,0)}}" autocomplete="off" maxlength="24" autofocus>
            </div>
            <label for="catatansj" class="col-md-3 col-form-label text-md-right">Surat Jalan</label>
            <div class="col-md-3">
                <input id="catatansj" type="text" class="form-control" name="catatansj" value="{{$data->sj_surat_jalan}}" autocomplete="off" maxlength="24" autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                <div class="float-right">
                    <a href="{{route('suratjalan.index')}}" id="btnback" class="btn btn-success bt-action">Back</a>
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
    $('.selectdrop').select2({width: '100%'});

    $("#duedate").datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: '+0d',
        onClose: function() {
            $("#addrow").focus();
        }
    });
    
    var tipebarang = $('#type').val();

    $('.tonase').css('display','none');
    $('.pricetot').removeClass('col-md-3');
    $('.pricetot').addClass('col-md-2');

    function getDefaultSangu(){
        var tipebarang = $('#type').val();
        
            sum = 0;
            // $('.qtyord').each(function(){
            //     sum += parseFloat(this.value);
            // });
            sum = $('#trip').val();
            var hsangu = $('#sangutruck').val();
            var hkomisi = $('#komisitruck').val();

            let total = (parseInt(hsangu.replace(',','')) + parseInt(hkomisi.replace(',',''))) * sum;

            total = Number(total).toLocaleString('en-US');
            $('#defaultsangu').val(total);

    }
    
    $(document).on('change keyup', '.qtyord',function(){
        getDefaultSangu();
    });

    var counter = 1;

    function selectRefresh() {
        $('.selectpicker').selectpicker().focus();
    }

    $(document).on('click', '#addrow', function() {
        var rowCount = $('#editTable tr').length;

        var currow = rowCount - 2;

        var lastline = parseInt($('#editTable tr:eq(' + currow + ') td:eq(0) input[type="number"]').val()) + 1;

        if (lastline !== lastline) {
            // check apa NaN
            lastline = 1;
        }

        var newRow = $("<tr>");
        var cols = "";
        cols += '<td data-title="Line" data-label="Line"><input type="hidden" name="idsodetail[]" class="idsodetail"><input type="hidden" name="operation[]" class="operation" value="M"><input type="hidden" name="iddetail[]" value=""><input type="number" class="form-control line" autocomplete="off" name="line[]" style="height:37px" required min="1" value="" readonly/></td>';
        cols += '<td data-label="Item Part">';
        cols += '<select id="barang" class="form-control selectpicker" style="border: 1px solid #e9ecef" name="part[]" data-size="5" data-live-search="true" required autofocus>';
        cols += '<option value = ""> -- Select Data -- </option>'
        @foreach($item as $items)
        cols += '<option value="{{$items->sod_part}}" data-idsodetail="{{$items->id}}" data-line="{{$items->sod_line}}" data-sisaqty="{{$items->sod_qty_ord - $items->sod_qty_ship}}" data-um="{{$items->getItem->item_um}}"> {{$items->sod_part}} -- {{$items->getItem->item_desc ?? ''}} </option>';
        @endforeach
        cols += '</select>';
        cols += '</td>';
        cols += '<td data-title="UM" data-label="Type"><input type="text" class="form-control um" autocomplete="off" name="um[]" style="height:37px" min="1" step="1" required readonly/></td>';

        cols += '<td data-title="Qty Order" data-label="Jumlah"><input type="text" class="form-control qtysisa" autocomplete="off" name="sisa[]" style="height:37px" value="0" required min="1" readonly/></td>';
        cols += '<td data-title="Qty Order" data-label="Jumlah"><input type="hidden" name="qtyold[]"><input type="number" class="form-control qtyord" autocomplete="off" name="qtyord[]" style="height:37px" required min="1"/></td>';
        cols += '<td data-title="Qty Ship" data-label="Jumlah"><input type="number" class="form-control" autocomplete="off" name="qtyship[]" style="height:37px" required readonly value="0"/></td>';

        cols += '<td data-title="Action"><input type="button" class="ibtnDel btn btn-danger btn-focus"  value="Delete"></td>';
        cols += '</tr>'
        newRow.append(cols);
        $("#edittable").append(newRow);
        counter++;

        selectRefresh();
    });

    $(document).on('change', '.selectpicker', function() {
        var data = $(this).val();
        var line = $(this).closest('tr').find('.line').val();
        var um = $(this).closest('tr').find('.um');

        var dataum = $(this).find(':selected').data('um');
        var qtysisa = $(this).find(':selected').data('sisaqty');
        var line = $(this).find(':selected').data('line');
        var idsodetail = $(this).find(':selected').data('idsodetail');

        $(this).closest('tr').find('.qtyord').attr('max',qtysisa);
        $(this).closest('tr').find('.line').val(line);
        $(this).closest('tr').find('.um').val(dataum);
        $(this).closest('tr').find('.qtysisa').val(qtysisa);
        $(this).closest('tr').find('.idsodetail').val(idsodetail);
    })

    $(document).on('change', '.qaddel', function() {
        var checkbox = $(this), // Selected or current checkbox
            value = checkbox.val(); // Value of checkbox

        if (checkbox.is(':checked')) {
            $(this).closest("tr").find('.operation').val('R');
        } else {
            $(this).closest("tr").find('.operation').val('M');
        }
    });

    $(document).on('submit', '#submit', function(e) {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnback').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });
    
    $(document).on('keyup', '.sangu', function() {
        var data = $(this).val();

        var newdata = data.replace(/([^ 0-9])/g, '');

        $(this).val(Number(newdata).toLocaleString('en-US'));
    });

    function resetSangu(){
        $('#defaultsangu').val(0);
        $('#sangutruck').val(0);
        $('#komisitruck').val(0);
    }

    $(document).ready(function(){
        let shipto = $('#shipto').find(':selected').data('id');
        let shipfrom = $('#shipfrom').find(':selected').data('id');
        let tipetruck = $('#idtruck').val();

        $.ajax({
            url: "{{ route('getRute') }}",
            data: {
                tipetruck: tipetruck,
                shipto: shipto,
                shipfrom: shipfrom
            },
            success: function(data) {
                let output = '<option value=""> Select Data </option>';

                if(data != 0){
                    data.forEach(element => {
                        output += `<option value="${element['id']}"
                                data-sangu="${element['history_sangu']}"
                                data-komisi="${element['history_ongkos']}"
                                data-harga="${element['history_harga']}">
                                Sangu : ${element['history_sangu']} , Komisi : ${element['history_ongkos']}
                                </option>`;
                    });
                }

                $('#listsan').html('').append(output);
            },
            error: function(data) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: 'Gagal mencari data',
                    showCloseButton: true,
                })
                $('#btnconf').hide();
            },
        })
    });

    $(document).on('change', '#shipto, #shipfrom', function(){
        let shipto = $('#shipto').find(':selected').data('id');
        let shipfrom = $('#shipfrom').find(':selected').data('id');
        let tipetruck = $('#idtruck').val();

        $.ajax({
            url: "{{ route('getRute') }}",
            data: {
                tipetruck: tipetruck,
                shipto: shipto,
                shipfrom: shipfrom
            },
            beforeSend: function() {
                $('#loader').removeClass('hidden');
            },
            success: function(data) {
                resetSangu();
                let output = '<option value=""> Select Data </option>';

                if(data != 0){
                    data.forEach(element => {
                        output += `<option value="${element['id']}"
                                data-sangu="${element['history_sangu']}"
                                data-komisi="${element['history_ongkos']}"
                                data-harga="${element['history_harga']}">
                                Sangu : ${element['history_sangu']} , Komisi : ${element['history_ongkos']}
                                </option>`;
                    });
                }

                $('#listsan').html('').append(output);
            },
            error: function(data) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: 'Gagal mencari data',
                    showCloseButton: true,
                })
                $('#btnconf').hide();
            },
            complete: function() {
                $('#loader').addClass('hidden');
            }
        })

    })

    $(document).on('change', '#listsan', function(){
        let id = $(this).val();
        let sangu = $(this).find(':selected').data('sangu');
        let komisi = $(this).find(':selected').data('komisi');
        
        $('#sangutruck').val(sangu);
        $('#komisitruck').val(komisi);
        $('#idtipesangu').val(id);

        getDefaultSangu();
    });

    $("table.edittable").on("click", ".ibtnDel", function(event) {
        var row = $(this).closest("tr");
        var line = row.find(".line").val();

        if (line == counter - 1) {
            counter -= 1
        }

        $(this).closest("tr").remove();

        // if(colCount == 2){
        //   // Row table kosong. sisa header & footer
        //   counter = 1;
        // }
    });
</script>
@endsection