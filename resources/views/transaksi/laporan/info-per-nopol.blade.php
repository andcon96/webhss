@extends('layout.layout')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Transaksi</a></li>
        <li class="breadcrumb-item active">Generate Report Sangu - Total Per Nopol</li>
    </ol>
@endsection

@section('content')
    <form action="{{ route('reportPerNopol') }}">

        <input type="hidden" name="report" value="{{ $report }}">

        <div class="form-group row col-md-12">
            <label for="datefrom" class="col-md-2 col-form-label text-md-right">{{ __('Date From') }}</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="datefrom" id="datefrom" value="{{ $datefrom }}" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="dateto" class="col-md-2 col-form-label text-md-right">{{ __('Date To') }}</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="dateto" id="dateto" value="{{ $dateto }}" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="nopol" class="col-md-2 col-form-label text-md-right">{{ __('No Polis') }}</label>
            <div class="col-md-3">
                <input type="text" class="form-control" value="{{ $nopol }}" readonly>
                <input type="hidden" name="truck" id="truckid" value="{{$nopolid}}">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="driver" class="col-md-2 col-form-label text-md-right">{{ __('Driver') }}</label>
            <div class="col-md-3">
                <select name="driver" id="driver" class="form-control" required>
                    <option value="">Select Data</option>
                    @foreach ($driver as $drivers)
                        <option value="{{$drivers->dn_driver_id}}">{{$drivers->getDriver->driver_name ?? ''}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="form-group row col-md-10 offset-md-1 mt-2">
            <table class="table table-bordered mini-table" id="dataTable">
                <thead>
                    <tr>
                        <th colspan="4" style="text-align: center">Daftar Pembayaran Cicilan</th>
                    </tr>
                    <tr>
                        <th width="8%">No.</th>
                        <th width="15%">Eff Date</th>
                        <th width="15%">Nominal</th>
                        <th width="40%">Remarks</th>
                    </tr>
                </thead>
                <tbody id="bodydata">

                </tbody>
            </table>
        </div>
        
        <div class="form-group row col-md-12">
            <label for="s_status" class="col-md-1 col-form-label text-md-right">{{ __('') }}</label>
            <div class="col-md-10">
                <a href="{{route('report.index')}}" class="btn bt-action">Back</a>
                <button class="btn bt-action" name="aksi" value="1" formtarget="_blank">Preview</button>
                <button class="btn bt-action" name="aksi" value="2" formtarget="_blank">Export Excel</button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('#driver').select2({
            width: '100%',
        });

        $(document).on('change', '#driver',function(){
            let url = "{{ route('getCicilan') }}"
            let truckid = $('#truckid').val();
            let datefrom = $('#datefrom').val();
            let dateto = $('#dateto').val();
            let driverid = $(this).find(':selected').val();
            
            $.ajax({
                url: url,
                data: {
                    truckid : truckid,
                    driverid : driverid,
                    datefrom : datefrom,
                    dateto : dateto
                },
                beforeSend: function() {
                    $('#loader').removeClass('hidden');
                },
                success: function(data) {
                    let output = '<tr><td colspan="4" style="color:red;text-align:center;">No Data</td></tr>';

                    if(data.length > 0){
                        output = '';
                        $.each(data, function(index, value){
                            output += '<tr>';
                            output += '<td>' + parseInt(index + 1) + '</td>';
                            output += '<td>' + value['hc_eff_date'] + '</td>';
                            output += '<td>' + Number(value['hc_nominal']).toLocaleString() + '</td>';
                            output += '<td>' + value['hc_remarks'] + '</td>';
                            output += '</tr>';
                        });
                    }
                    
                    $('#bodydata').html(output);
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

        $(document).on('keyup', '.harga', function() {
            letterRegex = /[^\0-9\,]/;
            var data = $(this).val();

            var newdata = data.replace(/([^0-9])/g, '');

            console.log(Number(newdata).toLocaleString('en-US'));

            $(this).val(Number(newdata).toLocaleString('en-US'));
        });
    </script>
@endsection
