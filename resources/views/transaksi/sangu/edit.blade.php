@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Alokasi Sangu SO</li>
</ol>
@endsection

@section('content')
<form action="{{ route('sosangu.update',$data->id) }}" id="submit" method="POST">
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
                <input id="customer" type="text" class="form-control" name="customer" value="{{$data->so_cust}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="shipfrom" class="col-md-2 col-form-label text-md-right">Ship From</label>
            <div class="col-md-3">
                <input id="shipfrom" type="text" class="form-control" name="shipfrom" value="{{$data->so_ship_from}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
            <div class="col-md-3">
                <input id="shipto" type="text" class="form-control" name="shipto" value="{{$data->so_ship_to}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="duedate" class="col-md-2 col-form-label text-md-right">Due Date</label>
            <div class="col-md-3">
                <input id="duedate" type="text" class="form-control" name="duedate" value="{{$data->so_due_date}}" autocomplete="off" maxlength="24" autofocus disabled>
            </div>
            <label for="type" class="col-md-3 col-form-label text-md-right">Type</label>
            <div class="col-md-3">
                <input id="type" type="text" class="form-control" name="type" value="{{$data->so_type}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        
        <div class="mobileonly">
            <div class="form-group ml-4">
                <label class="col-form-label text-md-right"><h4>Detail SO</h4></label>
            </div>
        </div>
        <div class="form-group row col-md-12">
            @include('transaksi.sangu.edit-table')
        </div>
        
        <div class="mobileonly">
            <div class="form-group ml-4">
                <label class="col-form-label text-md-right"><h4>Driver & Sangu</h4></label>
            </div>
        </div>
        <div class="form-group row offset-md-2 col-md-8">
            @include('transaksi.sangu.edit-driver-table')
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                <div class="float-right">
                    <a href="{{route('sosangu.index')}}" id="btnback" class="btn btn-success bt-action">Back</a>
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
        var rowCount = $('#driverTable tr').length;

        var currow = rowCount - 2;

        var lastline = parseInt($('#driverTable tr:eq(' + currow + ') td:eq(0) input[type="number"]').val()) + 1;

        if (lastline !== lastline) {
            // check apa NaN
            lastline = 1;
        }

        var newRow = $("<tr>");
        var cols = "";
        cols += '<td data-label="No Polis">';
        cols += '<select id="polis" class="form-control selectpicker" style="border: 1px solid #e9ecef" name="polis[]" data-live-search="true" required autofocus>';
        cols += '<option value = ""> -- Select Data -- </option>'
        @foreach($truck as $trucks)
        cols += '<option value="{{$trucks->id}}"> {{$trucks->getTruck->truck_no_polis}} -- {{$trucks->getUser->name}} </option>';
        @endforeach
        cols += '</select>';
        cols += '</td>';

        cols += '<td data-title="Total Trip" data-label="Total Trip"><input type="number" class="form-control" autocomplete="off" name="qtytrip[]" style="height:37px" required min="1"/></td>';

        cols += '<td data-title="Total Trip" data-label="Sangu"><input type="text" class="form-control sangu" autocomplete="off" name="totsangu[]" style="height:37px" required min="1"/></td>';

        cols += '<td data-title="Action"><input type="button" class="ibtnDel btn btn-danger btn-focus"  value="Delete"><input type="hidden" name="operation[]" value="A"><input type="hidden" name="iddetail[]" value=""></td>';
        cols += '</tr>'
        newRow.append(cols);
        $("#driverbody").append(newRow);
        counter++;

        selectRefresh();
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

    $(document).on('keyup', '.sangu', function() {
        letterRegex = /[^\0-9\,]/;
            var data = $(this).val();

            var newdata = data.replace(/([^0-9])/g, '');

            $(this).val(Number(newdata).toLocaleString('en-US'));
    });

    $(document).on('click','.deltrip',function(){
        var checkbox = $(this), // Selected or current checkbox
        value = checkbox.val(); // Value of checkbox

        if (checkbox.is(':checked')) {
            $(this).closest("tr").find('.inpdeltrip').val('R');
        } else {
            $(this).closest("tr").find('.inpdeltrip').val('M');
        }

    });



    $("table.drivertable").on("click", ".ibtnDel", function(event) {
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