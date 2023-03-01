@extends('layout.layout')

@section('menu_name', 'Sales Order Maintenance')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Transaksi</a></li>
        <li class="breadcrumb-item active">Customer Order Maintenance</li>
    </ol>
@endsection

@section('content')
    <form action="{{ route('confirmsj.index') }}" method="get">

        <div class="form-group row col-md-12">
            <label for="sonumber" class="col-md-2 col-form-label text-md-right">{{ __('SO Number.') }}</label>
            <div class="col-md-4 col-lg-3">
                <select id="sonumber" class="form-control select2drop" name="sonumber" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach ($listso as $sonumbers)
                        <option value="{{ $sonumbers->id }}">{{ $sonumbers->so_nbr }}</option>
                    @endforeach
                </select>
            </div>
            <label for="sjnumber" class="col-md-2 col-form-label text-md-right">{{ __('SJ Number.') }}</label>
            <div class="col-md-4 col-lg-3">
                <select id="sjnumber" class="form-control select2drop" name="sjnumber" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach ($listsj as $sjnumbers)
                        <option value="{{ $sjnumbers->id }}">{{ $sjnumbers->sj_nbr }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="customer" class="col-md-2 col-form-label text-md-right">{{ __('Customer') }}</label>
            <div class="col-md-4 col-lg-3">
                <select id="customer" class="form-control select2drop" name="customer" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach ($listcust as $customers)
                        <option value="{{ $customers->cust_code }}">{{$customers->cust_code}} -- {{ $customers->cust_desc }}</option>
                    @endforeach
                </select>
            </div>
            <label for="truck" class="col-md-2 col-form-label text-md-right">{{ __('Truck') }}</label>
            <div class="col-md-4 col-lg-3">
                <select id="truck" class="form-control select2drop" name="truck" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach ($listtruck as $trucks)
                        <option value="{{ $trucks->id }}">{{$trucks->truck_no_polis}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="shipfrom" class="col-md-2 col-form-label text-md-right">{{ __('Ship From') }}</label>
            <div class="col-md-4 col-lg-3">
                <select id="shipfrom" class="form-control select2drop" name="shipfrom" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach ($listshipfrom as $shipfroms)
                        <option value="{{ $shipfroms->sf_code }}">{{$shipfroms->sf_code}}</option>
                    @endforeach
                </select>
            </div>
            <label for="shipto" class="col-md-2 col-form-label text-md-right">{{ __('Ship To') }}</label>
            <div class="col-md-4 col-lg-3">
                <select id="shipto" class="form-control select2drop" name="shipto" autofocus autocomplete="off">
                    <option value=""> Select Data </option>
                    @foreach ($listshipto as $shiptos)
                        <option value="{{ $shiptos->cs_shipto }}">{{$shiptos->cs_shipto}}</option>
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

    @include('transaksi.confirmsj.index-table')

@endsection


@section('scripts')

    <script type="text/javascript">
        $('.select2drop').select2({
            width: '100%',
        });

        function resetSearch() {
            $('#sonumber').val('');
            $('#sjnumber').val('');
            $('#customer').val('');
            $('#truck').val('');
            $('#shipfrom').val('');
            $('#shipto').val('');
        }

        $(document).ready(function() {
            var cur_url = window.location.href;

            let paramString = cur_url.split('?')[1];
            let queryString = new URLSearchParams(paramString);

            let sonumber = queryString.get('sonumber');
            let sjnumber = queryString.get('sjnumber');
            let customer = queryString.get('customer');
            let truck = queryString.get('truck');
            let shipfrom = queryString.get('shipfrom');
            let shipto = queryString.get('shipto');

            $('#sonumber').val(sonumber).trigger('change');
            $('#sjnumber').val(sjnumber).trigger('change');
            $('#customer').val(customer).trigger('change');
            $('#truck').val(truck).trigger('change');
            $('#shipfrom').val(shipfrom).trigger('change');
            $('#shipto').val(shipto).trigger('change');
        });

        $(document).on('click', '#btnrefresh', function() {
            resetSearch();
        });
    </script>
@endsection
