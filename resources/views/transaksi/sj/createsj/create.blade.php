@extends('layout.layout')

@section('menu_name', 'Sales Order Maintenance')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Transaksi</a></li>
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
                        @foreach ($listso as $listsos)
                            <option value="{{ $listsos->id }}" data-cust="{{ $listsos->getCOMaster->co_cust_code }}"
                                data-custdesc="{{ $listsos->getCOMaster->getCustomer->cust_desc }}"
                                data-shipfrom="{{ $listsos->so_ship_from }}"
                                data-shipfromdesc="{{ $listsos->getShipFrom->sf_desc ?? null }}"
                                data-shipfromid="{{ $listsos->getShipFrom->id ?? null }}"
                                data-shipto="{{ $listsos->so_ship_to }}"
                                data-shiptodesc="{{ $listsos->getShipTo->cs_shipto_name }}"
                                data-shiptoid="{{ $listsos->getShipTo->id }}"
                                data-type="{{ $listsos->getCOMaster->co_type }}"
                                data-duedate="{{ $listsos->so_due_date }}"
                                data-barang="{{ $listsos->getCOMaster->getBarang->barang_deskripsi ?? '' }}"
                                data-kapal="{{ $listsos->getCOMaster->co_kapal }}"
                                data-conbr="{{ $listsos->getCOMaster->co_nbr }}">{{ $listsos->so_nbr }} --
                                {{ $listsos->getCOMaster->getCustomer->cust_desc ?? '' }}</option>
                        @endforeach
                    </select>
                </div>

                <label for="conbr" class="col-md-3 col-form-label text-md-right">CO Number</label>
                <div class="col-md-3">
                    <input id="conbr" type="text" class="form-control" name="conbr" value=""
                        autocomplete="off" maxlength="24" readonly required autofocus>
                </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="duedateso" class="col-md-2 col-form-label text-md-right">Due Date</label>
                <div class="col-md-3">
                    <input id="duedateso" type="text" class="form-control" name="duedateso" value=""
                        autocomplete="off" maxlength="24" readonly required autofocus>
                </div>
                <label for="customer" class="col-md-3 col-form-label text-md-right">Customer</label>
                <div class="col-md-3">
                    <input id="customer" type="text" class="form-control" name="customer" value=""
                        autocomplete="off" maxlength="24" readonly required autofocus>
                </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="shipfrom" class="col-md-2 col-form-label text-md-right">Ship From</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="" id="shipfrom" name="shipfrom"
                        autocomplete="off" maxlength="24" readonly required autofocus>
                    <input type="hidden" id="shipfromid" name="shipfromid">
                </div>
                <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="" id="shipto" name="shipto"
                        autocomplete="off" maxlength="24" readonly required autofocus>
                    <input type="hidden" id="shiptoid" name="shiptoid">
                </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="type" class="col-md-2 col-form-label text-md-right">Type</label>
                <div class="col-md-3">
                    <input id="type" type="text" class="form-control" name="type" value=""
                        autocomplete="off" maxlength="24" required readonly>
                </div>
                <label for="barang" class="col-md-3 col-form-label text-md-right">Barang</label>
                <div class="col-md-3">
                    <input id="barang" type="text" class="form-control" name="barang" value=""
                        autocomplete="off" readonly>
                </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="kapal" class="col-md-2 col-form-label text-md-right">Kapal</label>
                <div class="col-md-3">
                    <input id="kapal" type="text" class="form-control" name="kapal" value="" readonly>
                </div>
                <label for="duedate" class="col-md-3 col-form-label text-md-right">Eff Date</label>
                <div class="col-md-3">
                    @php($now = \Carbon\Carbon::now())
                    @if ($now->format('H') >= 22 && $now->format('H') < 5)
                        <input id="duedate" type="text" class="form-control" name="duedate"
                            value="{{ $now->toDateString() }}" readonly>
                    @else
                        <input id="duedate" type="text" class="form-control duedate" name="duedate"
                            value="{{ $now->toDateString() }}" required>
                    @endif
                </div>
            </div>
            <div class="form-group row col-md-12">
                @include('transaksi.sj.createsj.create-table')
            </div>
            <div class="form-group row col-md-12">
                @include('transaksi.sj.createsj.listtruck-table')
            </div>
            <div class="form-group row col-md-12">
                <label for="truck" class="col-md-3 col-form-label text-md-right">Truck</label>
                <div class="col-md-3">
                    <select id="truck" class="form-control" name="truck" required autofocus autocomplete="off">
                        <option value=""> Select Data </option>
                        @foreach ($truck as $trucks)
                            <option value="{{ $trucks->id }}" data-typetruck="{{ $trucks->truck_tipe_id }}"
                                data-pengurus="{{ $trucks->getUserPengurus->name ?? '' }}"
                                data-nopol="{{ $trucks->truck_no_polis }}" data-domain="{{ $trucks->truck_domain }}">
                                {{ $trucks->truck_no_polis }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button class="btn bt-action newUser" id='btnadd' style="margin-left: 10px; width: 40px !important"
                    disabled><i class="fa fa-plus"></i></button>
            </div>
            <div class="form-group row col-md-12">
                <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                    <div class="float-right">
                        <a href="{{ route('suratjalan.index') }}" id="btnback"
                            class="btn btn-success bt-action">Back</a>
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
        $('#truck,#soid,#listsan').select2({
            width: '100%'
        });

        $(".duedate").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $('#btnconf').hide();

        $(document).on('change', '#soid', function() {
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
            var barang = $(this).find(':selected').data('barang');
            var kapal = $(this).find(':selected').data('kapal');

            $('#customer').val(cust + ' - ' + custdesc);
            $('#shipfrom').val(shipfrom + ' - ' + shipfromdesc);
            $('#shipto').val(shipto + ' - ' + shiptodesc);
            $('#type').val(type);
            $('#duedateso').val(duedate);
            $('#shipfromid').val(shipfromid);
            $('#shiptoid').val(shiptoid);
            $('#conbr').val(conbr);
            $('#barang').val(barang);
            $('#kapal').val(kapal);

            let url = "{{ route('getDetailSJSO', ':soid') }}"
            url = url.replace(':soid', soid);

            $.ajax({
                url: url,
                beforeSend: function() {
                    $('#loader').removeClass('hidden');
                },
                success: function(data) {
                    $('#btnconf').show();
                    $('#addtable').html('').append(data);
                    $('.tonase').css('display', 'none');
                    $('.pricetot').removeClass('col-md-3');
                    $('.pricetot').addClass('col-md-2');
                    $('#container').css('display', '');
                    $('#btnadd').prop('disabled', false);
                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: 'Gagal mencari data',
                        showCloseButton: true,
                    })
                    $('#addtable').html('');
                    $('#btnconf').hide();
                },
                complete: function() {
                    $('#loader').addClass('hidden');
                }
            })
        });

        $(document).on('click', '#btnadd', function(e) {
            e.preventDefault();

            let truck = $('#truck').val();
            if (!truck) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: 'Silahkan pilih truck terlebih dahulu',
                    showCloseButton: true,
                });
                return;
            }

            let nopol = $('#truck').find(':selected').data('nopol');
            let type = $('#type').val();

            let qty = type == "BERAT" ? "25000" : "1";

            let output = '';
            output += '<tr>';
            output += '<td>' + nopol + '<input type="hidden" name="idtruck[]" value="' + truck + '"></td>';
            output += '<td><input type="number" class="form-control qtyord" value="' + qty +
                '" name="qtyord[]" "></td>';
            output += '<td><input type="text" class="form-control sj" name="sj[]"></td>';
            output +=
                '<td><button class="btn btn-danger delrow" style="margin-left: 10px; width: 40px !important"><i class="fa fa-trash"></i></button></td>';
            output += '</tr>';

            $('#listtruck').append(output);

        });

        $(document).on('click', '.delrow', function(e) {
            $(this).closest("tr").remove();
        });

        $(document).on('click', '#btnconf', function(e) {
            e.preventDefault();

            Swal.fire({
                title: "Submit Data ?",
                text: "Data akan dicancel dan tidak bisa diulang",
                type: "warning",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Confirm",
                closeOnConfirm: false
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    document.getElementById('btnconf').style.display = 'none';
                    document.getElementById('btnback').style.display = 'none';
                    document.getElementById('btnloading').style.display = '';

                    $('#submit').submit();
                }
            })
        });
    </script>
@endsection
