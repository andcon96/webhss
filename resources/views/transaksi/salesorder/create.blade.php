@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Sales Order Maintenance</li>
</ol>
@endsection

@section('content')
<form action="{{ route('salesorder.store') }}" id="submit" method="POST">
    @csrf
    @method('POST')
    <div class="row">
        <div class="form-group row col-md-12">
            <label for="customer" class="col-md-2 col-form-label text-md-right">Customer</label>
            <div class="col-md-3">
                <select name="customer" id="customer" class="form-control" required>
                    <option value="">Select Data</option>
                    @foreach($cust as $custs)
                    <option value="{{$custs->cust_code}}">{{$custs->cust_code}} -- {{$custs->cust_desc}}</option>
                    @endforeach
                </select>
            </div>
            <label for="type" class="col-md-3 col-form-label text-md-right">Type</label>
            <div class="col-md-3">
                <select name="type" id="type" class="form-control" required>
                    <option value="">Select Data</option>
                    <option value="BERAT">Berat</option>
                    <option value="RITS">Rits</option>
                    <option value="SHIFT">Shift</option>
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="shipfrom" class="col-md-2 col-form-label text-md-right">Ship From</label>
            <div class="col-md-3">
                <input id="shipfrom" type="text" class="form-control" name="shipfrom" value="" autocomplete="off" maxlength="24" required autofocus>
            </div>
            <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
            <div class="col-md-3">
                <select name="shipto" id="shipto" class="form-control" required>
                    <option value="">Select Data</option>
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="duedate" class="col-md-2 col-form-label text-md-right">Due Date</label>
            <div class="col-md-3">
                <input id="duedate" type="text" class="form-control" name="duedate" value="" autocomplete="off" maxlength="24" required autofocus>
            </div>
            <label for="domain" class="col-md-3 col-form-label text-md-right">Domain</label>
            <div class="col-md-3">
                <input id="domains" type="text" class="form-control" name="domains" value="{{Session::get('domain')}}" autocomplete="off" maxlength="24" required readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            @include('transaksi.salesorder.create-table')
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
    $('#customer, #type, #shipto').select2({
        width: '100%'
    });
    $("#customer").select2('open');
    
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

    $(document).on('change', '#customer',function(){
        let value = $(this).val();

        $.ajax({
            url: "/getshipto",
            data: {
                search: value,
            },
            success: function(data) {
                console.log(data);
                $('#shipto').html('').append(data);
            }
        });
    });

    function resetDropDownValue(){
        let filter = $('#type').val();

        $('.selectpicker option').each(function() {
            if ($(this).data('type') == filter || $(this).val() == ""){  
                $(this).show();
            } else {
                $(this).hide();
            }
        })

        $('.selectpicker').selectpicker('refresh');
    }

    $(document).on('change','#type',function(){
        $('.selectpicker').val('');
        resetDropDownValue();
    });

    $(document).on('click', '#addrow', function() {
        var rowCount = $('#createTable tr').length;

        var currow = rowCount - 2;

        var lastline = parseInt($('#createTable tr:eq(' + currow + ') td:eq(0) input[type="number"]').val()) + 1;

        if (lastline !== lastline) {
            // check apa NaN
            lastline = 1;
        }

        var newRow = $("<tr>");
        var cols = "";
        cols += '<td data-title="Line" data-label="Line"><input type="hidden" name="operation[]" class="operation" value="M"><input type="number" class="form-control line" autocomplete="off" name="line[]" style="height:37px" required min="1" value="' + lastline + '"/></td>';
        cols += '<td data-label="Item Part">';
        cols += '<select id="barang" class="form-control selectpicker" style="border: 1px solid #e9ecef" name="part[]" data-size="5" data-live-search="true" required autofocus>';
        cols += '<option value = ""> -- Select Data -- </option>'
        @foreach($item as $items)
        cols += '<option value="{{$items->item_part}}" data-type="{{$items->item_promo}}"> {{$items->item_part}} -- {{$items->item_desc}} </option>';
        @endforeach
        cols += '</select>';
        cols += '</td>';
        cols += '<td data-title="UM" data-label="UM"><input type="text" class="form-control um" autocomplete="off" name="um[]" style="height:37px" min="1" step="1" required readonly/></td>';

        cols += '<td data-title="Qty Order" data-label="Qty Order"><input type="number" class="form-control" autocomplete="off" name="qtyord[]" style="height:37px" required min="1"/></td>';
        
        cols += '<td data-title="Action"><input type="button" class="ibtnDel btn btn-danger btn-focus"  value="Delete"></td>';
        cols += '</tr>'
        newRow.append(cols);
        $("#addtable").append(newRow);
        counter++;

        selectRefresh();
        resetDropDownValue();
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
        
        var type = $(this).find(':selected').data('type');

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
    });

    $(document).on('submit', '#submit', function(e) {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnback').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });

    $("table#createTable").on("click", ".ibtnDel", function(event) {
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