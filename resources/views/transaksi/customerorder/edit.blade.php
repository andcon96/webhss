@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Customer Order Maintenance - Edit {{$data->co_nbr}}</li>
</ol>
@endsection

@section('content')
<form action="{{ route('customerorder.update',$data->id) }}" id="submit" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <input type="hidden" name="idmaster" value="{{$data->id}}">
        <div class="form-group row col-md-12">
            <label for="sonbr" class="col-md-2 col-form-label text-md-right">Nomor CO</label>
            <div class="col-md-3">
                <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{$data->co_nbr}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="customer" class="col-md-3 col-form-label text-md-right">Customer</label>
            <div class="col-md-3">
                <input id="customer" type="text" class="form-control" name="customer" value="{{$data->co_cust_code}} - {{$data->getCustomer->cust_desc}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="status" class="col-md-2 col-form-label text-md-right">Status</label>
            <div class="col-md-3">
                <input id="status" type="text" class="form-control" name="status" value="{{$data->co_status}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="kapal" class="col-md-2 col-form-label text-md-right">Kapal</label>
            <div class="col-md-3">
                <input id="kapal" type="text" class="form-control" name="kapal" value="{{$data->co_kapal}}" autocomplete="off" maxlength="24" autofocus>
            </div>
            <label for="barang" class="col-md-3 col-form-label text-md-right">Barang</label>
            <div class="col-md-3">
                <select name="barang" id="barang" class="form-control">
                    <option value="">None</option>
                    @foreach ($barang as $barangs)
                        <option value="{{$barangs->id}}" {{$data->co_barang_id == $barangs->id ? 'Selected' : ''}}>{{$barangs->barang_deskripsi}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="remark" class="col-md-2 col-form-label text-md-right">Remark</label>
            <div class="col-md-9">
                <input id="remark" type="text" class="form-control" name="remark" value="{{$data->co_remark}}" autocomplete="off" maxlength="24" autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12">
            @include('transaksi.customerorder.edit-table')
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                <div class="float-right">
                    <a href="{{route('customerorder.index')}}" id="btnback" class="btn btn-success bt-action">Back</a>
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
    $('#barang').select2({
        width : '100%',
    })
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
        cols += '<td data-title="Line" data-label="Line"><input type="hidden" name="operation[]" class="operation" value="M"><input type="hidden" name="iddetail[]" value=""><input type="number" class="form-control line" autocomplete="off" name="line[]" style="height:37px" required min="1" value="' + lastline + '"/></td>';
        cols += '<td data-label="Item Part">';
        cols += '<select id="barang" class="form-control selectpicker" style="border: 1px solid #e9ecef" name="part[]" data-size="5" data-live-search="true" required autofocus>';
        cols += '<option value = ""> -- Select Data -- </option>'
        @foreach($item as $items)
        cols += '<option value="{{$items->item_part}}"> {{$items->item_part}} -- {{$items->item_desc}} </option>';
        @endforeach
        cols += '</select>';
        cols += '</td>';
        cols += '<td data-title="Qty Order" data-label="Jumlah"><input type="number" class="form-control" autocomplete="off" name="qtyord[]" style="height:37px" required value="0" readonly/></td>';
        cols += '<td data-title="Qty Open" data-label="Jumlah"><input type="number" class="form-control" autocomplete="off" name="qtyopen[]" style="height:37px" required value="0" readonly/></td>';
        
        cols += '<td data-title="Qty New" data-label="Jumlah"><input type="number" class="form-control" autocomplete="off" name="qtynew[]" style="height:37px" required min="1"/></td>';
        
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

        $.ajax({
            url: "/getum",
            data: {
                search: data,
            },
            success: function(data) {
                um.val(data);
            }
        })


        console.log(line);
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