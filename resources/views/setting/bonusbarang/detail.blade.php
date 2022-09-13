@extends('layout.layout')

@section('menu_name', 'Rute Maintenance')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Master</a></li>
        <li class="breadcrumb-item active">Rute Maintenance</li>
    </ol>
@endsection

@section('content')

    <!-- Page Heading -->
    <div id="myModal" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <!-- konten modal-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">Create Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="panel-body">
                    <!-- heading modal -->
                    <form class="form-horizontal" role="form" method="POST" action="{{route('bonusbarangmt.store')}}">
                        {{ method_field('post') }}
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="barang"
                                    class="col-md-3 col-form-label text-md-right">{{ __('Barang') }}</label>
                                <div class="col-md-7">
                                    <input type="hidden" value="{{$curbarang->id}}" name="barang">
                                    <input type="text" class="form-control" value="{{$curbarang->barang_deskripsi}}" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="role"
                                    class="col-md-3 col-form-label text-md-right">{{ __('Tipe Truck') }}</label>
                                <div class="col-md-7">
                                    <select id="tipetruck" class="form-control select2" name="tipetruck" required autofocus>
                                        <option value=""> Select Data </option>
                                        @foreach ($tipetruck as $tipetrucks)
                                            <option value="{{ $tipetrucks->id }}">{{ $tipetrucks->tt_desc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="qtyawal"
                                    class="col-md-3 col-form-label text-md-right">{{ __('Qty Start') }}</label>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name="qtyawal" value="0" step="0.01">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="qtyakhir"
                                    class="col-md-3 col-form-label text-md-right">{{ __('Qty Akhir') }}</label>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name="qtyakhir" value="0" step="0.01">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nominal"
                                    class="col-md-3 col-form-label text-md-right">{{ __('Harga Per Satuan') }}</label>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name="nominal" value="0" step="0.01">
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
    
    <div id="editModal" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <!-- konten modal-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">Create Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="panel-body">
                    <!-- heading modal -->
                    <form class="form-horizontal" role="form" method="POST" action="{{route('BonusUpdateDetail')}}">
                        {{ method_field('post') }}
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <input type="hidden" name="iddetail" id="iddetail">
                            <div class="form-group row">
                                <label for="tipetruck"
                                    class="col-md-3 col-form-label text-md-right">{{ __('Tipe Truck') }}</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" id="e_tipetruck" name="tipetruck" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="barang"
                                    class="col-md-3 col-form-label text-md-right">{{ __('Barang') }}</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" id="e_barang" name="tipetruck" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="qtyawal"
                                    class="col-md-3 col-form-label text-md-right">{{ __('Qty Start') }}</label>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" id="e_start" name="qtyawal" value="0" step="0.01" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="qtyakhir"
                                    class="col-md-3 col-form-label text-md-right">{{ __('Qty Akhir') }}</label>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" id="e_end" name="qtyakhir" value="0" step="0.01" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nominal"
                                    class="col-md-3 col-form-label text-md-right">{{ __('Harga Per Satuan') }}</label>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" id="e_price" name="nominal" value="0" step="0.01" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="active"
                                    class="col-md-3 col-form-label text-md-right">{{ __('Status') }}</label>
                                <div class="col-md-3">
                                    <select name="status" id="e_status" class="form-control">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-info bt-action" id="e_btnclose"
                                data-dismiss="modal">Cancel</button>
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


    <div class="col-lg-12">
        <button class="btn btn-info bt-action newRole" data-toggle="modal" data-target="#myModal">
            Create Bonus</button>
        <form action="{{route('BonusBarangDetail', $id)}}" method="get">
            <div class="row form-group mt-4">
                <label for="s_tipetruck" class="col-md-2 col-form-label text-md-right">{{ __('Tipe Truck') }}</label>
                <div class="col-md-3">
                    <select name="s_tipetruck" id="s_tipetruck" class="form-control select2">
                        <option value="">Select Data</option>
                        @foreach ($tipetruck as $tipetrucks)
                            <option value="{{ $tipetrucks->id }}">{{ $tipetrucks->tt_desc }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="col-md-1">
                    <input type="submit" class="btn bt-ref" id="btnsearch" value="Search" />
                </div>
            </div>
        </form>
    </div>
    <div class="table-responsive offset-md-1 col-lg-10 col-md-10 mt-3">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Tipe Truck</th>
                    <th>Qty Awal</th>
                    <th>Qty Akhir</th>
                    <th>Harga Per Satuan</th>
                    <th>Active</th>
                    <th width="8%">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bonusbarang as $index => $show)
                    <tr>
                        <td>
                            {{ $show->getBarang->barang_deskripsi ?? '' }}
                        </td>
                        <td>
                            {{ $show->getTipeTruck->tt_desc ?? ''}}
                        </td>
                        <td>
                            {{ $show->bb_qty_start }}
                        </td>
                        <td>
                            {{ $show->bb_qty_end }}
                        </td>
                        <td>
                            {{ number_format($show->bb_price,0) }}
                        </td>
                        <td>
                            {{ $show->bb_is_active == 1 ? 'Active' : 'Not Active'}}
                        </td>
                        <td>
                            <a href="#" class="editmodal" data-toggle="modal" data-target="#editModal"
                                data-barang="{{$show->getBarang->barang_deskripsi ?? ''}}"
                                data-tipetruck="{{$show->getTipeTruck->tt_desc ?? ''}}"
                                data-qtystart="{{$show->bb_qty_start}}"
                                data-qtyend="{{$show->bb_qty_end}}"
                                data-price="{{$show->bb_price}}"
                                data-active="{{$show->bb_is_active}}"
                                data-id="{{$show->id}}">
                                <i class="fa fa-edit"></i> 
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $bonusbarang->withQueryString()->links() }}
    </div>




@endsection


@section('scripts')

    <script type="text/javascript">
        $('.select2').select2({
            width: '100%'
        });
        
        $('.editmodal').on('click',function(e){
            let tipetruck = $(this).data('tipetruck');
            let barang = $(this).data('barang');
            let qtystart = $(this).data('qtystart');
            let qtyend = $(this).data('qtyend');
            let price = $(this).data('price');
            let status = $(this).data('active');
            let id = $(this).data('id')

            $('#e_tipetruck').val(tipetruck);
            $('#e_barang').val(barang);
            $('#e_start').val(qtystart);
            $('#e_end').val(qtyend);
            $('#e_price').val(price);
            $('#e_status').val(status);
            $('#iddetail').val(id);
        });
        
        $('form').submit(function(e) {
            document.getElementById('btnclose').style.display = 'none';
            document.getElementById('btnconf').style.display = 'none';
            document.getElementById('btnloading').style.display = '';
            document.getElementById('e_btnclose').style.display = 'none';
            document.getElementById('e_btnconf').style.display = 'none';
            document.getElementById('e_btnloading').style.display = '';
        })

            
        $(document).ready(function(){
            var cur_url = window.location.href;

            let paramString = cur_url.split('?')[1];
            let queryString = new URLSearchParams(paramString);

            let tipetruck = queryString.get('s_tipetruck');

            $('#s_tipetruck').val(tipetruck).trigger('change');
        });
    </script>
@endsection
