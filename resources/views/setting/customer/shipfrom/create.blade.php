@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Ship From Create</li>
</ol>
@endsection

@section('content')
<div class="table-responsive col-lg-12 col-md-12">
    <form action="{{route('shipfrom.store')}}" method="post" id="submit">
        {{ method_field('post') }}
        {{ csrf_field() }}

        <div class="modal-body">

            <input type="hidden" name="qxenable" id="qxenable" value="{{$data->qx_enable ?? ''}}">

            {{-- <div class="form-group row">
                <label for="domain" class="col-md-3 col-form-label text-md-right">{{ __('Domain') }}</label>
                <div class="col-md-7">
                    <input id="domain" type="text" class="form-control" name="domain" autocomplete="off" value="{{$data->wsas_domain ?? ''}}" 
                    autofocus required>
                </div>
            </div> --}}
            <div class="form-group row">
                <label for="sfcode" class="col-md-3 col-form-label text-md-right">{{ __('Ship From Code') }}</label>
                <div class="col-md-2">
                    <input id="sfcode" type="text" class="form-control" autocomplete="off" name="sfcode" value="{{$lastnumber}}" maxlength="6" required readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="sfdesc" class="col-md-3 col-form-label text-md-right">{{ __('Description') }}</label>
                <div class="col-md-7">
                    <input id="sfdesc" type="text" class="form-control" autocomplete="off" name="sfdesc" value="" required>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <a href="{{ route('shipfrom.index') }}" id="btnback" class="btn btn-success bt-action">Back</a>
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
    $('#submit').on("submit", function() {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnback').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });
</script>
@endsection