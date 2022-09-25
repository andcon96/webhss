@extends('layout.layout')

@section('menu_name','Lapor Kerusakan')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Tindakan History</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->
<form action="{{route('krhistview',$id)}}" method="get">
    <div class="form-group row col-md-12">
        <label for="s_krnbr" class="col-md-2 col-form-label text-md-right">{{ __('Kerusakan Nbr.') }}</label>
        <div class="col-md-4 col-lg-3">
            <input id="s_krnbr" type="text" class="form-control" name="s_krnbr" value="{{ $data->kr_nbr }}" autofocus autocomplete="off" readonly>
        </div>
        @if(!empty($data->getTruck))
        <label for="s_truck" class="col-md-2 col-form-label text-md-right">{{ __('Truck') }}</label>
        <div class="col-md-4 col-lg-3">
            
            <input id="s_truck" type="text" class="form-control" name="s_truck" value="{{ $data->getTruck->truck_no_polis }}" autofocus autocomplete="off" readonly>
        </div>
        @elseif(empty($data->getTruck))
        <label for="s_gandengan" class="col-md-2 col-form-label text-md-right">{{ __('Gandengan') }}</label>
        <div class="col-md-4 col-lg-3">
            
            <input id="s_gandengan" type="text" class="form-control" name="s_gandengan" value="{{ $data->getGandeng->gandeng_desc }}" autofocus autocomplete="off" readonly>
        </div>
        @endif
        
    </div>
    
    <div class="form-group row col-md-12">
        <label for="s_jeniskerusakan" class="col-md-2 col-form-label text-md-right">{{ __('Jenis Kerusakan') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="s_jeniskerusakan" class="form-control" name="s_jeniskerusakan" autofocus autocomplete="off">
                @foreach($data->getDetail as $key => $datas)  
                    <option value="{{$datas->id}}" {{$datas->id == request()->input('s_jeniskerusakan') ? 'selected' : '' }}> {{$datas->getKerusakan->kerusakan_code}} -- {{$datas->getKerusakan->kerusakan_desc}} </option>
                @endforeach
            </select>  
        </div>
        <div class="col-md-4 col-lg-3">
            <button class="btn bt-action newUser" id="btnsearch" value="Search">Search</button>
            {{-- <button class="btn bt-action newUser" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i class="fa fa-sync"></i></button> --}}
        </div>
    </div>
</form>

@include('transaksi.kerusakan.assignkrremarkshistory-table')


@endsection


@section('scripts')

<script type="text/javascript">
    $('#s_status,#s_driver').select2({
        width: '100%',
    });
    
    function resetSearch(){
        $('#s_driver').val('');
        $('#s_krnbr').val('');
    }

    $(document).ready(function(){
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let driver = queryString.get('s_driver');

        $('#s_driver').val(driver).trigger('change');
    });

    // $(document).on('click', '#btnrefresh', function(){
    //     resetSearch();
    // });

    
    $(document).on('click', '.deleteModal', function() {
        var id = $(this).data('id');
        var krnbr = $(this).data('krnbr');

        document.getElementById("temp_id").value = id;
        document.getElementById('temp_uname').text = krnbr;
    });

</script>
@endsection