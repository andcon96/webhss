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
                <label for="prefixso" class="col-md-3 col-form-label text-md-right">{{ __('Prefix SO') }}</label>
                <div class="col-md-7">
                    <input id="prefixso" type="text" class="form-control" name="prefixso" autocomplete="off" value="{{$prefix->prefix_so ?? ''}}" maxlength="2" autofocus required>
                </div>
            </div>
            <div class="form-group row">
                <label for="rnso" class="col-md-3 col-form-label text-md-right">{{ __('Running Nbr SO') }}</label>
                <div class="col-md-7">
                    <input id="rnso" type="text" class="form-control" autocomplete="off" name="rnso" value="{{$prefix->rn_so ?? ''}}" maxlength="6" required>
                    <span id="errorcur" style="color:red"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="prefixkerusakan" class="col-md-3 col-form-label text-md-right">{{ __('Prefix Kerusakan') }}</label>
                <div class="col-md-7">
                    <input id="prefixkerusakan" type="text" class="form-control" name="prefixkerusakan" autocomplete="off" value="{{$prefix->prefix_kerusakan ?? ''}}" maxlength="2" autofocus required>
                </div>
            </div>
            <div class="form-group row">
                <label for="rnkerusakan" class="col-md-3 col-form-label text-md-right">{{ __('Running Nbr Kerusakan') }}</label>
                <div class="col-md-7">
                    <input id="rnkerusakan" type="text" class="form-control" autocomplete="off" name="rnkerusakan" value="{{$prefix->rn_kerusakan ?? ''}}" maxlength="6" required>
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