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
            <label for="soid" class="col-md-2 col-form-label text-md-right">SO Number</label>
            <div class="col-md-3">
                <select id="soid" class="form-control" name="soid" autofocus autocomplete="off">
                    <option value="">Select Data</option>
                    @foreach($listso as $listsos)
                        <option value = "{{$listsos->id}}"
                                data-cust = "{{$listsos->getCOMaster->co_cust_code}}"
                                data-custdesc = "{{$listsos->getCOMaster->getCustomer->cust_desc}}"
                                data-shipfrom = "{{$listsos->so_ship_from}}"
                                data-shipfromdesc = "{{$listsos->getShipFrom->sf_desc ?? null}}"
                                data-shipfromid = "{{$listsos->getShipFrom->id ?? null}}"
                                data-shipto = "{{$listsos->so_ship_to}}"
                                data-shiptodesc = "{{$listsos->getShipTo->cs_shipto_name}}"
                                data-shiptoid = "{{$listsos->getShipTo->id}}"
                                data-type = "{{$listsos->getCOMaster->co_type}}"
                                data-duedate = "{{$listsos->so_due_date}}"
                                data-conbr = "{{$listsos->getCOMaster->co_nbr}}"
                            >{{$listsos->so_nbr}} -- {{$listsos->getCOMaster->getCustomer->cust_desc ?? ''}}</option>
                    @endforeach
                </select>
            </div>

            <label for="conbr" class="col-md-3 col-form-label text-md-right">CO Number</label>
            <div class="col-md-3">
                <input id="conbr" type="text" class="form-control" name="conbr" value="" autocomplete="off" maxlength="24" readonly required autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="duedate" class="col-md-2 col-form-label text-md-right">Due Date</label>
            <div class="col-md-3">
                <input id="duedate" type="text" class="form-control" name="duedate" value="" autocomplete="off" maxlength="24" readonly required autofocus>
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
            <label for="type" class="col-md-2 col-form-label text-md-right">Type</label>
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
                        data-pengurus="{{$trucks->getUserPengurus->name ?? ''}}"
                        data-domain="{{$trucks->truck_domain}}">
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
        var shipfromdesc = $(this).find(':selected').data('shipfromdesc');
        var shiptodesc = $(this).find(':selected').data('shiptodesc');
        var type = $(this).find(':selected').data('type');
        var duedate = $(this).find(':selected').data('duedate');
        var shipfromid = $(this).find(':selected').data('shipfromid');
        var shiptoid = $(this).find(':selected').data('shiptoid');
        var conbr = $(this).find(':selected').data('conbr');

        $('#customer').val(cust + ' - ' + custdesc);
        $('#shipfrom').val(shipfrom + ' - ' + shipfromdesc);
        $('#shipto').val(shipto + ' - ' + shiptodesc);
        $('#type').val(type);
        $('#duedate').val(duedate);
        $('#shipfromid').val(shipfromid);
        $('#shiptoid').val(shiptoid);
        $('#conbr').val(conbr);

        let url = "{{route('getDetailSJSO',':soid')}}"
        url = url.replace(':soid',soid);

        $.ajax({
            url : url,
            beforeSend: function(){
                $('#loader').removeClass('hidden');
            },
            success: function(data){
                $('#btnconf').show();
                $('#addtable').html('').append(data);

                console.log(type);
                // if(type == 'BERAT'){
                //     $('#container').css('display','none');
                //     $('.tonase').css('display','');
                //     $('.pricetot').removeClass('col-md-2');
                //     $('.pricetot').addClass('col-md-3');
                // }else if(type == 'TRIP'){
                // }
                $('.tonase').css('display','none');
                $('.pricetot').removeClass('col-md-3');
                $('.pricetot').addClass('col-md-2');
                $('#container').css('display','');
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
            },
            complete: function(){
                $('#loader').addClass('hidden');
            }
        })
    });

    var sum = 0;
    var tipebarang = $('#type').val();

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

    $('.tonase').css('display','none');
    $('.pricetot').removeClass('col-md-3');
    $('.pricetot').addClass('col-md-2');
    
    $(document).on('change keyup', '.qtysj,#trip',function(){
        getDefaultSangu();
    });

    $(document).on('change', '#truck',function(){
        let truck = $(this).val();
        var typetruck = $(this).find(':selected').data('typetruck');
        var pengurus = $(this).find(':selected').data('pengurus');
        var domain = $(this).find(':selected').data('domain');
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
                console.log(data);
                $('#sangutruck').val(Number(data['history_sangu'] ?? 0).toLocaleString('en-US'));
                $('#komisitruck').val(Number(data['history_ongkos'] ?? 0).toLocaleString('en-US'))
                $('#defaultprice').val(Number(data['history_harga'] ?? 0).toLocaleString('en-US'));
                $('#defaultpriceid').val(data['id']);
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
        $('#truckdomain').val(domain);
    })
    
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
        var data = $(this).val();

        var newdata = data.replace(/([^ 0-9])/g, '');

        $(this).val(Number(newdata).toLocaleString('en-US'));
    });

</script>
@endsection