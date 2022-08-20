@extends('layout.layout')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Transaksi</a></li>
        <li class="breadcrumb-item active">Invoice Maintenance</li>
    </ol>
@endsection

@section('content')
    <form action="{{ route('invoicemt.store') }}" id="submit" method="POST">
        @csrf
        @method('POST')
        <div class="row">
            <div class="form-group row col-md-12">
                <label for="sonbr" class="col-md-2 col-form-label text-md-right">SO Number</label>
                <div class="col-md-3">
                    <select name="sonbr" id="sonbr" class="form-control" required>
                        <option value="">Select Data</option>
                        @foreach ($list_sonbr as $sonbr)
                            <option value="{{ $sonbr->id }}">{{ $sonbr->so_nbr }}</option>
                        @endforeach
                    </select>
                </div>
                <label for="effdate" class="col-md-3 col-form-label text-md-right">Eff Date</label>
                <div class="col-md-3">
                    <input type="text" name="effdate" id="effdate" class="form-control"
                        value="{{ \Carbon\Carbon::now()->toDateString() }}">
                </div>
            </div>


            <div class="form-group row mr-2 ml-2">
                <div class="col-md-10 offset-md-1">
                    <table id="createTable" class="table order-list" style="table-layout: fixed;">
                        <thead>
                            <tr>
                                <td style="text-align: center; width:15%">Domain</td>
                                <td style="text-align: center; width:35%">Invoice QAD</td>
                                <td style="text-align: center; width:15%">Price</td>
                                <td style="text-align: center; width:15%">Due Date</td>
                                <td style="text-align: center;">Action</td>
                            </tr>
                        </thead>
                        <tbody id='detailapp'>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">
                                    <input type="button" class="btn btn-lg btn-block btn-focus" id="addrow"
                                        value="Add Row" style="background-color:#1234A5; color:white; font-size:16px" />
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="form-group row col-md-12">
                <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                    <div class="float-right">
                        <a href="{{ route('invoicemt.index') }}" id="btnback" class="btn btn-success bt-action">Back</a>
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
        $('#sonbr').select2({
            width: '100%'
        });

        $("#effdate").datepicker({
            dateFormat: 'yy-mm-dd'
        });

        $(document).on('keyup', '.nominal', function() {
            var data = $(this).val();

            var newdata = data.replace(/([^ 0-9])/g, '');

            $(this).val(Number(newdata).toLocaleString('en-US'));
        });

        $(document).on('submit', '#submit', function(e) {
            document.getElementById('btnconf').style.display = 'none';
            document.getElementById('btnback').style.display = 'none';
            document.getElementById('btnloading').style.display = '';
        });



        var counter = 1;

        function selectRefresh() {
            $('.selectpicker').selectpicker().focus();
        }

        $("#addrow").on("click", function() {
            var rowCount = $('#createTable tr').length;
            var currow = rowCount - 2;

            var lastline = parseInt($('#createTable tr:eq(' + currow + ') td:eq(0) input[type="number"]').val()) +
            1;

            if (lastline !== lastline) {
                lastline = 1;
            }

            var newRow = $("<tr>");
            var cols = "";

            cols += '<td>';
            cols +=
                '<select name="domain[]" id="domain" class="form-control domain selectpicker"style="border: 1px solid #e9ecef" name="part[]" data-size="5" data-live-search="true" required autofocus>';
            cols += '<option value="">Select Data</option>';
            @foreach ($list_domain as $domain)
                cols += '<option value="{{ $domain->domain_code }}">{{ $domain->domain_code }}</option>';
            @endforeach
            cols += '</td>';

            cols += '<td>';
            cols += '<input type="text" class="form-control ivnbr" name="ivnbr[]" required />';
            cols += '</td>';

            cols += '<td>';
            cols +=
                '<input type="text" class="form-control price" name="price[]" value="" onkeydown="return false;" style="caret-color: transparent !important;background-color:#d3d3d3;"   required/>';
            cols += '</td>';

            cols += '<td>';
            cols +=
                '<input type="text" class="form-control duedate" name="duedate[]" value="" onkeydown="return false;" style="caret-color: transparent !important;background-color:#d3d3d3;"   required/>';
            cols += '</td>';

            cols +=
                '<td data-title="Action"><input type="button" class="btncheck btn btn-info btn-focus mr-2"  value="Check"><input type="button" class="ibtnDel btn btn-danger btn-focus" value="Delete"></td>';
            cols += '</tr>'
            counter++;

            newRow.append(cols);
            $("#detailapp").append(newRow);

            selectRefresh();
        });

        $("table.order-list").on("click", ".ibtnDel", function(event) {
            var row = $(this).closest("tr");
            var line = row.find(".line").val();

            if (line == counter - 1) {
                // kalo line terakhir delete kurangin counter
                counter -= 1
            }

            $(this).closest("tr").remove();
        });

        $('table.order-list').on('click', '.btncheck', function(event) {
            event.preventDefault();
            let invoiceqad = $(this).closest('tr').find('.ivnbr').val();
            let domain = $(this).closest('tr').find('.domain :selected').val();
            let price = $(this).closest('tr').find('.price');
            let duedate = $(this).closest('tr').find('.duedate');

            if (!invoiceqad || !domain) {
                // data kosong alert error;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: 'Invoice & Domain tidak boleh kosong',
                    showCloseButton: true,
                })
            } else {
                $.ajax({
                    url: "{{ route('checkInvoice') }}",
                    data: {
                        invoiceqad: invoiceqad,
                        domain: domain
                    },
                    beforeSend: function() {
                        $('#loader').removeClass('hidden');
                    },
                    success: function(data) {
                        price.val(data[0]);
                        duedate.val(data[1]);
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            html: 'Price Updated',
                            showCloseButton: true,
                        })
                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: 'Gagal mencari data',
                            showCloseButton: true,
                        })
                    },
                    complete: function() {
                        $('#loader').addClass('hidden');
                    }
                })
            }

            console.log(invoiceqad);
            console.log(domain);
        });
    </script>
@endsection
