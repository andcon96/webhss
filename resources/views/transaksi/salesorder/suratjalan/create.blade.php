@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Create SPK</li>
</ol>
@endsection

@section('content')
<form action="{{ route('suratjalan.store') }}" id="submit" method="POST">
    @csrf
    @method('POST')
    <div class="row">
        <div class="form-group row col-md-12">
            <label for="sonbr" class="col-md-2 col-form-label text-md-right">SO Number</label>
            <div class="col-md-3">
                <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{$data->so_nbr}}" autocomplete="off" maxlength="24" readonly required autofocus>
                <input type="hidden" name="soid" value="{{$data->id}}">
            </div>

            <label for="conbr" class="col-md-3 col-form-label text-md-right">CO Number</label>
            <div class="col-md-3">
                <input id="conbr" type="text" class="form-control" name="conbr" value="{{$data->getCOMaster->co_nbr}}" autocomplete="off" maxlength="24" readonly required autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="duedate" class="col-md-2 col-form-label text-md-right">Due Date</label>
            <div class="col-md-3">
                <input id="duedate" type="text" class="form-control" name="duedate" value="{{$data->so_due_date}}" autocomplete="off" maxlength="24" readonly required autofocus>
            </div>
            <label for="customer" class="col-md-3 col-form-label text-md-right">Customer</label>
            <div class="col-md-3">
                <input id="customer" type="text" class="form-control" name="customer" value="{{$data->getCOMaster->co_cust_code}} - {{$data->getCOMaster->getCustomer->cust_desc ?? ''}}" autocomplete="off" maxlength="24" readonly required autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="shipfrom" class="col-md-2 col-form-label text-md-right">Ship From</label>
            <div class="col-md-3">
                <input type="text" class="form-control" value="{{$data->so_ship_from}} -- {{$data->getShipFrom->sf_desc ?? ''}}" autocomplete="off" maxlength="24" readonly required autofocus>
                <input type="hidden" id="shipfrom" name="shipfrom" value="{{$data->getShipFrom->id ?? null}}">
            </div>
            <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
            <div class="col-md-3">
                <input type="text" class="form-control" value="{{$data->so_ship_to}} -- {{$data->getShipTo->cs_shipto_name}}" autocomplete="off" maxlength="24" readonly required autofocus>
                <input type="hidden" id="shipto" name="shipto" value="{{$data->getShipTo->id}}">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="type" class="col-md-2 col-form-label text-md-right">Type</label>
            <div class="col-md-3">
                <input id="type" type="text" class="form-control" name="type" value="{{$data->getCOMaster->co_type}}" autocomplete="off" maxlength="24" required readonly>
            </div>
            <label for="barang" class="col-md-3 col-form-label text-md-right">Barang</label>
            <div class="col-md-3">
                <input type="hidden" name="barangid" id="barangid" value="{{$data->getCOMaster->co_barang_id}}">
                <input id="barang" type="text" class="form-control" name="barang" value="{{$data->getCOMaster->getBarang->barang_deskripsi ?? ''}}" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="kapal" class="col-md-2 col-form-label text-md-right">Kapal</label>
            <div class="col-md-3">
                <input id="barang" name="barang" type="text" class="form-control" value="{{$data->getCOMaster->co_kapal ?? ''}}" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            @include('transaksi.salesorder.suratjalan.create-table')
        </div>
        <div class="form-group row col-md-12">
            <label for="truck" class="col-md-2 col-form-label text-md-right">Truck</label>
            <div class="col-md-3">
                <select id="truck" class="form-control" name="truck" required autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach($truck as $trucks)
                    <option value="{{$trucks->id}}" 
                        data-typetruck="{{$trucks->truck_tipe_id}}"
                        data-pengurus="{{$trucks->getUserPengurus->name ?? ''}}"
                        data-domain="{{$trucks->truck_domain}}">
                        {{$trucks->truck_no_polis}} -- {{$trucks->getTipe->tt_desc ?? ''}}
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
            <label for="catatansj" class="col-md-2 col-form-label text-md-right">SJ</label>
            <div class="col-md-3">
                <input id="text" type="catatansj" class="form-control" name="catatansj" value=""
                    autocomplete="off" maxlength="24">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="listsan" class="col-md-2 col-form-label text-md-right">List Sangu</label>
            <div class="col-md-3">
                <select id="listsan" class="form-control" name="listsan" required autofocus autocomplete="off">
                    <option value="">Select Data</option>
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12" id="container">
            <label for="trip" class="col-md-2 col-form-label text-md-right">Tarif</label>
            <div class="col-md-3">
                <input type="text" id="sangutruck" class="form-control" value="0" readonly>
            </div>
            <label for="defaultsangu" class="col-md-3 col-form-label text-md-right">Komisi Truck</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="komisitruck" id="komisitruck" value="0" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="trip" class="col-md-2 col-form-label text-md-right tonase">Price Per Unit</label>
            <div class="col-md-3 tonase">
                <input type="text" id="defaultprice" class="form-control" value="0" readonly>
                <input type="hidden" id="defaultpriceid" name="defaultpriceid" value="">
            </div>
            <label for="defaultsangu" class="col-md-3 col-form-label text-md-right pricetot">Total Tarif</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="defaultsangu" id="defaultsangu" value="0" readonly>
            </div>
            <label for="truckdomain" class="col-md-3 col-form-label text-md-right">Truck Domain</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="truckdomain" id="truckdomain" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="trip" class="col-md-2 col-form-label text-md-right">Jumlah Trip</label>
            <div class="col-md-3">
                <input type="number" class="form-control" name="trip" min="1" value="1" id="trip">
            </div>
            <label for="totsangu" class="col-md-3 col-form-label text-md-right">Sangu</label>
            <div class="col-md-3">
                <input type="text" class="form-control sangu" required name="totsangu" value="0" id="totsangu" required>
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
    $('#truck,#listsan,#bonus').select2({
        width: '100%'
    });

    var tipebarang = $('#type').val();
    
    $('.tonase').css('display','none');
    $('.pricetot').removeClass('col-md-3');
    $('.pricetot').addClass('col-md-2');
    
    $("#duedate").datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: '+0d',
        onClose: function() {
            $("#addrow").focus();
        }
    });

    var sum = 0;
    function getDefaultSangu(){
        var tipebarang = $('#type').val();
        
        sum = 0;
        // $('.qtysj').each(function(){
        //     sum += parseFloat(this.value);
        // });
        sum = $('#trip').val();
        var hsangu = $('#sangutruck').val();
        var hkomisi = $('#komisitruck').val();

        let total = (parseInt(hsangu.replace(',','')) + parseInt(hkomisi.replace(',',''))) * sum;

        total = Number(total).toLocaleString('en-US');
        $('#defaultsangu').val(total);

    }

    $(document).on('change keyup', '.qtysj,#trip',function(){
        getDefaultSangu();
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

    $(document).on('change', '#truck',function(){
        let truck = $(this).val();
        var typetruck = $(this).find(':selected').data('typetruck');
        var pengurus = $(this).find(':selected').data('pengurus');
        var domain = $(this).find(':selected').data('domain');
        var shipfrom = $('#shipfrom').val();
        var shipto = $('#shipto').val();
        var trip = $('#trip').val();
        var barangid = $('#barangid').val();


        $.ajax({
            url: "{{ route('getRute') }}",
            data: {
                tipetruck: typetruck,
                shipto: shipto,
                shipfrom: shipfrom
            },
            success: function(data) {
                console.log(data);

                let output = '<option value=""> Select Data </option>';

                data.forEach(element => {
                    console.log(element['id']);
                    output += `<option value="${element['id']}"
                            data-sangu="${element['history_sangu']}"
                            data-komisi="${element['history_ongkos']}"
                            data-harga="${element['history_harga']}">

                            Sangu : ${element['history_sangu']} , Komisi : ${element['history_ongkos']}
                            
                            </option>`;
                });

                $('#listsan').html('').append(output);
            },
            error: function(data) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: 'Gagal mencari data',
                    showCloseButton: true,
                })
            }
        })

        resetDropDownValue(typetruck, barangid);
        $('#pengurus').val(pengurus);
        $('#truckdomain').val(domain);
    });

    $(document).on('change', '#listsan',function(){
        let sangu = $(this).find(':selected').data('sangu');
        let komisi = $(this).find(':selected').data('komisi');
        let harga = $(this).find(':selected').data('harga');
        let id = $(this).find(':selected').val();

        $('#sangutruck').val(Number(sangu).toLocaleString('en-US'));
        $('#komisitruck').val(Number(komisi).toLocaleString('en-US'))
        $('#defaultprice').val(Number(harga).toLocaleString('en-US'));
        $('#defaultpriceid').val(id);
        getDefaultSangu();
    });
    
    $(document).on('click', '#btnconf', function(e) {
        e.preventDefault();
        let totaltarif = $('#defaultsangu').val();
        
        if(totaltarif == 0){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: 'Total Tarif tidak boleh 0, Silahkan cek kembali',
                showCloseButton: true,
            })
        }else{
            document.getElementById('btnconf').style.display = 'none';
            document.getElementById('btnback').style.display = 'none';
            document.getElementById('btnloading').style.display = '';
            
            $('#submit').submit();
        }
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