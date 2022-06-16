@extends('layout.layout')

@section('menu_name','Kerusakan Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Kerusakan Maintenance</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->
<div class="col-md-12 offset-lg-2">
    <button class="btn btn-info bt-action newRole" data-toggle="modal" data-target="#myModal">
        Create Data</button>
</div>

<form action="{{route('kerusakanmt.index')}}" method="get">
    <div class="form-group row offset-md-1 col-md-10 mt-3">
        <label for="s_kerusakan" class="col-md-2 col-form-label text-md-right">{{ __('Kerusakan') }}</label>
        <div class="col-md-3">
            <select id="s_kerusakan" class="form-control" name="s_kerusakan" autofocus autocomplete="off">
                <option value=""> --Select Data-- </option>
                @foreach($kerusakan as $datas)
                <option value="{{$datas->id}}">{{$datas->kerusakan_code}} -- {{$datas->kerusakan_desc}}</option>
                @endforeach
            </select>
        </div>
        <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('') }}</label>
        <div class="col-md-3">
            <button class="btn bt-action newUser" id="btnsearch" value="Search">Search</button>
            <button class="btn bt-action newUser" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i class="fa fa-sync"></i></button>
        </div>
    </div>
</form>

@include('setting.kerusakan.index-table')


<!--Create Modal-->
<div id="myModal" class="modal fade bd-example-modal-lg" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <!-- konten modal-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Create Kerusakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="panel-body">
                <!-- heading modal -->
                <form class="form-horizontal" role="form" method="POST" action="{{route('kerusakanmt.store')}}">
                    {{ method_field('post') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="code" class="col-md-3 col-form-label text-md-right">{{ __('Code') }}</label>
                            <div class="col-md-3">
                                <input id="code" type="text" class="form-control" name="code" autocomplete="off" value="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="desc" class="col-md-3 col-form-label text-md-right">{{ __('Description') }}</label>
                            <div class="col-md-7">
                                <input id="desc" type="text" class="form-control" name="desc" autocomplete="off" value="" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-info bt-action" id="btnclose" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success bt-action" id="btnconf">Save</button>
                        <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                            <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Create Modal-->
<div id="editModal" class="modal fade bd-example-modal-lg" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <!-- konten modal-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Edit Kerusakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="panel-body">
                <!-- heading modal -->
                <form class="form-horizontal" role="form" method="POST" action="{{route('kerusakanmt.update','test')}}">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <div class="form-group row">
                            <label for="e_code" class="col-md-3 col-form-label text-md-right">{{ __('Code') }}</label>
                            <div class="col-md-3">
                                <input id="e_code" type="text" class="form-control" name="e_code" autocomplete="off" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="e_desc" class="col-md-3 col-form-label text-md-right">{{ __('Description') }}</label>
                            <div class="col-md-7">
                                <input id="e_desc" type="text" class="form-control" name="e_desc" autocomplete="off" value="" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-info bt-action" id="btnclose" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success bt-action" id="btnconf">Save</button>
                        <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                            <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

            <form action="{{route('kerusakanmt.destroy', 'role')}}" method="post">

                {{ method_field('delete') }}
                {{ csrf_field() }}

                <div class="modal-body">

                    <input type="hidden" name="_method" value="delete">

                    <input type="hidden" name="temp_id" id="temp_id" value="">

                    <div class="container">
                        <div class="row">
                            Are you sure you want to <a name="temp_active" id="temp_active" class="mr-1 ml-1">  Code : &nbsp; <strong><a name="temp_uname" id="temp_uname"></a></strong>
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
    $('#s_kerusakan').select2({
        width: '100%'
    });

    $(document).on('click', '.newRole', function() {
        document.getElementById('role').value = '';
        document.getElementById('desc').value = '';
    });

    $(document).on('click', '.editRole', function() {
        let id = $(this).data('id');
        let code = $(this).data('code');
        let desc = $(this).data('desc');

        $('#edit_id').val(id);
        $('#e_code').val(code);
        $('#e_desc').val(desc);
    });

    $(document).on('click', '.deleteRole', function() { // Click to only happen on announce links

        //alert('tst');
        var id = $(this).data('id');
        var code = $(this).data('code');
        var active = $(this).data('active');

        active == 1 ? active = 'Deactive' : active = 'Activate';


        document.getElementById("temp_id").value = id;
        document.getElementById("temp_uname").innerHTML = code;
        document.getElementById("temp_active").innerHTML = active;

    });

    function resetSearch() {
        $('#s_kerusakan').val('').trigger('change');
    }

    $(document).on('click', '#btnrefresh', function() {
        resetSearch();
    });

    $(document).ready(function() {
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let truck = queryString.get('s_kerusakan');

        $('#s_kerusakan').val(truck).trigger('change');
    });
</script>
@endsection