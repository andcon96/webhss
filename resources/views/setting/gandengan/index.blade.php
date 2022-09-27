@extends('layout.layout')

@section('menu_name','Gandengan Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Gandengan Maintenance</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->
<div class="col-md-10 offset-lg-1">
    <button class="btn btn-info bt-action newRole" data-toggle="modal" data-target="#myModal">
        Create Gandengan</button>
</div>

<form action="{{route('gandengan.index')}}" method="get">
    <div class="form-group row  col-md-12 mt-3">
        <label for="s_gandengan" class="col-md-4 col-form-label text-md-right">{{ __('Gandengan') }}</label>
        <div class="col-md-3">
            <select id="s_gandengan" class="form-control" name="s_gandengan" autofocus autocomplete="off">
                <option value=""> --Select Data-- </option>
                @foreach($gandengmstr as $gandengan)
                <option value="{{$gandengan->id}}">{{$gandengan->gandeng_desc}}</option>
                @endforeach
            </select>
        </div>
{{--        <label for="s_tipe" class="col-md-2 col-form-label text-md-right">{{ __('Tipe') }}</label>
        <div class="col-md-2">
            <select id="s_tipe" class="form-control" name="s_tipe" autofocus autocomplete="off">
                <option value=""> --Select Data-- </option>
                @foreach($tipe as $tipes)
                <option value="{{$tipes->id}}">{{$tipes->tt_code}} - {{$tipes->tt_desc}}</option>
                @endforeach
            </select>
        </div> --}}
        <div class="col-md-3">
            {{-- <label for="s_status" class="col-md-2 col-form-label text-md-right">{{ __('') }}</label> --}}
            {{-- <div class="col-md-3"> --}}
                <button class="btn bt-action newUser" id="btnsearch" value="Search">Search</button>
                <button class="btn bt-action newUser" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i class="fa fa-sync"></i></button>
        {{-- </div> --}}
        </div>
    </div>
</form>

<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-3">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Domain</th>
                <th>Gandengan Code</th>
                <th>Gandengan Desc</th>
                <th>Active</th>
                
                <th width="10%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $datas)
            <tr>
                <td>
                    {{$datas->gandeng_domain}}
                </td>
                <td>
                    {{$datas->gandeng_code}}
                </td>
                <td>
                    {{$datas->gandeng_desc}}
                </td>
                <td>
                    {{$datas->gandeng_is_active == 1 ? 'Active' : 'Not Active'}}
                </td>

                <td>
                    <a href="{{Route('gandengan.edit',$datas)}}" ><i class="fas fa-edit"></i></a>
                    @if($datas->truck_is_active == 1)
                    <a href="" class="deleteRole" data-id="{{$datas->id}}" data-active="{{$datas->gandeng_is_active}}" data-polis="{{$datas->gandeng_code}}" data-toggle='modal' data-target="#deleteModal"><i class="fas fa-trash-alt"></i></button>
                    @else
                    <a href="" class="deleteRole" data-id="{{$datas->id}}" data-active="{{$datas->gandeng_is_active}}" data-polis="{{$datas->gandeng_code}}" data-toggle='modal' data-target="#deleteModal"><i class="fas fa-trash-restore"></i></button>
                    @endif    
                </td>
            </tr>
            @empty
            <tr>
                <td colspan='6' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{$data->withQueryString()->render()}}
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
                <form class="form-horizontal" role="form" method="POST" action="{{route('gandengan.store')}}">
                    {{ method_field('post') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="domain" class="col-md-3 col-form-label text-md-right">{{ __('Domain') }}</label>
                            <div class="col-md-7">
                                <select id="c_domain" class="form-control domain" name="c_domain" autofocus required autocomplete="off">
                                    
                                    @foreach($domain as $dm)
                                    <option value="{{$dm->domain_code}}">{{$dm->domain_code}} -- {{$dm->domain_desc}}</option>
                                    @endforeach
                                </select>
                                
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="c_gandeng" class="col-md-3 col-form-label text-md-right">{{ __('Gandengan Code') }}</label>
                            <div class="col-md-7">
                                <input id="c_gandeng" type="text" class="form-control" name="c_gandeng" autocomplete="off" value="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="c_gandengdesc" class="col-md-3 col-form-label text-md-right">{{ __('Gandengan Desc') }}</label>
                            <div class="col-md-7">
                                <input id="c_gandengdesc" type="text" class="form-control" name="c_gandengdesc" autocomplete="off" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="c_active" class="col-md-3 col-form-label text-md-right">{{ __('Active') }}</label>
                            <div class="col-md-7">
                                <select id="c_active" class="form-control user" name="c_active" autofocus required autocomplete="off">
                                    <option selected disabled> --Select Data-- </option>
                                    <option value="1">Ya</option>
                                    <option value="0">Tidak</option>
                                    
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

            <form action="{{route('gandengan.destroy', 'role')}}" method="post">

                {{ method_field('delete') }}
                {{ csrf_field() }}

                <div class="modal-body">

                    <input type="hidden" name="_method" value="delete">

                    <input type="hidden" name="temp_id" id="temp_id" value="">

                    <div class="container">
                        <div class="row">
                            Are you sure you want to <a name="temp_active" id="temp_active" class="mr-1 ml-1">  Gandengan : &nbsp; <strong><a name="temp_uname" id="temp_uname"></a></strong>
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
    
    $('.domain, .driver, #s_gandengan, .user, #s_tipe').select2({
        width: '100%'
    });

    // $(document).on('click', '.newRole', function() {
    //     document.getElementById('role').value = '';
    //     document.getElementById('desc').value = '';
    // });

    $(document).on('click', '.deleteRole', function() { // Click to only happen on announce links

        //alert('tst');
        var uid = $(this).data('userid');
        var id = $(this).data('id');
        var truck = $(this).data('polis');
        var active = $(this).data('active');

        active == 1 ? active = 'Deactive' : active = 'Activate';

        document.getElementById("temp_id").value = id;
        document.getElementById("temp_uname").innerHTML = truck;
        document.getElementById("temp_active").innerHTML = active;

    });

    function resetSearch(){
        $('#s_truck').val('').trigger('change');
        $('#s_tipe').val('').trigger('change');
    }
    
    $(document).on('click', '#btnrefresh', function(){
        resetSearch();
    });

    $(document).ready(function(){
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let truck = queryString.get('s_truck');
        let tipe = queryString.get('s_tipe');

        $('#s_truck').val(truck).trigger('change');
        $('#s_tipe').val(tipe).trigger('change');
    });
</script>
@endsection