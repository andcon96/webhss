@extends('layout.layout')

@section('menu_name', 'Department Maintenance')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Master</a></li>
        <li class="breadcrumb-item active">Driver Nopol Maintenance</li>
    </ol>
@endsection

@section('content')

    <!-- Page Heading -->
    <div class="col-md-10 offset-md-1">
        <button class="btn btn-info bt-action newRole" data-toggle="modal" data-target="#myModal">
            Create Link Nopol</button>
    </div>
    <!-- page heading -->
    <div class="col-md-10 col-lg-10 offset-lg-1 mb-4">
        <form action="{{ route('drivernopolmt.index') }}" method="get">
            <div class="row form-group mt-4">
                <label for="driver" class="col-md-2 col-form-label text-md-right">{{ __('Driver') }}</label>
                <div class="col-md-3">
                    <select name="driver" id="driver" class="form-control">
                        <option value="">Select Data</option>
                        @foreach ($listdriver as $listdrivers)
                            <option value="{{ $listdrivers->id }}">{{ $listdrivers->driver_name }}</option>
                        @endforeach
                    </select>
                </div>
                <label for="truck" class="col-md-2 col-form-label text-md-right">{{ __('Truck') }}</label>
                <div class="col-md-3">
                    <select name="truck" id="truck" class="form-control">
                        <option value="">Select Data</option>
                        @foreach ($listtruck as $listtrucks)
                            <option value="{{ $listtrucks->id }}">{{ $listtrucks->truck_no_polis }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="submit" class="btn bt-ref" id="btnsearch" value="Search" />
                    <button class="btn bt-action" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i
                            class="fa fa-sync"></i></button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive col-lg-10 offset-lg-1 col-md-12">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th width="20%">Truck</th>
                    <th width="20%">Driver</th>
                    <th width="10%">Active</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $items)
                    <tr>
                        <td>{{ $items->getTruck->truck_no_polis ?? '' }}</td>
                        <td>{{ $items->getDriver->driver_name ?? '' }}</td>
                        <td>{{ $items->dn_is_active == 1 ? 'Active' : 'Not Active' }}</td>
                        <td>
                            <a href="#" class="editmodal" data-toggle="modal" data-target="#editModal"
                                data-id="{{ $items->id }}" data-truck="{{$items->getTruck->truck_no_polis ?? ''}}"
                                data-name="{{$items->getDriver->driver_name ?? ''}}">
                                @if ($items->driver_is_active == 1)
                                    <i class="fa fa-check"></i>
                                @else
                                    <i class="fa fa-times"></i>
                                @endif
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align:center;color:red">No Data Available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $data->withQueryString()->links() }}
    </div>



    <div id="editModal" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <!-- konten modal-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">Aktifkan / Matikan Driver</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="panel-body">
                    <!-- heading modal -->
                    <form class="form-horizontal" role="form" method="POST"
                        action="{{ route('drivernopolmt.update', 'Edit') }}">
                        {{ method_field('put') }}
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="form-group row">
                                Aktifkan / Non Aktifkan Driver &nbsp; <b>
                                    <p id="e_truck_name"></p>
                                </b> &nbsp <input type="hidden" name="id" id="e_iddriver"> ?
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-info bt-action" id="btnclose"
                                data-dismiss="modal">Cancel</button>
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

    <div id="myModal" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <!-- konten modal-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">Update Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="panel-body">
                    <!-- heading modal -->
                    <form class="form-horizontal" role="form" method="POST"
                        action="{{ route('drivernopolmt.store') }}">
                        {{ method_field('post') }}
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="barang" class="col-md-3 col-form-label">{{ __('Driver Name') }}</label>
                                <div class="col-md-7">
                                    <select name="driver" id="d_driver" class="form-control">
                                       <option value="">Select Data</option>
                                       @foreach ($listactivedriver as $listdrivers)
                                          <option value="{{ $listdrivers->id }}">{{ $listdrivers->driver_name }}</option>
                                       @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="truck" class="col-md-3 col-form-label">{{ __('Truck') }}</label>
                                <div class="col-md-7">
                                    <select name="truck" id="d_truck" class="form-control">
                                       <option value="">Select Data</option>
                                       @foreach ($listactivetruck as $listtruck)
                                          <option value="{{ $listtruck->id }}">{{ $listtruck->truck_no_polis }}</option>
                                       @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-info bt-action" id="btnclose"
                                data-dismiss="modal">Cancel</button>
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

@endsection


@section('scripts')
    <script>
        $('#driver, #truck, #d_truck, #d_driver').select2({
            width: '100%',
        });

        $('#btnrefresh,#btnsearch').on('click', function() {
            $('#loader').removeClass('hidden');
        });

        $(document).on('click', '.editmodal', function(e) {
            let id = $(this).data('id');
            let nama = $(this).data('name');
            let truck = $(this).data('truck');

            document.getElementById('e_truck_name').innerHTML = nama + ' - ' + truck;
            document.getElementById('e_iddriver').value = id;
        })

        $(document).ready(function() {
            var cur_url = window.location.href;

            let paramString = cur_url.split('?')[1];
            let queryString = new URLSearchParams(paramString);

            let driver = queryString.get('driver');
            let truck = queryString.get('truck');


            $('#driver').val(driver).trigger('change');
            $('#truck').val(truck).trigger('change');
        });


        function resetSearch() {
            $('#driver').val('');
            $('#truck').val('');
        }
        $(document).on('click', '#btnrefresh', function() {
            resetSearch();
        });
    </script>
@endsection
