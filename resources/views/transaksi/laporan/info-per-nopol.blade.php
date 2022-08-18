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
            <label for="nopol" class="col-md-2 col-form-label text-md-right">{{ __('No Polis') }}</label>
            <div class="col-md-3">
                <input type="text" class="form-control" value="{{ $nopol }}" readonly>
                <input type="hidden" name="truck" value="{{$nopolid}}">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="datefrom" class="col-md-2 col-form-label text-md-right">{{ __('Date From') }}</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="datefrom" value="{{ $datefrom }}" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="dateto" class="col-md-2 col-form-label text-md-right">{{ __('Date To') }}</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="dateto" value="{{ $dateto }}" readonly>
            </div>
        </div>

        <div class="form-group row col-md-12">
            <label for="tabungan" class="col-md-2 col-form-label text-md-right">{{ __('Tabungan') }}</label>
            <div class="col-md-3">
                <input id="tabungan" type="text" class="form-control harga" name="tabungan" autocomplete="off"
                    value="0" autofocus required>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="cicilan" class="col-md-2 col-form-label text-md-right">{{ __('Cicilan') }}</label>
            <div class="col-md-3">
                <input id="cicilan" type="text" class="form-control harga" name="cicilan" autocomplete="off"
                    value="0" autofocus required>
            </div>
        </div>

        <div class="form-group row col-md-12">
            <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('') }}</label>
            <div class="col-md-10">
                {{-- <a href="{{route('reportPerNopol')}}" class="btn bt-action newUser" target="_blank">Preview</a> --}}
                <a href="{{route('report.index')}}" class="btn bt-action">Back</a>
                <button class="btn bt-action" formtarget="_blank">Preview</button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).on('keyup', '.harga', function() {
            letterRegex = /[^\0-9\,]/;
            var data = $(this).val();

            var newdata = data.replace(/([^0-9])/g, '');

            console.log(Number(newdata).toLocaleString('en-US'));

            $(this).val(Number(newdata).toLocaleString('en-US'));
        });
    </script>
@endsection
