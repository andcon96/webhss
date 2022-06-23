@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Create Surat Jalan</li>
</ol>
@endsection

@section('content')
<form action="{{ route('suratjalan.store') }}" id="submit" method="POST">
    @csrf
    @method('POST')
    <div class="row">
        <div class="form-group row col-md-12">
            <label for="soid" class="col-md-2 col-form-label text-md-right">SO Number</label>
            <div class="col-md-3">
                <select id="soid" class="form-control" name="soid" autofocus autocomplete="off">
                    <option value="">Select Data</option>
                    @foreach($listso as $listsos)
                        <option value = "{{$listsos->id}}"
                                data-cust = "{{$listsos->getCOMaster->co_cust_code}}"
                                data-custdesc = "{{$listsos->getCOMaster->getCustomer->cust_desc}}"
                                data-shipfrom = "{{$listsos->so_ship_from}}"
                                data-shipfromid = "{{$listsos->getShipFrom->id}}"
                                data-shipto = "{{$listsos->so_ship_to}}"
                                data-shiptoid = "{{$listsos->getShipTo->id}}"
                                data-type = "{{$listsos->getCOMaster->co_type}}"
                                data-duedate = "{{$listsos->so_due_date}}"
                            >{{$listsos->so_nbr}}</option>
                    @endforeach
                </select>
            </div>

            <label for="customer" class="col-md-3 col-form-label text-md-right">Customer</label>
            <div class="col-md-3">
                <input id="customer" type="text" class="form-control" name="customer" value="" autocomplete="off" maxlength="24" readonly required autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="shipfrom" class="col-md-2 col-form-label text-md-right">Ship From</label>
            <div class="col-md-3">
                <input type="text" class="form-control" value="" id="shipfrom" name="shipfrom" autocomplete="off" maxlength="24" readonly required autofocus>
                <input type="hidden" id="shipfromid" name="shipfromid">
            </div>
            <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
            <div class="col-md-3">
                <input type="text" class="form-control" value="" id="shipto" name="shipto" autocomplete="off" maxlength="24" readonly required autofocus>
                <input type="hidden" id="shiptoid" name="shiptoid">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="duedate" class="col-md-2 col-form-label text-md-right">Due Date</label>
            <div class="col-md-3">
                <input id="duedate" type="text" class="form-control" name="duedate" value="" autocomplete="off" maxlength="24" readonly required autofocus>
            </div>
            <label for="type" class="col-md-3 col-form-label text-md-right">Type</label>
            <div class="col-md-3">
                <input id="type" type="text" class="form-control" name="type" value="" autocomplete="off" maxlength="24" required readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            @include('transaksi.sj.createsj.create-table')
        </div>
        <div class="form-group row col-md-12">
            <label for="truck" class="col-md-2 col-form-label text-md-right">Truck</label>
            <div class="col-md-3">
                <select id="truck" class="form-control" name="truck" required autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach($truck as $trucks)
                    <option value="{{$trucks->id}}" 
                        data-typetruck="{{$trucks->truck_tipe_id}}"
                        data-pengurus="{{$trucks->getUserPengurus->name}}">
                        {{$trucks->truck_no_polis}}
                    </option>
                    @endforeach
                </select>
            </div>
            <label for="pengurus" class="col-md-3 col-form-label text-md-right">Pengurus</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="pengurus" id="pengurus" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="trip" class="col-md-2 col-form-label text-md-right">Sangu Truck</label>
            <div class="col-md-3">
                <input type="text" id="sangutruck" class="form-control" value="0" readonly>
            </div>
            <label for="defaultsangu" class="col-md-3 col-form-label text-md-right">Komisi Truck</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="komisitruck" id="komisitruck" value="0" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="trip" class="col-md-2 col-form-label text-md-right">Price Per Unit</label>
            <div class="col-md-3">
                <input type="text" id="defaultprice" class="form-control" value="0" readonly>
            </div>
            <label for="defaultsangu" class="col-md-3 col-form-label text-md-right">Default Sangu</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="defaultsangu" id="defaultsangu" value="0" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="trip" class="col-md-2 col-form-label text-md-right">Jumlah Trip</label>
            <div class="col-md-3">
                <input type="number" class="form-control" name="trip" min="1" value="1" id="trip">
            </div>
            <label for="totsangu" class="col-md-3 col-form-label text-md-right">Total Sangu</label>
            <div class="col-md-3">
                <input type="text" class="form-control sangu" name="totsangu" required id="totsangu">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="remark" class="col-md-2 col-form-label text-md-right">Remark</label>
            <div class="col-md-9">
                <input type="text" id="remark" class="form-control" name="remark">
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
    $('#truck,#soid').select2({
        width: '100%'
    });
   
    $('#btnconf').hide();

    $(document).on('change', '#soid', function(){
        let soid = $(this).val();
        var cust = $(this).find(':selected').data('cust');
        var custdesc = $(this).find(':selected').data('custdesc');
        var shipfrom = $(this).find(':selected').data('shipfrom');
        var shipto = $(this).find(':selected').data('shipto');
        var type = $(this).find(':selected').data('type');
        var duedate = $(this).find(':selected').data('duedate');
        var shipfromid = $(this).find(':selected').data('shipfromid');
        var shiptoid = $(this).find(':selected').data('shiptoid');

        $('#customer').val(cust + ' - ' + custdesc);
        $('#shipfrom').val(shipfrom);
        $('#shipto').val(shipto);
        $('#type').val(type);
        $('#duedate').val(duedate);
        $('#shipfromid').val(shipfromid);
        $('#shiptoid').val(shiptoid);

        let url = "{{route('getDetailSJSO',':soid')}}"
        url = url.replace(':soid',soid);

        $.ajax({
            url : url,
            success: function(data){
                $('#btnconf').show();
                $('#addtable').html('').append(data);
            },
            error: function(data){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: 'Gagal mencari data',
                    showCloseButton: true,
                })
                $('#addtable').html('');
                $('#btnconf').hide();
            }
        })
    });

    var sum = 0;
    function getDefaultSangu(){
        sum = 0;
        $('.qtysj').each(function(){
            sum += parseFloat(this.value);
        });

        var hsangu = $('#sangutruck').val();
        var hkomisi = $('#komisitruck').val();
        var hprice = $('#defaultprice').val();
        var jmlhtrip = $('#trip').val();

        let total = parseInt(hprice) * parseInt(sum) + parseInt(jmlhtrip) * 
                    (parseInt(hsangu.replace(',','')) + parseInt(hkomisi.replace(',','')));

        total = Number(total).toLocaleString('en-US');
        
        $('#defaultsangu').val(total);
    }
    
    $(document).on('keyup', '.qtysj',function(){
        let price = $('#defaultprice').val();
        getDefaultSangu();
        $('#defaultsangu').val(Number(price * sum).toLocaleString('en-US'));
    });

    $(document).on('change', '#truck',function(){
        let truck = $(this).val();
        var typetruck = $(this).find(':selected').data('typetruck');
        var pengurus = $(this).find(':selected').data('pengurus');
        var shipfrom = $('#shipfromid').val();
        var shipto = $('#shiptoid').val();
        var trip = $('#trip').val();

        $.ajax({
            url: "{{ route('getRute') }}",
            data: {
                tipetruck: typetruck,
                shipto: shipto,
                shipfrom: shipfrom
            },
            success: function(data) {
                $('#sangutruck').val(Number(data['history_sangu'] ?? 0).toLocaleString('en-US'));
                $('#komisitruck').val(Number(data['history_ongkos'] ?? 0).toLocaleString('en-US'))
                $('#defaultprice').val(Number(data['history_harga'] ?? 0).toLocaleString('en-US'));
                getDefaultSangu();
            },
            error: function(data) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: 'Gagal mencari data',
                    showCloseButton: true,
                })
                $('#btnconf').hide();
            }
        })

        $('#pengurus').val(pengurus);
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

        console.log(Number(newdata).toLocaleString('en-US'));

        $(this).val(Number(newdata).toLocaleString('en-US'));
    });

</script>
@endsection