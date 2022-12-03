@extends('layout.layout')

@section('menu_name', 'Department Maintenance')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Master</a></li>
        <li class="breadcrumb-item active">Bank Customer Maintenance</li>
    </ol>
@endsection

@section('content')

   <!-- Page Heading -->
    <div class="col-md-12">
        <a href="{{route('bankcustomer.create')}}" class="btn bt-action">Create Data</a>
    </div>
    <!-- page heading -->
    <div class="col-md-12 col-lg-10 mb-4">
        <form action="{{route('bankcustomer.index')}}" method="get">
            <div class="row form-group mt-4">
                <label for="s_customer" class="col-md-2 text-md-right col-form-label">{{ __('Customer') }}</label>
                <div class="col-md-4">
                    <select name="s_customer" id="s_customer" class="form-control">
                        <option value="">Select Data</option>
                        @foreach($customer as $customers)
                            <option value="{{$customers->id}}">{{$customers->cust_code}} -- {{$customers->cust_desc}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="s_domain" class="col-md-2 text-md-right col-form-label">{{ __('Domain') }}</label>
                <div class="col-md-4">
                    <select name="s_domain" id="s_domain" class="form-control">
                        <option value="">Select Data</option>
                        @foreach($domain as $domains)
                            <option value="{{$domains->id}}">{{$domains->domain_code}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row form-group mt-4">
               <label for="s_domain" class="col-md-2 text-md-right col-form-label">{{ __('') }}</label>
               <div class="col-md-2">
                   <input type="submit" class="btn bt-ref" id="btnsearch" value="Search" />
                   <button class="btn bt-action ml-2" id='btnrefresh' style="width: 40px !important"><i class="fa fa-sync"></i></button>
               </div>
            </div>
        </form>
    </div>

    <div class="table-responsive col-lg-12 col-md-12">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th width="25%">Customer</th>
                    <th width="10%">Domain</th>
                    <th width="10%">Bank Account</th>
                    <th width="15%">Bank Account Name</th>
                    <th width="10%">Status</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bankcust as $bankcusts)
                    <tr>
                        <td>{{ $bankcusts->getCustomer->cust_desc }}</td>
                        <td>{{ $bankcusts->getDomain->domain_code }}</td>
                        <td>{{ $bankcusts->bc_acc_nbr }}</td>
                        <td>{{ $bankcusts->bc_acc_name }}</td>
                        <td>{{ $bankcusts->bc_is_active == 1 ? 'Active' : 'Not Active' }}</td>
                        <td>
                            <a href="{{route('bankcustomer.edit', $bankcusts->id)}}">
                                <i class="fas fa-edit"></i>
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
        {{ $bankcust->withQueryString()->links() }}
    </div>

@endsection


@section('scripts')
    <script>
        $('#s_customer,#s_domain').select2({
            width : '100%',
        });

        $('#btnrefresh').on('click', function(){
            $('#s_customer').val('');
            $('#s_domain').val('');
        });

        $(document).ready(function(){
            var cur_url = window.location.href;

            let paramString = cur_url.split('?')[1];
            let queryString = new URLSearchParams(paramString);

            let customer = queryString.get('s_customer');
            let domain = queryString.get('s_domain');

            $('#s_customer').val(customer).trigger('change');
            $('#s_domain').val(domain).trigger('change');
        });
    </script>
@endsection