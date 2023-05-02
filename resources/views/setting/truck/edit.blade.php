@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Edit Truck {{$data->truck_no_polis}}</li>
</ol>
@endsection

@section('content')
<div class="table-responsive col-lg-12 col-md-12">
    <form action="{{route('truckmaint.update',$data->id)}}" method="post" id="submit">
        {{ method_field('put') }}
        {{ csrf_field() }}
        <input type="hidden" id="isactive" name='isactive' value={{$data->truck_is_active}}>
        <input type="hidden" name="prevurl" value="{{url()->previous()}}">
        <div class="modal-body">
            <div class="form-group row">
                <label for="domain" class="col-md-3 col-form-label text-md-right">{{ __('Domain') }}</label>
                <div class="col-md-7">
                    <select id="domain" class="form-control domain" name="domain" autofocus required autocomplete="off" readonly>
                        @foreach($domain as $dm)
                            <option value="{{$dm->domain_code}}" {{$dm->domain_code == $data->truck_domain ? 'Selected' : ''}}>{{$dm->domain_code}} -- {{$dm->domain_desc}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="subdomain" class="col-md-3 col-form-label text-md-right">{{ __('Sub Domain') }}</label>
                <div class="col-md-7">
                    <select id="subdomain" class="form-control subdomain" name="subdomain" autofocus autocomplete="off">
                        <option value="">Select Data</option>
                        @foreach($subdomain as $sd)
                            <option value="{{$sd->sd_sub_domain_code}}" {{$sd->sd_sub_domain_code == $data->truck_sub_domain ? 'Selected' : ''}}>{{$sd->sd_sub_domain_code}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="nopol" class="col-md-3 col-form-label text-md-right">{{ __('No Polis') }}</label>
                <div class="col-md-7">
                    <input id="nopol" type="text" class="form-control" autocomplete="off" name="nopol" value="{{$data->truck_no_polis}}" {{$data->truck_is_active == 0 ? 'readonly' : 'required'}}>
                </div>
            </div>
            <div class="form-group row">
                <label for="driver" class="col-md-3 col-form-label text-md-right">{{ __('Driver') }}</label>
                <div class="col-md-7">
                    <select name="driver" id="driver" class="form-control" {{$data->truck_is_active == 0 ? 'readonly' : ''}}> 
                        <option selected disabled></option>
                        @foreach ($user as $driver)
                            <option value="{{$driver->id}}" {{$driver->id == $data->truck_user_id ? 'Selected' : ''}} >{{$driver->username}} - {{$driver->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="pengurus" class="col-md-3 col-form-label text-md-right">{{ __('Pengurus') }}</label>
                <div class="col-md-7">
                    <select name="pengurus" id="pengurus" class="form-control" {{$data->truck_is_active == 0 ? 'readonly' : ''}}> 
                        <option selected disabled></option>
                        @foreach ($user as $pengurus)
                            <option value="{{$pengurus->id}}" {{$pengurus->id == $data->truck_pengurus_id ? 'Selected' : ''}} >{{$pengurus->username}} - {{$pengurus->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="tipetruck" class="col-md-3 col-form-label text-md-right">{{ __('Tipe Truck') }}</label>
                <div class="col-md-7">
                    <select name="tipetruck" id="tipetruck" class="form-control" {{$data->truck_is_active == 0 ? 'readonly' : ''}}> 
                        @foreach ($tipetruck as $tt)
                            <option value="{{$tt->id}}" {{$tt->id == $data->truck_tipe_id ? 'Selected' : ''}} >{{$tt->tt_code}} - {{$tt->tt_desc}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row" style="display:{{$data->truck_is_active == 0 ? '' : 'none'}}">
                <label for="newnopol" class="col-md-3 col-form-label text-md-right">{{ __('No Polis Baru') }}</label>
                <div class="col-md-7">
                    <input id="newnopol" type="text" class="form-control" autocomplete="off" name="newnopol" value="{{$data->new_truck_note}}" >
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <a href="{{ route('truckmaint.index') }}" id="btnback" class="btn btn-success bt-action">Back</a>
            <button type="submit" class="btn btn-success bt-action" id="btnconf">Save</button>
            <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')

<script type="text/javascript">
    $(document).ready(function() {
        var qxtend = document.getElementById('qxenable').value;

        if (qxtend == "1") {
            document.getElementById("cb_qxenable").checked = true;
            document.getElementById("qxurl").readOnly = false;
            document.getElementById("qxpath").readOnly = false;
        } else {
            document.getElementById("cb_qxenable").checked = false;
            document.getElementById("qxurl").readOnly = true;
            document.getElementById("qxpath").readOnly = true;
        }
    });
    
    if($('#isactive').val() == 0){
        
        $('#tipetruck,#domain,#driver,#pengurus').select2({
            placeholder: 'Pilih Data',
            allowClear: true,
            readOnly : true,
            disabled: true
        });

        $('.subdomain').select2({disabled: true, readOnly: true});
    }
    else{
        $('#tipetruck,#domain,#driver,#pengurus').select2({
        placeholder: 'Pilih Data',
        allowClear: true
    });
        $('.subdomain').select2({});
    }

    
    $('#submit').on("submit", function() {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnback').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });
</script>
@endsection