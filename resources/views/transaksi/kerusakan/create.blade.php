@extends('layout.layout')

@section('menu_name','Lapor Kerusakan')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Lapor Kerusakan</li>
</ol>
@endsection

@section('content')
<form action="{{ route('laporkerusakan.store') }}" id="submit" method="POST">
    @csrf
    @method('POST')
    <div class="row">
        <div class="form-group row col-md-12">
            <label for="truckdriver" class="col-md-2 col-form-label text-md-right">Truck</label>
            <div class="col-md-3">  
                <select id="truck" class="form-control selectpicker" style="border: 1px solid #e9ecef" name="truck" data-live-search="true" required autofocus>';
                    <option value = ""> -- Select Data -- </option>
                    @foreach($truck as $truck2)
                        <option value="{{$truck2->id}}">{{$truck2->truck_no_polis}}</option>
                    @endforeach
                </select>
            </div>
            <label for="tgllapor" class="col-md-2 col-form-label text-md-right">Tanggal Lapor</label>
            <div class="col-md-3">
                <input id="tgllapor" type="text" class="form-control" name="tgllapor" value="" autocomplete="off" maxlength="24" required autofocus>
            </div>
            
        </div>
        <div class="form-group row col-md-12">
            
            <label for="truckdriver" class="col-md-2 col-form-label text-md-right">Kilometer</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="km" required>
            </div>
        </div>
        <div class="form-group row col-md-12">
            @include('transaksi.kerusakan.create-table')
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                <div class="float-right">
                    <a href="{{route('laporkerusakan.index')}}" id="btnback" class="btn btn-success bt-action">Back</a>
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
    $('#truckdriver').select2({
        width: '100%'
    });
    $("#customer").select2('open');
    
    $("#tgllapor").datepicker({
        dateFormat: 'yy-mm-dd',
        // minDate: '+0d',
        onClose: function() {
            $("#addrow").focus();
        }
    });

    var counter = 1;

    function selectRefresh() {
        $('.selectpicker').selectpicker().focus();
    }

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
        cols += '<td data-label="Jenis Kerusakan">';
        cols += '<select id="jeniskerusakan" class="form-control selectpicker" style="border: 1px solid #e9ecef" name="jeniskerusakan[]" data-live-search="true" data-size="4" required autofocus>';
        cols += '<option value = ""> -- Select Data -- </option>'
        @foreach($jeniskerusakan as $jeniskerusakans)
        cols += '<option value="{{$jeniskerusakans->id}}"> {{$jeniskerusakans->kerusakan_code}} -- {{$jeniskerusakans->kerusakan_desc}} </option>';
        @endforeach
        cols += '</select>';
        cols += '</td>';
        cols += '<td data-title="Action"><textarea type="text" class="form-control remarkslain" name="remarkslain[]"></td>';
        cols += '<td data-title="Action"><input type="button" class="ibtnDel btn btn-danger btn-focus"  value="Delete"></td>';
        cols += '</tr>'
        newRow.append(cols);
        $("#addtable").append(newRow);
        counter++;

        selectRefresh();
    });
    // $(document).on('change','.selectpicker',function(){
    //     var val = $("option:selected",this).text().split(' -- ')[1].trim();
        
    //     if(val == 'LAIN - LAIN'){
    //         $(this).closest('div').closest('tr').find('.remarkslain').readOnly = false
    //     }
    //     else{
           
    //         $(this).closest('tr').find('.remarkslain').readOnly = true
        
    //     }
    // })
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

    $("table.addtable").on("click", ".ibtnDel", function(event) {
        var row = $(this).closest("tr");
        var line = row.find(".line").val();

        if (line == counter - 1) {
            counter -= 1
        }
        
        $(this).closest("tr").remove();

        if(colCount == 2){
          // Row table kosong. sisa header & footer
          counter = 1;
        }
    });
</script>
@endsection