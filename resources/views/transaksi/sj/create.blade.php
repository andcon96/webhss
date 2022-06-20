@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Create Surat Jalan</li>
</ol>
@endsection

@section('content')
<form action="{{ route('salesorder.store') }}" id="submit" method="POST">
    @csrf
    @method('POST')
    <div class="row">
        <div class="form-group row col-md-12">
            <label for="sonbr" class="col-md-2 col-form-label text-md-right">SO Number</label>
            <div class="col-md-3">
                <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{$data->so_nbr}}" autocomplete="off" maxlength="24" readonly required autofocus>
            </div>

            <label for="customer" class="col-md-3 col-form-label text-md-right">Customer</label>
            <div class="col-md-3">
                <input id="customer" type="text" class="form-control" name="customer" value="{{$data->so_cust}}" autocomplete="off" maxlength="24" readonly required autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="shipfrom" class="col-md-2 col-form-label text-md-right">Ship From</label>
            <div class="col-md-3">
                <input id="shipfrom" type="text" class="form-control" name="shipfrom" value="{{$data->so_ship_from}}" autocomplete="off" maxlength="24" readonly required autofocus>
            </div>
            <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
            <div class="col-md-3">
                <input id="shipfrom" type="text" class="form-control" name="shipfrom" value="{{$data->so_ship_to}}" autocomplete="off" maxlength="24" readonly required autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="duedate" class="col-md-2 col-form-label text-md-right">Due Date</label>
            <div class="col-md-3">
                <input id="duedate" type="text" class="form-control" name="duedate" value="{{$data->so_due_date}}" autocomplete="off" maxlength="24" readonly required autofocus>
            </div>
            <label for="type" class="col-md-3 col-form-label text-md-right">Type</label>
            <div class="col-md-3">
                <input id="type" type="text" class="form-control" name="type" value="{{$data->so_type}}" autocomplete="off" maxlength="24" required readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            @include('transaksi.sj.create-table')
        </div>
        <div class="form-group row col-md-12">
            <label for="truck" class="col-md-2 col-form-label text-md-right">Truck</label>
            <div class="col-md-3">
                <select id="truck" class="form-control" name="truck" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach($truck as $trucks)
                    <option value="{{$trucks->id}}" data-pengurus="{{$trucks->getActiveDriver->getPengurus->name}}">{{$trucks->truck_no_polis}}</option>
                    @endforeach
                </select>
            </div>
            <label for="truck" class="col-md-2 col-form-label text-md-right">Pengurus</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="pengurus" id="pengurus" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="trip" class="col-md-2 col-form-label text-md-right">Jumlah Trip</label>
            <div class="col-md-3">
                <input type="number" class="form-control" name="trip" min="1" value="1" id="trip">
            </div>
            <label for="totsangu" class="col-md-2 col-form-label text-md-right">Total Sangu</label>
            <div class="col-md-3">
                <input type="text" class="form-control sangu" name="totsangu" id="totsangu">
            </div>
        </div>

        <div class="form-group row col-md-12">
            <label for="trip" class="col-md-2 col-form-label text-md-right">Default Sangu</label>
            <div class="col-md-3">
                <input type="number" class="form-control" name="trip" id="trip" value="0" readonly>
            </div>
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
    $('#truck').select2({
        width: '100%'
    });
   
    $("#duedate").datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: '+0d',
        onClose: function() {
            $("#addrow").focus();
        }
    });

    $(document).on('change', '#truck',function(){
        let truck = $(this).val();
        var pengurus = $(this).find(':selected').data('pengurus');

        $('#pengurus').val(pengurus);
    })


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

    $(document).on('submit', '#submit', function(e) {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnback').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });

    
    $(document).on('keyup', '.sangu', function() {
        letterRegex = /[^\0-9\,]/;
        var data = $(this).val();

        var newdata = data.replace(/([^0-9])/g, '');

        console.log(Number(newdata).toLocaleString('en-US'));

        $(this).val(Number(newdata).toLocaleString('en-US'));
    });

</script>
@endsection