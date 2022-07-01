@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Generate Report Sangu</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->
<form action="{{route('reportSangu')}}" method="get">
    <div class="form-group row col-md-12">
        <label for="datefrom" class="col-md-2 col-form-label text-md-right">{{ __('Date From') }}</label>
        <div class="col-md-4 col-lg-3">
            <input id="datefrom" type="text" class="form-control" name="datefrom" value="{{\Carbon\Carbon::now()->toDateString()  }}" autocomplete="off">
        </div>
        <label for="dateto" class="col-md-3 col-form-label text-md-right">{{ __('Date To') }}</label>
        <div class="col-md-4 col-lg-3">
            <input id="dateto" type="text" class="form-control" name="dateto" value="{{\Carbon\Carbon::now()->toDateString() }}" autocomplete="off">
        </div>
    </div>
    <div class="form-group row col-md-12">
        <label for="report" class="col-md-2 col-form-label text-md-right">{{ __('Tipe Report') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="report" class="form-control" name="report" autofocus autocomplete="off" required>
                <option value=""> Select Data </option>
                <option value="1"> Report Bulan by Date </option>
                <option value="2"> Report Bulan by Truck by Date </option>
            </select>
        </div>
        <label for="truck" class="col-md-3 col-form-label text-md-right">{{ __('Truck') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="truck" class="form-control" name="truck" autofocus autocomplete="off" required>
                <option value=""> Select Data </option>
                @foreach($truck as $trucks)
                <option value="{{$trucks->id}}">{{$trucks->truck_no_polis}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row col-md-12">
        <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('') }}</label>
        <div class="col-md-4 col-lg-3">
            <button class="btn bt-action newUser" name="aksi" value="1" style="margin-left: 10px; width: 40px !important">
                <i class="fas fa-file-excel"></i>
            </button>
            <button class="btn bt-action newUser" name="aksi" value="2" style="margin-left: 10px; width: 40px !important">
                <i class="fas fa-file-pdf"></i>
            </button>
        </div>
    </div>

</form>

@endsection


@section('scripts')

<script type="text/javascript">
    $('#truck,#report').select2({
        width: '100%',
    });
    
    $("#datefrom, #dateto").datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $('#truck').prop('disabled',true);

    $('#report').on('change', function(){
        let val = $(this).val();
        
        if(val == 2){
            $('#truck').prop('disabled',false);
            $('#truck').prop('required',true);
        }else{
            $('#truck').prop('disabled',true);;
            $('#truck').prop('required',false);
        }
    });

</script>
@endsection