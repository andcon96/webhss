@extends('layout.layout')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Transaksi</a></li>
        <li class="breadcrumb-item active">Generate Report Tonase Rill & Harga Rill</li>
    </ol>
@endsection

@section('content')
    <form action="{{ route('reportTonaseRill') }}" id="createfile">
        <div class="form-group row col-md-12">
            <label for="listso" class="col-md-2 col-form-label text-md-right">{{ __('SO Number') }}</label>
            <div class="col-md-3">
                <select name="listso" id="listso" class="form-control select2data">
                    <option value="">Select Data</option>
                    @foreach ($listso as $listsos)
                        <option value="{{ $listsos->id }}">{{ $listsos->so_nbr }}</option>
                    @endforeach
                </select>
            </div>
            <label for="listspk" class="col-md-3 col-form-label text-md-right">{{ __('SJ Number') }}</label>
            <div class="col-md-3">
                <select name="listspk" id="listspk" class="form-control select2data">
                    <option value="">Select Data</option>
                    @foreach ($listspk as $listspks)
                        <option value="{{ $listspks->id }}">{{ $listspks->sj_nbr }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="listcustomer" class="col-md-2 col-form-label text-md-right">{{ __('Customer') }}</label>
            <div class="col-md-3">
                <select name="listcustomer" id="listcustomer" class="form-control select2data">
                    <option value="">Select Data</option>
                    @foreach ($listcustomer as $listcustomers)
                        <option value="{{ $listcustomers->id }}">{{ $listcustomers->cust_code }} --
                            {{ $listcustomers->cust_desc }}</option>
                    @endforeach
                </select>
            </div>
            <label for="listtruck" class="col-md-3 col-form-label text-md-right">{{ __('Trucks') }}</label>
            <div class="col-md-3">
                <select name="listtruck" id="listtruck" class="form-control select2data">
                    <option value="">Select Data</option>
                    @foreach ($listtruck as $listtrucks)
                        <option value="{{ $listtrucks->id }}">{{ $listtrucks->truck_no_polis }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="listshipfrom" class="col-md-2 col-form-label text-md-right">{{ __('Ship From') }}</label>
            <div class="col-md-3">
                <select name="listshipfrom" id="listshipfrom" class="form-control select2data">
                    <option value="">Select Data</option>
                    @foreach ($listshipfrom as $listshipfroms)
                        <option value="{{ $listshipfroms->id }}">{{ $listshipfroms->sf_code }} --
                            {{ $listshipfroms->sf_desc }}</option>
                    @endforeach
                </select>
            </div>
            <label for="listshipto" class="col-md-3 col-form-label text-md-right">{{ __('Ship To') }}</label>
            <div class="col-md-3">
                <select name="listshipto" id="listshipto" class="form-control select2data">
                    <option value="">Select Data</option>
                    @foreach ($listshipto as $listshiptos)
                        <option value="{{ $listshiptos->id }}">{{ $listshiptos->cs_shipto }} --
                            {{ $listshiptos->cs_shipto_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row col-md-12 mt-4">
            <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('') }}</label>
            <div class="col-md-10">
                <a href="{{ route('report.index') }}" style="width: 40px !important"
                    class="btn bt-action"><i class="fa fa-arrow-left"></i></a>
                <button class="btn bt-action" style="margin-left: 10px; width: 40px !important" name="aksi"
                    value="2" formtarget="_blank"><i class="fa fa-file-excel"></i></button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('.select2data').select2({
            width: '100%',
        });

        $(".dateclass").datepicker({
            dateFormat: 'yy-mm-dd'
        });

        $(document).on('submit', '#createfile', function(e) {
            let so = $('#listso').val();
            let spk = $('#listspk').val();
            let cust = $('#listcustomer').val();
            let truck = $('#listtruck').val();
            let shipto = $('#listshipto').val();
            let shipfrom = $('#listshipfrom').val();
            if (!so && !spk && !cust && !truck && !shipto && !shipfrom) {
                e.preventDefault();
                swal.fire({
                    title: 'Warning',
                    text: 'Salah satu data harus diisi',
                    type: 'warning',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }
        })

        $(document).on('keyup', '.harga', function() {
            letterRegex = /[^\0-9\,]/;
            var data = $(this).val();

            var newdata = data.replace(/([^0-9])/g, '');

            console.log(Number(newdata).toLocaleString('en-US'));

            $(this).val(Number(newdata).toLocaleString('en-US'));
        });
    </script>
@endsection
