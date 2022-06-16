@extends('layout.layout')

@section('menu_name','Lapor Kerusakan')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Lapor Kerusakan</li>
</ol>
@endsection

@section('content')

<div class="col-md-12">
    <a href="{{route('laporkerusakan.create') }}" class="btn btn-info bt-action mb-3">Lapor Kerusakan</a>
</div>
<!-- Page Heading -->
<form action="{{route('laporkerusakan.index')}}" method="get">

    <div class="form-group row col-md-12">
        <label for="s_krnbr" class="col-md-2 col-form-label text-md-right">{{ __('Kerusakan Nbr.') }}</label>
        <div class="col-md-4 col-lg-3">
            <input id="s_krnbr" type="text" class="form-control" name="s_krnbr" value="{{ request()->input('s_krnbr') }}" autofocus autocomplete="off">
        </div>
        <label for="s_driver" class="col-md-2 col-form-label text-md-right">{{ __('No Polis') }}</label>
        <div class="col-md-4 col-lg-3">
            <select id="s_driver" class="form-control" name="s_driver" autofocus autocomplete="off">
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
            <button class="btn bt-action newUser" id="btnsearch" value="Search">Search</button>
            <button class="btn bt-action newUser" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i class="fa fa-sync"></i></button>
        </div>
    </div>
</form>

@include('transaksi.kerusakan.index-table')


<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Delete Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{route('laporkerusakan.destroy', 'role')}}" method="post">

                {{ method_field('delete') }}
                {{ csrf_field() }}

                <div class="modal-body">

                    <input type="hidden" name="_method" value="delete">

                    <input type="hidden" name="temp_id" id="temp_id" value="">

                    <div class="container">
                        <div class="row">
                            Are you sure you want to Cancel Number :&nbsp; <strong><a name="temp_uname" id="temp_uname"></a></strong>
                            &nbsp;?
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

@endsection


@section('scripts')

<script type="text/javascript">
    $('#s_driver').select2({
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

    $(document).on('click', '#btnrefresh', function(){
        resetSearch();
    });

    
    $(document).on('click', '.deleteModal', function() {
        var id = $(this).data('id');
        var krnbr = $(this).data('krnbr');

        document.getElementById("temp_id").value = id;
        document.getElementById('temp_uname').text = krnbr;
    });
</script>
@endsection