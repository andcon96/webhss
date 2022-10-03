@extends('layout.layout')

@section('menu_name','Sales Order Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Bank Customer Create</li>
</ol>
@endsection

@section('content')
<form action="{{ route('bankcustomer.store') }}" id="submit" method="POST">
    @csrf
    @method('POST')
    <div class="row offset-md-1 col-md-10">
        <div class="form-group row col-md-12">
            <label for="customer" class="col-md-2 col-form-label text-md-right">Customer</label>
            <div class="col-md-4">
               <select name="customer" id="customer" class="form-control">
                  <option value="">Select Data</option>
                  @foreach($customer as $customers)
                      <option value="{{$customers->id}}">{{$customers->cust_code}} -- {{$customers->cust_desc}}</option>
                  @endforeach
              </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="domain" class="col-md-2 col-form-label text-md-right">Domain</label>
            <div class="col-md-4">
               <select name="domain" id="domain" class="form-control">
                   <option value="">Select Data</option>
                   @foreach($domain as $domains)
                       <option value="{{$domains->id}}">{{$domains->domain_code}}</option>
                   @endforeach
               </select>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="bankname" class="col-md-2 col-form-label text-md-right">Bank Name</label>
            <div class="col-md-4">
               <input type="text" class="form-control" value="" name="bankname">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="bankacc" class="col-md-2 col-form-label text-md-right">Bank Account</label>
            <div class="col-md-4">
               <input type="text" class="form-control" value="" name="bankacc">
            </div>
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10">
                <div class="float-right">
                    <a href="{{route('bankcustomer.index')}}" id="btnback" class="btn btn-success bt-action">Back</a>
                    <button type="submit" class="btn btn-success bt-action btn-focus" id="btnconf">Save</button>
                    <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                        <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
    <script>
        $('#customer,#domain').select2({
            width : '100%',
        });
    </script>
@endsection