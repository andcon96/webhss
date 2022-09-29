@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Prefix Control</li>
</ol>
@endsection

@section('content')
<div class="table-responsive col-lg-12 col-md-12">
    <form action="{{route('prefixmaint.store')}}" method="post" id="submit">
        {{ method_field('post') }}
        {{ csrf_field() }}

        <div class="modal-header">
        </div>

        <div class="modal-body">

            <div class="form-group row">
                <label for="prefixco" class="col-md-3 col-form-label text-md-right">{{ __('Prefix CO') }}</label>
                <div class="col-md-2">
                    <input id="prefixco" type="text" class="form-control" name="prefixco" autocomplete="off" value="{{$prefix->prefix_co ?? ''}}" maxlength="2" autofocus required>
                </div>
            </div>
            <div class="form-group row">
                <label for="rnco" class="col-md-3 col-form-label text-md-right">{{ __('Running Nbr CO') }}</label>
                <div class="col-md-3">
                    <input id="rnco" type="text" class="form-control" autocomplete="off" name="rnco" value="{{$prefix->prefix_co_rn ?? ''}}" maxlength="6" required readonly>
                    <span id="errorcur" style="color:red"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="prefixso" class="col-md-3 col-form-label text-md-right">{{ __('Prefix SO') }}</label>
                <div class="col-md-2">
                    <input id="prefixso" type="text" class="form-control" name="prefixso" autocomplete="off" value="{{$prefix->prefix_so ?? ''}}" maxlength="2" autofocus required>
                </div>
            </div>
            <div class="form-group row">
                <label for="rnso" class="col-md-3 col-form-label text-md-right">{{ __('Running Nbr SO') }}</label>
                <div class="col-md-3">
                    <input id="rnso" type="text" class="form-control" autocomplete="off" name="rnso" value="{{$prefix->prefix_so_rn ?? ''}}" maxlength="6" required readonly>
                    <span id="errorcur" style="color:red"></span>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="prefixspk" class="col-md-3 col-form-label text-md-right">{{ __('Prefix SPK') }}</label>
                <div class="col-md-2">
                    <input id="prefixspk" type="text" class="form-control" name="prefixspk" autocomplete="off" value="{{$prefix->prefix_sj ?? ''}}" maxlength="2" autofocus required>
                </div>
            </div>
            <div class="form-group row">
                <label for="rnspk" class="col-md-3 col-form-label text-md-right">{{ __('Running Nbr SPK') }}</label>
                <div class="col-md-3">
                    <input id="rnspk" type="text" class="form-control" autocomplete="off" name="rnspk" value="{{$prefix->prefix_sj_rn ?? ''}}" maxlength="6" required readonly>
                    <span id="errorcur" style="color:red"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="prefixkerusakan" class="col-md-3 col-form-label text-md-right">{{ __('Prefix Kerusakan') }}</label>
                <div class="col-md-2">
                    <input id="prefixkerusakan" type="text" class="form-control" name="prefixkerusakan" autocomplete="off" value="{{$prefix->prefix_kr ?? ''}}" maxlength="2" autofocus required>
                </div>
            </div>
            <div class="form-group row">
                <label for="rnkerusakan" class="col-md-3 col-form-label text-md-right">{{ __('Running Nbr Kerusakan') }}</label>
                <div class="col-md-3">
                    <input id="rnkerusakan" type="text" class="form-control" autocomplete="off" name="rnkerusakan" value="{{$prefix->prefix_kr_rn ?? ''}}" maxlength="6" required readonly> 
                    <span id="errorcur" style="color:red"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="prefixiv" class="col-md-3 col-form-label text-md-right">{{ __('Prefix Invoice') }}</label>
                <div class="col-md-2">
                    <input id="prefixiv" type="text" class="form-control" name="prefixiv" autocomplete="off" value="{{$prefix->prefix_iv ?? ''}}" minlength="3" maxlength="3" autofocus required>
                </div>
            </div>
            <div class="form-group row">
                <label for="rniv" class="col-md-3 col-form-label text-md-right">{{ __('Running Nbr Invoice') }}</label>
                <div class="col-md-3">
                    <input id="rniv" type="text" class="form-control" autocomplete="off" name="rniv" value="{{ substr($prefix->prefix_iv_rn,4,9) ?? ''}}" minlength="9" maxlength="9" required> 
                    <span id="errorcur" style="color:red"></span>
                </div>
            </div>
        </div>

        <div class="modal-footer">
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
        document.getElementById('btnloading').style.display = '';
    });
</script>
@endsection