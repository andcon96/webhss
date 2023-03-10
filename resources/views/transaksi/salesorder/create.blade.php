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
            <label for="conbr" class="col-md-2 col-form-label text-md-right">Customer Order</label>
            <div class="col-md-3">
                <select name="conbr" id="conbr" class="form-control" required>
                    <option value="">Select Data</option>
                    @foreach($conbr as $conbrs)
                    <option value="{{$conbrs->id}}" 
                            data-cust="{{$conbrs->co_cust_code}}"
                            data-custdesc="{{$conbrs->getCustomer->cust_desc}}"
                            data-type="{{$conbrs->co_type}}"
                            data-barang="{{$conbrs->getBarang->barang_deskripsi ?? ''}}">{{$conbrs->co_nbr}} -- {{$conbrs->getCustomer->cust_desc ?? ''}}</option>
                    @endforeach
                </select>
            </div>
            <label for="customer" class="col-md-3 col-form-label text-md-right">Customer</label>
            <div class="col-md-3">
                <input id="customer" type="text" class="form-control" name="customer" value="" autocomplete="off" maxlength="24" readonly autofocus>
                <input type="hidden" id="custcode" name="custcode">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="type" class="col-md-2 col-form-label text-md-right">Type</label>
            <div class="col-md-3">
                <input id="type" type="text" class="form-control" name="type" value="" autocomplete="off" maxlength="24" required readonly>
            </div>
            <label for="barang" class="col-md-3 col-form-label text-md-right">Barang</label>
            <div class="col-md-3">
                <input type="text" name="barang" id="barang" class="form-control" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="shipfrom" class="col-md-2 col-form-label text-md-right">Ship From</label>
            <div class="col-md-3">
                <select name="shipfrom" id="shipfrom" class="form-control">
                    <option value="">None</option>
                    @foreach($shipfrom as $shipfroms)
                        <option value="{{$shipfroms->sf_code}}">{{$shipfroms->sf_code}} -- {{$shipfroms->sf_desc}}</option>
                    @endforeach
                </select>
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
                <input id="duedate" type="text" class="form-control" name="duedate" value="{{\Carbon\Carbon::now()->addDays(1)->toDateString()}}" autocomplete="off" maxlength="24" required autofocus>
            </div>
            <label for="poaju" class="col-md-3 col-form-label text-md-right">PO/AJU</label>
            <div class="col-md-3">
                <input id="poaju" type="text" class="form-control" name="poaju" value="" autocomplete="off" maxlength="50" autofocus>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="remarks" class="col-md-2 col-form-label text-md-right">Remarks</label>
            <div class="col-md-9">
                <input id="remarks" type="text" class="form-control" name="remarks" value="" autocomplete="off" maxlength="50" required autofocus>
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
    $('#shipto,#conbr,#shipfrom').select2({
        width: '100%'
    });
    
    $("#duedate").datepicker({
        dateFormat: 'yy-mm-dd',
        onClose: function() {
            $("#addrow").focus();
        }
    });

    $('#btnconf').hide();

    $(document).on('change', '#conbr', function(){
        let value = $(this).val();
        var type = $(this).find(':selected').data('type');
        var cust = $(this).find(':selected').data('cust');
        var desc = $(this).find(':selected').data('custdesc');
        var barang = $(this).find(':selected').data('barang');
        var customer = cust + ' - ' + desc;

        $('#customer').val(customer);
        $('#custcode').val(cust);
        $('#type').val(type);
        $('#barang').val(barang);

        if(value != ''){
            $.ajax({
                url: "/getshipto",
                data: {
                    search: cust,
                },
                beforeSend: function(){
                    $('#loader').removeClass('hidden');
                },
                success: function(data) {
                    $('#shipto').html('').append(data);
                    $.ajax({
                        url: "{{ route('getCO') }}",
                        data: {
                            search: value,
                        },
                        success: function(data) {
                            $('#loader').addClass('hidden');
                            $('#addtable').html('').append(data);
                            $('#btnconf').show();
                        },
                        error: function(data) {
                            $('#loader').addClass('hidden');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: 'Gagal mencari data',
                                showCloseButton: true,
                            })
                        }
                    });
                },
                error: function(data) {
                    $('#loader').addClass('hidden');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: 'Gagal mencari data',
                        showCloseButton: true,
                    })
                }
            });

            
        }else{
            $('#addtable').html('');
            $('#btnconf').hide();
        }
    })

    $(document).on('submit', '#submit', function(e) {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnback').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });
</script>
@endsection