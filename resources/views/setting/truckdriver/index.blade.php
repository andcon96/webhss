@extends('layout.layout')

@section('menu_name','Truck Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Truck Maintenance</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->
<div class="col-md-12 offset-lg-2">
    <button class="btn btn-info bt-action newRole" data-toggle="modal" data-target="#myModal">
        Create Truck</button>
</div>


<form action="{{route('truckdrivemaint.index')}}" method="get">
    <div class="form-group row offset-md-1 col-md-10 mt-3">
        <label for="s_truck" class="col-md-2 col-form-label text-md-right">{{ __('Truck') }}</label>
        <div class="col-md-3">
            <select id="s_truck" class="form-control" name="s_truck" autofocus autocomplete="off">
                <option value=""> --Select Data-- </option>
                @foreach($truck as $trucks)
                <option value="{{$trucks->id}}">{{$trucks->truck_no_polis}}</option>
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

<div class="table-responsive offset-lg-2 col-lg-8 col-md-12 mt-3">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>No Polis</th>
                <th>Kode Supir</th>
                <th>Active</th>
                <th width="10%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $datas)
            <tr>
                <td>
                    {{$datas->getTruck->truck_no_polis}}
                </td>
                <td>
                    {{$datas->getUser->username}} -- {{$datas->getUser->name}}
                </td>
                <td>
                    {{$datas->truck_is_active == 1 ? 'Active' : 'Not Active'}}
                </td>
                <td>
                    <a href="" class="editModal" data-id="{{$datas->id}}" data-polis="{{$datas->truck_no_polis}}"
                        data-iduser="{{$datas->truck_user_id}}" data-toggle='modal' data-target="#editModal"><i
                        class="fas fa-edit"></i></button>
                    @if($datas->truck_is_active == 1)
                    <a href="" class="deleteRole" data-id="{{$datas->id}}" data-active="{{$datas->truck_is_active}}" data-polis="{{$datas->truck_no_polis}}" data-user="{{$datas->getUser->username}}" data-toggle='modal' data-target="#deleteModal"><i class="fas fa-trash-alt"></i></button>
                    @else
                    <a href="" class="deleteRole" data-id="{{$datas->id}}" data-active="{{$datas->truck_is_active}}" data-polis="{{$datas->truck_no_polis}}" data-user="{{$datas->getUser->username}}" data-toggle='modal' data-target="#deleteModal"><i class="fas fa-trash-restore"></i></button>
                    @endif    
                </td>
            </tr>
            @empty
            <tr>
                <td colspan='3' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{$data->render()}}
</div>


<!--Create Modal-->
<div id="myModal" class="modal fade bd-example-modal-lg" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <!-- konten modal-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Create Driver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="panel-body">
                <!-- heading modal -->
                <form class="form-horizontal" role="form" method="POST" action="{{route('truckdrivemaint.store')}}">
                    {{ method_field('post') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="polis" class="col-md-3 col-form-label text-md-right">{{ __('No Polis') }}</label>
                            <div class="col-md-7">
                                <select name="polis" id="polis" class="form-control polis">
                                    @foreach($truck as $trucks)
                                    <option value="{{$trucks->id}}">{{$trucks->truck_no_polis}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="supir" class="col-md-3 col-form-label text-md-right">{{ __('Driver') }}</label>
                            <div class="col-md-7">
                                <select name="driver" id="driver" class="form-control driver">
                                    @foreach($user as $driver)
                                    <option value="{{$driver->id}}">{{$driver->username}} -- {{$driver->name}}</option>
                                    @endforeach
                                </select>
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

<!--Edit Modal-->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <!-- konten modal-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="panel-body">
                <!-- heading modal -->
                <form class="form-horizontal" role="form" method="POST" action="{{route('truckdrivemaint.update', 'update')}}">
                    @method('put')
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="e_id" id="e_id">
                        <div class="form-group row">
                            <label for="e_polis" class="col-md-3 col-form-label text-md-right">{{ __('No Polis') }}</label>
                            <div class="col-md-7">
                                <input id="e_polis" type="text" class="form-control" name="e_polis" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="e_driver" class="col-md-3 col-form-label text-md-right">{{ __('Driver') }}</label>
                            <div class="col-md-7">
                                <select name="e_driver" id="e_driver" class="form-control driver" required>
                                    <option value="">Select Data</option>
                                    @foreach($user as $drivers)
                                    <option value="{{$drivers->id}}">{{$drivers->username}} -- {{$drivers->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-info bt-action" id="e_btnclose" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success bt-action" id="e_btnconf">Save</button>
                        <button type="button" class="btn bt-action" id="e_btnloading" style="display:none">
                            <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DELETE -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Activate / Deactive Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{route('truckdrivemaint.destroy', 'role')}}" method="post">

                {{ method_field('delete') }}
                {{ csrf_field() }}

                <div class="modal-body">

                    <input type="hidden" name="_method" value="delete">

                    <input type="hidden" name="temp_id" id="temp_id" value="">

                    <div class="container">
                        <div class="row">
                            Are you sure you want to <a name="temp_active" id="temp_active" class="mr-1 ml-1">  Truck : &nbsp; <strong><a name="temp_uname" id="temp_uname"></a></strong>
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
    $('.driver, .polis, #s_truck').select2({
        width: '100%'
    });

    $(document).on('click', '.newRole', function() {
        document.getElementById('role').value = '';
        document.getElementById('desc').value = '';
    });

    $(document).on('click', '.deleteRole', function() { // Click to only happen on announce links

        //alert('tst');
        var uid = $(this).data('userid');
        var id = $(this).data('id');
        var user = $(this).data('user');
        var truck = $(this).data('polis');
        var active = $(this).data('active');

        active == 1 ? active = 'Deactive' : active = 'Activate';

        document.getElementById("temp_id").value = id;
        document.getElementById("temp_uname").innerHTML = truck + ' - ' + user;
        document.getElementById("temp_active").innerHTML = active;

    });

    $(document).on('click', '.editModal', function() { // Click to only happen on announce links
        var id = $(this).data('id');
        var user = $(this).data('iduser');
        var truck = $(this).data('polis');

        $('#e_driver').val(user).trigger('change');
        document.getElementById("e_id").value = id;
        document.getElementById('e_polis').value = truck;

    });

    function resetSearch(){
        $('#s_truck').val('').trigger('change');
    }
    
    $(document).on('click', '#btnrefresh', function(){
        resetSearch();
    });

    $(document).ready(function(){
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let truck = queryString.get('s_truck');

        $('#s_truck').val(truck).trigger('change');
    });
</script>
@endsection