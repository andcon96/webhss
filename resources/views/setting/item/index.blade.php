@extends('layout.layout')

@section('menu_name', 'Department Maintenance')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Master</a></li>
        <li class="breadcrumb-item active">Item Maintenance</li>
    </ol>
@endsection

@section('content')
    <!-- page heading -->
    <div class="col-md-12 col-lg-8 offset-lg-2 mb-4">
        <form action="{{ route('itemmaint.store', 'create') }}}" method="POST">
            {{ method_field('post') }}
            {{ csrf_field() }}
            <input type="submit" class="btn bt-action" id="btnrefresh" value="Load Table" />
        </form>

        <form action="{{route('itemmaint.index')}}" method="get">
            <div class="row form-group mt-4">
                <label for="s_itemcode" class="col-md-2 col-form-label">{{ __('Item Code') }}</label>
                <div class="col-md-4">
                    <select name="s_itemcode" id="s_itemcode" class="form-control">
                        <option value="">Select Data</option>
                        @foreach($listitem as $listitems)
                            <option value="{{$listitems->id}}">{{$listitems->item_part}} -- {{$listitems->item_desc}}</option>
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
                    {{-- <th width="15%">Domain</th> --}}
                    <th width="20%">Item Code</th>
                    <th>Item Desc</th>
                    <th width="10%">Item EA</th>
                    <th width="10%">Type</th>
                </tr>
            </thead>
            <tbody>
                @forelse($item as $items)
                    <tr>
                        {{-- <td>{{ $items->item_domain }}</td> --}}
                        <td>{{ $items->item_part }}</td>
                        <td>{{ $items->item_desc }}</td>
                        <td>{{ $items->item_um }}</td>
                        <td>{{ $items->item_promo }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align:center;color:red">No Data Available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $item->withQueryString()->links() }}
    </div>

@endsection


@section('scripts')
    <script>
        $('#s_itemcode').select2({
            width : '100%',
        });

        $('#btnrefresh').on('click', function(){
            $('#loader').removeClass('hidden');
        });
    </script>
@endsection