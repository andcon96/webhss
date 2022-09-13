@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Sales Order Maintenance - Edit {{$data->so_nbr}}</li>
</ol>
@endsection

@section('content')
<form action="{{ route('salesorder.update',$data->id) }}" id="submit" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <input type="hidden" name="idmaster" value="{{$data->id}}">
        <div class="form-group row col-md-12">
            <label for="sonbr" class="col-md-2 col-form-label text-md-right">Nomor SO</label>
            <div class="col-md-3">
                <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{$data->so_nbr}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="customer" class="col-md-3 col-form-label text-md-right">Customer</label>
            <div class="col-md-3">
                <input id="customer" type="text" class="form-control" name="customer" value="{{$data->getCOMaster->co_cust_code}} -- {{$data->getCOMaster->getCustomer->cust_desc}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        @if ($data->getNonCancelledSJ->count() == 0)
            <div class="form-group row col-md-12">
                <label for="shipfrom" class="col-md-2 col-form-label text-md-right">Ship From</label>
                <div class="col-md-3">
                    <select name="shipfrom" id="shipfrom" class="form-control selectdrop">
                        <option value="">None</option>
                        @foreach ($shipfrom as $shipfroms)
                            <option value="{{$shipfroms->sf_code}}" {{$data->so_ship_from == $shipfroms->sf_code ? 'Selected' : ''}} >{{$shipfroms->sf_code}} -- {{$shipfroms->sf_desc}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
                <div class="col-md-3">
                    <select name="shipto" id="shipto" class="form-control selectdrop">
                        <option value="">None</option>
                        @foreach ($shipto as $shiptos)
                            <option value="{{$shiptos->cs_shipto}}" {{$data->so_ship_to == $shiptos->cs_shipto ? 'Selected' : ''}} >{{$shiptos->cs_shipto}} -- {{$shiptos->cs_shipto_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @else
            <div class="form-group row col-md-12">
                <label for="shipfrom" class="col-md-2 col-form-label text-md-right">Ship From</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="{{$data->so_ship_from}} -- {{$data->getShipFrom->sf_desc ?? ''}}" autocomplete="off" maxlength="24" autofocus readonly>
                    <input id="shipfrom" type="hidden" class="form-control" name="shipfrom" value="{{$data->so_ship_from}}" autocomplete="off" maxlength="24" autofocus readonly>
                </div>
                <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="{{$data->so_ship_to}} -- {{$data->getShipTo->cs_shipto_name}}" autocomplete="off" maxlength="24" autofocus readonly>
                    <input id="shipto" type="hidden" class="form-control" name="shipto" value="{{$data->so_ship_to}}" autocomplete="off" maxlength="24" autofocus readonly>
                </div>
            </div>
        @endif
        <div class="form-group row col-md-12">
            <label for="duedate" class="col-md-2 col-form-label text-md-right">Due Date</label>
            <div class="col-md-3">
                <input id="duedate" type="text" class="form-control" name="duedate" value="{{$data->so_due_date}}" autocomplete="off" maxlength="24" autofocus>
            </div>
            <label for="type" class="col-md-3 col-form-label text-md-right">Type</label>
            <div class="col-md-3">
                <input id="type" type="text" class="form-control" name="type" value="{{$data->getCOMaster->co_type}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="remark" class="col-md-2 col-form-label text-md-right">Remark</label>
            <div class="col-md-9">
                <input id="remark" type="text" class="form-control" name="remark" value="{{$data->so_remark}}" autocomplete="off" maxlength="50" autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12">
            @include('transaksi.salesorder.edit-table')
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                <div class="float-right">
                    <a href="{{route('salesorder.index')}}" id="btnback" class="btn btn-success bt-action">Back</a>
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
    $("#duedate").datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: '+0d',
        onClose: function() {
            $("#addrow").focus();
        }
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
        cols += '<td data-title="Line" data-label="Line"><input type="hidden" name="operation[]" class="operation" value="M"><input type="hidden" name="iddetail[]" value=""><input type="number" class="form-control line" autocomplete="off" name="line[]" style="height:37px" required min="1" value="" readonly/></td>';
        cols += '<td data-label="Item Part">';
        cols += '<select id="barang" class="form-control selectpicker" style="border: 1px solid #e9ecef" name="part[]" data-size="5" data-live-search="true" required autofocus>';
        cols += '<option value = ""> -- Select Data -- </option>'
        @foreach($item as $items)
        cols += '<option value="{{$items->cod_part}}" data-line="{{$items->cod_line}}" data-sisaqty="{{$items->cod_qty_ord - $items->cod_qty_used}}" data-um="{{$items->getItem->item_um}}"> {{$items->cod_part}} -- {{$items->getItem->item_desc ?? ''}} </option>';
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

    $(document).on('change', '.qaddel', function() {
        var checkbox = $(this), // Selected or current checkbox
            value = checkbox.val(); // Value of checkbox

        if (checkbox.is(':checked')) {
            $(this).closest("tr").find('.operation').val('R');
        } else {
            $(this).closest("tr").find('.operation').val('M');
        }
    });

    $(document).on('change', '.selectpicker', function() {
        var data = $(this).val();
        var line = $(this).closest('tr').find('.line').val();
        var um = $(this).closest('tr').find('.um');

        var dataum = $(this).find(':selected').data('um');
        var qtysisa = $(this).find(':selected').data('sisaqty');
        var line = $(this).find(':selected').data('line');
        

        $(this).closest('tr').find('.qtyord').attr('max',qtysisa);
        $(this).closest('tr').find('.um').val(dataum);
        $(this).closest('tr').find('.qtysisa').val(qtysisa);
        $(this).closest('tr').find('.line').val(line);
    })

    $(document).on('submit', '#submit', function(e) {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnback').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
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