@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Lapor Kerusakan - Edit</li>
</ol>
@endsection

@section('content')
<form action="{{ route('laporkerusakan.update',$data->id) }}" id="submit" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <input type="hidden" name="idmaster" value="{{$data->id}}">
        <div class="form-group row col-md-12">
            <label for="sonbr" class="col-md-2 col-form-label text-md-right">Kerusakan Nbr</label>
            <div class="col-md-3">
                <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{$data->kr_nbr}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>

            <label for="km" class="col-md-2 col-form-label text-md-right">Kilometer</label>
            <div class="col-md-3">
                <input id="km" type="number" class="form-control" name="km" value="{{$data->kr_km}}" autocomplete="off" maxlength="24" autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="jenis" class="col-md-2 col-form-label text-md-right">Jenis</label>
            <div class="col-md-3">  
                <select id="jenis" class="form-control selectpicker" style="border: 1px solid #e9ecef" name="jenis" data-live-search="true" required autofocus>';
                    <option value = "" selected disabled> -- Select Jenis -- </option>
                    <option value = "truck" {{!empty($data->getTruck->truck_no_polis) ? 'selected' : ''}}>Truck</option>
                    <option value = "gandengan" {{empty($data->getTruck->truck_no_polis) ? 'selected' : ''}}>Gandengan</option>
                </select>
            </div>

        </div>
        <div class="form-group row col-md-12">
            <label for="truckdriver" id="labeltruck" class="col-md-2 col-form-label text-md-right" style="display:{{!empty($data->getTruck->truck_no_polis) ? '' : 'none' }}">Truck</label>
            <div class="col-md-3" id="divtruck" style="display:{{!empty($data->getTruck->truck_no_polis) ? '' : 'none' }}" >  
                <select id="truck" class="form-control selectpicker" style="border: 1px solid #e9ecef" name="truck" data-live-search="true" autofocus>;
                    <option value = "" selected disabled> -- Select Data -- </option>
                    @foreach($truck as $truck2)
                        <option value="{{$truck2->id}}" {{$truck2->id == $data->kr_truck ? 'selected' : ''}}>{{$truck2->truck_no_polis}}</option>
                    @endforeach
                </select>
                
            </div>
                        
            <label for="gandeng" id="labelgandeng" class="col-md-2 col-form-label text-md-right" style="display:{{empty($data->getTruck->truck_no_polis) ? '' : 'none'}}">Gandengan</label>
            <div class="col-md-3" id="divgandeng" style="display:{{empty($data->getTruck->truck_no_polis) ? '' : 'none'}}">  
                
                <select id="gandeng" class="form-control selectpicker" style="border: 1px solid #e9ecef" name="gandengan" data-live-search="true" autofocus>;
                    <option value = "" selected disabled> -- Select Data -- </option>
                    @foreach($gandeng as $gandeng1)
                        <option value="{{$gandeng1->id}}" {{$gandeng1->id == $data->kr_gandeng ? 'selected' : ''}}>{{$gandeng1->gandeng_desc}}</option>
                    @endforeach
                </select>
            </div>

            {{-- @if(!empty($data->getTruck->truck_no_polis))
            <label for="truck" class="col-md-2 col-form-label text-md-right" style="display: {{!empty($data->getTruck->truck_no_polis) ? '' : 'none'}}">Truck</label>
            <div class="col-md-3" style="display: {{!empty($data->getTruck->truck_no_polis) ? '' : 'none'}}">
                <input id="truck" type="text" class="form-control" name="truck" value="{{!empty($data->getTruck->truck_no_polis) ? $data->getTruck->truck_no_polis : ''}}" autocomplete="off" maxlength="24" readonly>
            </div>
            @elseif(empty($data->getTruck->truck_no_polis))
            <label for="gandeng" class="col-md-2 col-form-label text-md-right">Gandengan</label>
            <div class="col-md-3">
                <input id="gandeng" type="text" class="form-control" name="gandeng" value="{{!empty($data->getGandeng->gandeng_code) ? $data->getGandeng->gandeng_desc : ''}}" autocomplete="off" readonly autofocus>
            </div>
            @endif --}}
            <label for="driver" class="col-md-2 col-form-label text-md-right">Driver</label>
            <div class="col-md-3">
                <input id="driver" type="text" class="form-control" name="driver" value="{{isset($data->getTruck->getUserDriver->name) ? $data->getTruck->getUserDriver->name : ''}}" autocomplete="off" maxlength="24" readonly>
            </div>
        </div>

        <div class="form-group row col-md-12">
            @include('transaksi.kerusakan.edit-table')
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
    $("#duedate").datepicker({
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
        
        var rowCount = $('#editTable tr').length;

        var currow = rowCount - 2;

        var lastline = parseInt($('#editTable tr:eq(' + currow + ') td:eq(0) input[type="number"]').val()) + 1;

        if (lastline !== lastline) {
            // check apa NaN
            lastline = 1;
        }

        var newRow = $("<tr>");
        var cols = "";
        cols += '<td data-label="Jenis Kerusakan">';
        cols += '<select id="barang" class="form-control selectpicker" style="border: 1px solid #e9ecef" name="jeniskerusakan[]" data-live-search="true" data-size="4" required autofocus>';
        cols += '<option value = ""> -- Select Data -- </option>'
        @foreach($jeniskerusakan as $jeniskerusakans)
        cols += '<option value="{{$jeniskerusakans->id}}"> {{$jeniskerusakans->kerusakan_code}} -- {{$jeniskerusakans->kerusakan_desc}} </option>';
        @endforeach
        cols += '</select>';
        cols += '</td>';
        cols += '<td> <textarea type="text" name="remarkslain[]"  value=""></textarea></td>';
        cols += '<td data-title="Action"><input type="hidden" name="operation[]" class="operation" value="A"><input type="hidden" name="iddetail[]" value=""><input type="button" class="ibtnDel btn btn-danger btn-focus"  value="Delete"></td>';
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

    $(document).on('change','#jenis',function(){
        var jenisval = document.getElementById('jenis').value;
        if(jenisval == 'truck'){
            document.getElementById('labeltruck').style.display = '';
            document.getElementById('divtruck').style.display = '';
            document.getElementById('labelgandeng').style.display = 'none';
            document.getElementById('divgandeng').style.display = 'none';
            
        }
        else if(jenisval == 'gandengan'){
            document.getElementById('labelgandeng').style.display = '';
            document.getElementById('divgandeng').style.display = '';
            document.getElementById('labeltruck').style.display = 'none';
            document.getElementById('divtruck').style.display = 'none';
            
        }
    });

    $(document).on('change','#jenis',function(){
        var thisval = $(this).val();
        if(thisval == 'truck'){
            document.getElementById('truck').required = true;
            document.getElementById('gandeng').required = false;
        }
        else if(thisval == 'gandengan'){
            document.getElementById('truck').required = false;
            document.getElementById('gandeng').required = true;
        }
    })


</script>
@endsection