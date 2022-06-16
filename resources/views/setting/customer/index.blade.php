@extends('layout.layout')

@section('menu_name', 'Department Maintenance')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Master</a></li>
        <li class="breadcrumb-item active">Customer Maintenance</li>
    </ol>
@endsection

@section('content')

    <!-- page heading -->
    <div class="col-md-12 col-lg-8 offset-lg-2 mb-4">
        <form action="{{ route('customermaint.store', 'create') }}}" method="POST">
            {{ method_field('post') }}
            {{ csrf_field() }}
            <input type="submit" class="btn bt-action" id="btnrefresh" value="Load Table" />
        </form>

        <form action="{{route('customermaint.index')}}" method="get">
            <div class="row form-group mt-4">
                <label for="s_custcode" class="col-md-2 col-form-label">{{ __('Customer Code') }}</label>
                <div class="col-md-4">
                    <select name="s_custcode" id="s_custcode" class="form-control">
                        <option value="">Select Data</option>
                        @foreach($listcust as $listcusts)
                            <option value="{{$listcusts->id}}">{{$listcusts->cust_code}} -- {{$listcusts->cust_desc}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 offset-md-1">
                    <input type="submit" class="btn bt-ref" id="btnsearch" value="Search" />
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive col-lg-8 offset-lg-2 col-md-12">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th width="20%">Customer Code</th>
                    <th>Customer Desc</th>
                    <th width="15%">Site</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cust as $show)
                    <tr>
                        <td>{{ $show->cust_code }}</td>
                        <td>{{ $show->cust_desc }}</td>
                        <td>{{ $show->cust_site }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align:center;color:red">No Data Available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $cust->withQueryString()->links() }}
    </div>

@endsection


@section('scripts')
    <script>
        $('#s_custcode').select2({
            width : '100%',
        });
        $('#btnrefresh').on('click', function(){
            $('#loader').removeClass('hidden');
        });
    </script>
@endsection