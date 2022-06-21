@extends('layout.layout')

@section('menu_name','Role Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
  <li class="breadcrumb-item active">Rute Maintenance</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->

<div class="table-responsive col-lg-12 col-md-12 mt-3">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th>Tipe Truck</th>
        <th>Ship From</th>
        <th>Ship To</th>
        <th width="8%">View</th>
        
      </tr>
    </thead>
    <tbody>
      @foreach ($rute_data as $index => $show)
      <tr>
        <td>
          {{$show->getTipe->tt_desc}}
        </td>
        <td>
          {{$show->getShipFrom->sf_desc}}
        </td>
        <td>
          {{$show->getShipTo->cs_shipto_name}}
        </td>
        <td>
          <a href="rutedetail/{{$show->id}}" class="view"><i class="fas fa-eye"></i></a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>




@endsection


@section('scripts')

<script type="text/javascript">
</script>
@endsection