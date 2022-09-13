@extends('layout.layout')

@section('menu_name', 'Rute Maintenance')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Transaksi</a></li>
        <li class="breadcrumb-item active">Pembayaran Cicilan Maintenance</li>
    </ol>
@endsection

@section('content')

    <form action="{{ route('bayarcicilanmt.index') }}" method="get">
        <div class="form-group row col-md-12">
            <label for="truck" class="col-md-2 col-form-label text-md-right">{{ __('Truck') }}</label>
            <div class="col-md-4 col-lg-3">
                <select id="truck" class="form-control" name="truck" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach ($listtruck as $trucks)
                        <option value="{{ $trucks->id }}">{{ $trucks->truck_no_polis }}</option>
                    @endforeach
                </select>
            </div>
            <label for="driver" class="col-md-2 col-form-label text-md-right">{{ __('Driver') }}</label>
            <div class="col-md-4 col-lg-3">
                <select id="driver" class="form-control" name="driver" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach ($listdriver as $drivers)
                        <option value="{{ $drivers->id }}">{{ $drivers->driver_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('') }}</label>
            <div class="col-md-4 col-lg-3">
                <button class="btn bt-action newUser" id="btnsearch" value="Search">Search</button>
                <button class="btn bt-action newUser" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i
                        class="fa fa-sync"></i></button>
            </div>
        </div>

    </form>

    @include('transaksi.cicilan-bayar.index-table')
    
@endsection


@section('scripts')

    <script type="text/javascript">
        $('#truck, #driver').select2({
            width: '100%',
        });

        function resetSearch() {
            $('#driver').val('');
            $('#truck').val('');
        }

        $(document).on('click', '#btnrefresh', function() {
            resetSearch();
        });

        $(document).ready(function() {
            var cur_url = window.location.href;

            let paramString = cur_url.split('?')[1];
            let queryString = new URLSearchParams(paramString);

            let truck = queryString.get('truck');
            let driver = queryString.get('driver');


            $('#truck').val(truck).trigger('change');
            $('#driver').val(driver).trigger('change');
        });
    </script>
@endsection
