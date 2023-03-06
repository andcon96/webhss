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
                <option value="2"> Report Totalan Supir Loosing</option>
                <option value="3"> Report Rincian Sangu Loosing HSST</option>
                <option value="4"> Report Total Supir Loosing HSS Trailer</option>
                <option value="5"> Report Container By Tipe Truck </option>
                <option value="6"> Report Tambahan Biaya</option>
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
        <label for="domain" class="col-md-2 col-form-label text-md-right">{{ __('Domain') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="domain" class="form-control" name="domain" autofocus autocomplete="off" required>
                <option value=""> Select Data </option>
                @foreach($domain as $domains)
                <option value="{{$domains->domain_code}}">{{$domains->domain_code}}</option>
                @endforeach
            </select>
        </div>
        <label for="subdom" class="col-md-3 col-form-label text-md-right">{{ __('Sub Domain') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="subdom" class="form-control" name="subdom" autofocus autocomplete="off">
                <option value=""> Select Data </option>
                @foreach($subdomain as $subdomains)
                <option value="{{$subdomains->truck_sub_domain}}">{{$subdomains->truck_sub_domain}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row col-md-12">
        <label for="tipetruck" class="col-md-2 col-form-label text-md-right">{{ __('Tipe Truck') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="tipetruck" class="form-control" name="tipetruck" autofocus autocomplete="off" required>
                <option value=""> Select Data </option>
                @foreach($tipetruck as $tipetrucks)
                <option value="{{$tipetrucks->id}}">{{$tipetrucks->tt_code}} -- {{$tipetrucks->tt_desc}}</option>
                @endforeach
            </select>
        </div>
        <label for="tipe" class="col-md-3 col-md-label text-md-right">Tipe</label>
        <div class="col-md-4 col-lg-3">
            <select name="tipe" id="tipe" class="form-control" required autocomplete="off">
                <option value="">Select Data</option>
                <option value="1">Loosing</option>
                <option value="2">Container</option>
            </select>
        </div>
    </div>
    <div class="form-group row col-md-12">
        <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('') }}</label>
        <div class="col-md-4 col-lg-3">
            <button class="btn bt-action newUser" id="btnexcel" name="aksi" value="1" style="margin-left: 10px; width: 40px !important">
                <i class="fas fa-file-excel"></i>
            </button>
            <button class="btn bt-action newUser" id="btnpdf" name="aksi" formtarget="_blank" value="2" style="margin-left: 10px; width: 40px !important">
                <i class="fas fa-file-pdf"></i>
            </button>
        </div>
    </div>

</form>

@endsection


@section('scripts')

<script type="text/javascript">
    $('#truck,#report,#domain,#tipetruck,#tipe,#subdom').select2({
        width: '100%',
    });

    $("#dateto").datepicker({
        dateFormat: 'yy-mm-dd',
    });

    $('#datefrom').datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function(date){
            let newdate = new Date(date);
            newdate.setDate(newdate.getDate() + 14);
        }
    });
    
    $('#truck, #domain, #tipetruck, #tipe, #subdom').prop('disabled',true);

    $('#report').on('change', function(){
        let val = $(this).val();
        
        if(val == 2){
            $('#truck').prop('disabled',false);
            $('#truck').prop('required',true);
            
            $('#domain,#tipetruck,#tipe,#subdom').prop('disabled',true);
            $('#domain,#tipetruck,#tipe').prop('required',false);
            $('#btnpdf').prop('disabled',false);
            $('#btnexcel').prop('disabled',true);
            
        }else if(val == 4){
            $('#truck,#tipetruck,#tipe').prop('disabled',true);
            $('#truck,#tipetruck,#tipe').prop('required',false);

            $('#tipe,#domain').prop('disabled',false);
            $('#tipe,#domain').prop('required',true);
            $('#btnexcel').prop('disabled',false);

        }else if(val == 5){
            $('#truck,#domain,#tipe,#subdom').prop('disabled',true);
            $('#truck,#domain,#tipe').prop('required',false);
            
            $('#tipetruck').prop('disabled',false);
            $('#tipetruck').prop('required',true);

            $('#btnpdf').prop('disabled',true);
            $('#btnexcel').prop('disabled',false);
        }else if(val == 6){
            $('#truck,#domain,#tipetruck,#subdom').prop('disabled',true);
            $('#truck,#domain,#tipetruck').prop('required',false);
            $('#btnpdf').prop('disabled',true);
            $('#btnexcel').prop('disabled',false);
            $('#tipe').prop('disabled',false);
            $('#tipe').prop('required',true);
        }else{
            $('#truck,#tipetruck,#tipe').prop('disabled',true);
            $('#truck,#tipetruck,#tipe').prop('required',false);

            $('#domain').prop('disabled',false);
            $('#domain').prop('required',true);
            $('#btnpdf').prop('disabled',false);
            $('#btnexcel').prop('disabled',false);
        }
    });

    $('#domain').on('change', function(){
        let data = $(this).val();
        if(data == 'SPJS'){
            $('#subdom').prop('disabled',false);
        }else{
            $('#subdom').prop('disabled',true);
        }
    });

</script>
@endsection