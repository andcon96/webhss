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
        <th>Truck Type Code</th>
        <th>Truck Type Desc</th>
        <th>Active</th>
        <th width="8%">View</th>
        
      </tr>
    </thead>
    <tbody>
      @foreach ($truck_type as $index => $show)
      <tr>
        <td>
          {{$show->tt_code}}
        </td>
        <td>
          {{$show->tt_desc}}
        </td>
        <td>
          @if($show->tt_isactive == 1)
            Yes
          @else
            No
          @endif
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