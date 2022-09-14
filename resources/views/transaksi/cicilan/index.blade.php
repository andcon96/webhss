@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Cicilan Maintenance</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->
<div class="col-md-12 mb-3">
    <a href="{{route('cicilanmt.create') }}" class="btn btn-info bt-action">Create Cicilan</a>
</div>
<form action="{{route('cicilanmt.index')}}" method="get">
    <div class="form-group row col-md-12">
        <label for="truck" class="col-md-2 col-form-label text-md-right">{{ __('Truck') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="truck" class="form-control" name="truck" autofocus autocomplete="off">
                <option value=""> Select Data </option>
                @foreach($listtruck as $trucks)
                <option value="{{$trucks->id}}">{{$trucks->truck_no_polis}}</option>
                @endforeach
            </select>
        </div>
        <label for="driver" class="col-md-2 col-form-label text-md-right">{{ __('Driver') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="driver" class="form-control" name="driver" autofocus autocomplete="off">
                <option value=""> Select Data </option>
                @foreach($listdriver as $drivers)
                <option value="{{$drivers->id}}">{{$drivers->driver_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row col-md-12">
        <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('') }}</label>
        <div class="col-md-4 col-lg-3">
            <button class="btn bt-action newUser" id="btnsearch" value="Search">Search</button>
            <button class="btn bt-action newUser" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i class="fa fa-sync"></i></button>
        </div>
    </div>

</form>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Delete Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{route('cicilanmt.destroy', 'role')}}" method="post">

                {{ method_field('delete') }}
                {{ csrf_field() }}

                <div class="modal-body">

                    <input type="hidden" name="_method" value="delete">

                    <input type="hidden" name="temp_id" id="temp_id" value="">

                    <div class="container">
                        <div class="row">
                            Konfirmasi untuk menghapus data, Data yang dihapus tidak bisa dikembalikan.
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info bt-action" id="d_btnclose" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success bt-action" id="d_btnconf">Save</button>
                    <button type="button" class="btn bt-action" id="d_btnloading" style="display:none">
                        <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Detail Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            
            <div class="modal-body">

                <div class="container">
                    <div class="row col-md-12">
                        <label for="dettruck" class="col-md-3 col-form-label text-md-right">Truck</label>
                        <div class="col-md-5">
                            <input id="dettruck" type="text" class="form-control" name="dettruck" value=""
                                autocomplete="off" maxlength="24" readonly required autofocus>
                        </div>
                    </div>
                    <div class="row col-md-12 mt-3">
                        <label for="detdriver" class="col-md-3 col-form-label text-md-right">Driver</label>
                        <div class="col-md-5">
                            <input id="detdriver" type="text" class="form-control" name="detdriver" value=""
                                autocomplete="off" maxlength="24" readonly required autofocus>
                        </div>
                    </div>
                    <div class="row col-md-12 mt-3">
                        <label for="totnom" class="col-md-3 col-form-label text-md-right">Total Nominal</label>
                        <div class="col-md-5">
                            <input id="totnom" type="text" class="form-control" name="totnom" value=""
                                autocomplete="off" maxlength="24" readonly required autofocus>
                        </div>
                    </div>
                    <div class="row col-md-12 mt-3">
                        <label for="totpaid" class="col-md-3 col-form-label text-md-right">Total Paid</label>
                        <div class="col-md-5">
                            <input id="totpaid" type="text" class="form-control" name="totpaid" value=""
                                autocomplete="off" maxlength="24" readonly required autofocus>
                        </div>
                    </div>
                    <div class="row table-responsive col-lg-12 col-md-12 mt-3">
                        <table class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:8%">No.</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Nominal Bayar</th>
                                    <th>Active</th>
                                    <th style="width:40%">Remark</th>
                                </tr>
                            </thead>
                            <tbody id="bodyview">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-info bt-action" id="v_btnclose" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@include('transaksi.cicilan.index-table')

@endsection


@section('scripts')

<script type="text/javascript">
    $('#truck, #driver').select2({
        width: '100%',
    });
    
    function resetSearch(){
        $('#driver').val('');
        $('#truck').val('');
    }

    $(document).on('click', '#btnrefresh', function(){
        resetSearch();
    });

    $(document).ready(function(){
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let truck = queryString.get('truck');
        let driver = queryString.get('driver');
        

        $('#truck').val(truck).trigger('change');
        $('#driver').val(driver).trigger('change');
    });

    $(document).on('click', '.deletemodal', function(e){
        let id = $(this).data('id');

        $('#temp_id').val(id);
    });

    $(document).on('click', '.viewmodal', function(e){
        let truck = $(this).data('truck');
        let driver = $(this).data('driver');
        let detail = $(this).data('detail');
        let nominal = $(this).data('total');

        let output = ''; 
        let total = 0;
        $.each(detail, function(index, value){
            output += '<tr>';
            output += '<td>' + parseInt(index + 1)  + '</td>';
            output += '<td>' + value['hc_eff_date'] + '</td>';
            output += '<td>' + Number(value['hc_nominal']).toLocaleString('en-US') + '</td><td>';
            output += value['hc_is_active'] == 1 ? 'Active' : 'Not Active' + '</td>';
            output += '<td>' + value['hc_remarks'] + '</td>';
            output += '</tr>';

            if (value['hc_is_active'] == 1){
                total += parseFloat(value['hc_nominal']);
            }
        });

        $('#bodyview').html('').append(output);
        $('#totnom').val(nominal);
        $('#dettruck').val(truck);
        $('#detdriver').val(driver);
        $('#totpaid').val(Number(total).toLocaleString('en-US'))

    })

</script>
@endsection