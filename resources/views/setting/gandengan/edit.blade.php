@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Edit Gandengan {{$data->gandeng_code}}</li>
</ol>
@endsection

@section('content')
<div class="table-responsive col-lg-12 col-md-12">
    <form action="{{route('gandengan.update',$data->id)}}" method="post" id="submit">
        {{ method_field('put') }}
        {{ csrf_field() }}
        <input type="hidden" name="idcur" value="{{$data->id}}">
        <input type="hidden" id="isactive" name='isactive' value={{$data->gandeng_is_active}}>
        <input type="hidden" name="prevurl" value="{{url()->previous()}}">
        <div class="modal-body">
            <div class="form-group row">
                <label for="domain" class="col-md-3 col-form-label text-md-right">{{ __('Domain') }}</label>
                <div class="col-md-7">
                    <select id="domain" class="form-control domain" name="domain" autofocus required autocomplete="off">
                        @foreach($domain as $dm)
                            <option value="{{$dm->domain_code}}" {{$dm->domain_code == $data->gandeng_domain ? 'Selected' : ''}}>{{$dm->domain_code}} -- {{$dm->domain_desc}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="e_gandeng" class="col-md-3 col-form-label text-md-right">{{ __('Gandengan') }}</label>
                <div class="col-md-7">
                    <input type="text" name="e_gandeng" id="e_gandeng" class="form-control" value="{{$data->gandeng_code}}" {{$data->gandeng_is_active == 0 ? 'readonly' : ''}}> 
                        
                        
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <a href="{{ route('gandengan.index') }}" id="btnback" class="btn btn-success bt-action">Back</a>
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
            placeholder: 'Pilih Driver',
            allowClear: true,
            readOnly : true,
            disabled: true
        });
    }
    else{
        $('#tipetruck,#domain,#driver,#pengurus').select2({
        placeholder: 'Pilih Driver',
        allowClear: true
    });
    }

    
    $('#submit').on("submit", function() {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnback').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });
</script>
@endsection