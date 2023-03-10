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
                <label for="sonbr" class="col-md-2 col-form-label text-md-right">SO Number</label>
                <div class="col-md-3">
                    <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{ $data->so_nbr }}"
                        autocomplete="off" maxlength="24" readonly required autofocus>
                    <input type="hidden" name="soid" value="{{ $data->id }}">
                </div>

                <label for="conbr" class="col-md-3 col-form-label text-md-right">CO Number</label>
                <div class="col-md-3">
                    <input id="conbr" type="text" class="form-control" name="conbr"
                        value="{{ $data->getCOMaster->co_nbr }}" autocomplete="off" maxlength="24" readonly required
                        autofocus>
                </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="duedateso" class="col-md-2 col-form-label text-md-right">Due Date</label>
                <div class="col-md-3">
                    <input id="duedateso" type="text" class="form-control" name="duedateso"
                        value="{{ $data->so_due_date }}" autocomplete="off" maxlength="24" readonly required autofocus>
                </div>
                <label for="customer" class="col-md-3 col-form-label text-md-right">Customer</label>
                <div class="col-md-3">
                    <input id="customer" type="text" class="form-control" name="customer"
                        value="{{ $data->getCOMaster->co_cust_code }} - {{ $data->getCOMaster->getCustomer->cust_desc ?? '' }}"
                        autocomplete="off" maxlength="24" readonly required autofocus>
                </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="shipfrom" class="col-md-2 col-form-label text-md-right">Ship From</label>
                <div class="col-md-3">
                    <input type="text" class="form-control"
                        value="{{ $data->so_ship_from }} -- {{ $data->getShipFrom->sf_desc ?? '' }}" autocomplete="off"
                        maxlength="24" readonly required autofocus>
                    <input type="hidden" id="shipfrom" name="shipfrom" value="{{ $data->getShipFrom->id ?? null }}">
                </div>
                <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
                <div class="col-md-3">
                    <input type="text" class="form-control"
                        value="{{ $data->so_ship_to }} -- {{ $data->getShipTo->cs_shipto_name }}" autocomplete="off"
                        maxlength="24" readonly required autofocus>
                    <input type="hidden" id="shipto" name="shipto" value="{{ $data->getShipTo->id }}">
                </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="type" class="col-md-2 col-form-label text-md-right">Type</label>
                <div class="col-md-3">
                    <input id="type" type="text" class="form-control" name="type"
                        value="{{ $data->getCOMaster->co_type }}" autocomplete="off" maxlength="24" required readonly>
                </div>
                <label for="barang" class="col-md-3 col-form-label text-md-right">Barang</label>
                <div class="col-md-3">
                    <input type="hidden" name="barangid" id="barangid" value="{{ $data->getCOMaster->co_barang_id }}">
                    <input id="barang" type="text" class="form-control" name="barang"
                        value="{{ $data->getCOMaster->getBarang->barang_deskripsi ?? '' }}" readonly>
                </div>
            </div>
            <div class="form-group row col-md-12">
                <label for="kapal" class="col-md-2 col-form-label text-md-right">Kapal</label>
                <div class="col-md-3">
                    <input id="barang" name="barang" type="text" class="form-control"
                        value="{{ $data->getCOMaster->co_kapal ?? '' }}" readonly>
                </div>
                <label for="duedate" class="col-md-3 col-form-label text-md-right">Eff Date</label>
                <div class="col-md-3">
                    @php($now = \Carbon\Carbon::now())
                    @if ($now->format('H') <= 22 && $now->format('H') > 5)
                        <input id="duedate" type="text" class="form-control" name="duedate"
                            value="{{ $now->toDateString() }}" readonly>
                    @else
                        <input id="duedate" type="text" class="form-control duedate" name="duedate"
                            value="{{ $now->toDateString() }}" required>
                    @endif
                </div>
            </div>
            <div class="form-group row col-md-12">
                @include('transaksi.salesorder.suratjalan.create-table')
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
                                {{ $trucks->truck_no_polis }} -- {{ $trucks->getTipe->tt_desc ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button class="btn bt-action newUser" id='btnadd' style="margin-left: 10px; width: 40px !important"><i
                        class="fa fa-plus"></i></button>
            </div>
            <div class="form-group row col-md-12">
                <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                    <div class="float-right">
                        <a href="{{ route('salesorder.index') }}" id="btnback"
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
        $('#truck,#listsan,#bonus').select2({
            width: '100%'
        });

        var tipebarang = $('#type').val();

        $(".duedate").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        
        document.addEventListener('keydown', function(event) {
            if (event.which == 13) {
                event.preventDefault();
            }
        });

        function resetDropDownValue() {
            let filter = $('#type').val();

            $('.selectpicker option').each(function() {
                if ($(this).data('type') == filter || $(this).val() == "") {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            })

            $('.selectpicker').selectpicker('refresh');
        }

        $(document).on('change', '#truck', function() {
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
