@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Invoice - Edit {{$data->im_nbr}}</li>
</ol>
@endsection

@section('content')
<form action="{{ route('invoicemt.update',$data->id) }}" id="submit" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <input type="hidden" name="idmaster" value="{{$data->id}}">
        <div class="form-group row col-md-12">
            <label for="imnbr" class="col-md-2 col-form-label text-md-right">Invoice Nbr</label>
            <div class="col-md-3">
                <input id="imnbr" type="text" class="form-control" name="imnbr" value="{{$data->im_nbr}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>

        <div class="form-group row col-md-12">
            @include('transaksi.invoice.edit-table')
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
    $('#editTable').on('click', '.btncheck', function(event) {
        event.preventDefault();
        let invoiceqad = $(this).closest('tr').find('.invnbr').val();
        let domain = $(this).closest('tr').find('.domain').val();
        let price = $(this).closest('tr').find('.total');
        let duedate = $(this).closest('tr').find('.duedate');

        if(domain.length === 0){
            domain = $(this).closest('tr').find('.domain :selected').val();
        }

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
    });

    function selectRefresh() {
        $('.selectpicker').selectpicker().focus();
    }
    
    $("#addrow").on("click", function() {
        var rowCount = $('#editTable tr').length;
        var currow = rowCount - 2;

        var lastline = parseInt($('#editTable tr:eq(' + currow + ') td:eq(0) input[type="number"]').val()) +
        1;

        if (lastline !== lastline) {
            lastline = 1;
        }

        var newRow = $("<tr>");
        var cols = "";
        
        cols += '<td></td>'
        cols += '<td>';
        cols +=
            '<select name="domain[]" id="domain" class="form-control domain selectpicker"style="border: 1px solid #e9ecef" name="part[]" data-size="5" data-live-search="true" required autofocus>';
        cols += '<option value="">Select Data</option>';
        @foreach ($listdomain as $domain)
            cols += '<option value="{{ $domain->domain_code }}">{{ $domain->domain_code }}</option>';
        @endforeach
        cols += '</select>'
        cols += '</td>';

        cols += '<td>';
        cols += '<input type="hidden" class="form-control" name="">'
        cols += '<input type="text" class="form-control invnbr" name="invnbr[]" required />';
        cols += '</td>';

        cols += '<td>';
        cols +=
            '<input type="text" class="form-control duedate" name="duedate[]" value="" onkeydown="return false;" style="caret-color: transparent !important;background-color:#d3d3d3;"   required/>';
        cols += '</td>';

        cols += '<td>';
        cols +=
            '<input type="text" class="form-control total" name="total[]" value="" onkeydown="return false;" style="caret-color: transparent !important;background-color:#d3d3d3;"   required/>';
        cols += '</td>';

        cols +=
            '<td data-title="Action"><input type="hidden" name="iddetail[]" value=""><input type="button" class="btncheck btn btn-info btn-focus mr-2"  value="Check"></td>';
        
        cols += '<td><input type="hidden" name="operation[]" value="A"><input type="button" class="ibtnDel btn btn-danger btn-focus" value="Delete"></td>';

        cols += '</tr>';


        newRow.append(cols);
        $("#edittable").append(newRow);

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
    
    $("table.edittable").on("click", ".ibtnDel", function(event) {
        var row = $(this).closest("tr");
        var line = row.find(".line").val();

        $(this).closest("tr").remove();
    });
</script>
@endsection