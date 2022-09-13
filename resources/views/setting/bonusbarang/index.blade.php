@extends('layout.layout')

@section('menu_name', 'Rute Maintenance')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Master</a></li>
        <li class="breadcrumb-item active">Bonus Barang Maintenance</li>
    </ol>
@endsection

@section('content')

    
    <!-- page heading -->
    <div class="col-md-12 col-lg-8 offset-lg-2 mb-4">
        <form action="{{route('bonusbarangmt.index')}}" method="get">
            <div class="row form-group mt-4">
                <label for="s_itemcode" class="col-md-2 col-form-label">{{ __('Barang') }}</label>
                <div class="col-md-4">
                    <select name="s_itemcode" id="s_itemcode" class="form-control">
                        <option value="">Select Data</option>
                        @foreach($listbarang as $listitems)
                            <option value="{{$listitems->id}}">{{$listitems->barang_deskripsi}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 offset-md-1 row">
                    <input type="submit" class="btn bt-ref" id="btnsearch" value="Search" />
                    <button class="btn bt-action ml-2" id='btnrefresh' style="width: 40px !important"><i class="fa fa-sync"></i></button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive offset-md-2 col-lg-8 col-md-8 mt-3">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th width="20%">View</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barang as $index => $show)
                    <tr>
                        <td>
                            {{ $show->barang_deskripsi }}
                        </td>
                        <td>
                            <a href="{{ route('BonusBarangDetail', $show->id )}}" class="view"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{$barang->withQueryString()->links()}}
    </div>

@endsection

@section('scripts')
    <script>
        $('#s_itemcode').select2({
            width : '100%',
        });

        $('#btnrefresh').on('click', function(){
            $('#s_itemcode').val('');
        });

        $(document).ready(function(){
            var cur_url = window.location.href;

            let paramString = cur_url.split('?')[1];
            let queryString = new URLSearchParams(paramString);

            let itemcode = queryString.get('s_itemcode');

            $('#s_itemcode').val(itemcode).trigger('change');
        });
    </script>
@endsection