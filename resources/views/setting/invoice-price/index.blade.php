@extends('layout.layout')

@section('menu_name', 'Invoice Price Maintenance')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Master</a></li>
        <li class="breadcrumb-item active">Invoice Price Maintenance</li>
    </ol>
@endsection

@section('content')

    <!-- Page Heading -->
    <form action="{{ route('invoiceprice.index') }}" method="get">
        <div class="form-group row col-md-12">
            <label for="s_custcode" class="col-md-2 col-form-label">{{ __('Customer Code') }}</label>
            <div class="col-md-3">
                <select name="s_custcode" id="s_custcode" class="form-control">
                    <option value="">Select Data</option>
                    @foreach ($listcust as $listcusts)
                        <option value="{{ $listcusts->id }}">{{ $listcusts->cust_code }} -- {{ $listcusts->cust_desc }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 offset-md-1">
                <input type="submit" class="btn bt-action" id="btnsearch" value="Search" />
                <button class="btn bt-action" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i class="fa fa-sync"></i></button>
            </div>

        </div>
    </form>

    <div class="table-responsive col-lg-12 col-md-12 mt-3">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Customer Code</th>
                    <th>Customer Description</th>
                    <th width="8%">View</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customer as $index => $customers)
                    <tr>
                        <td>
                            {{ $customers->cust_code }}
                        </td>
                        <td>
                            {{ $customers->cust_desc }}
                        </td>
                        <td>
                            <a href="invoiceprice/invoicepricedetail/{{ $customers->id }}" class="view"><i
                                    class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{$customer->withQueryString()->links()}}
    </div>




@endsection


@section('scripts')

    <script type="text/javascript">
         $('#s_custcode').select2({});

         $(document).ready(function(){
            var cur_url = window.location.href;

            let paramString = cur_url.split('?')[1];
            let queryString = new URLSearchParams(paramString);

            let customer = queryString.get('s_custcode');

            $('#s_custcode').val(customer).trigger('change');
         });

         function resetSearch(){
            $('#s_custcode').val('');
         }
         
         $(document).on('click', '#btnrefresh', function(){
            resetSearch();
         });

         $('#btnexcel').change(function() {
               $(this).closest('form').submit();
         })
    </script>
@endsection
