@extends('layout.layout')

@section('menu_name','Rute Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
  <li class="breadcrumb-item active">Rute Maintenance</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->
<div class="form-group row col-md-12">
  <div class="col-md-2 ml-3 mt-2" >
    <form action="/importexcel" method="post" enctype="multipart/form-data">
      {{ method_field('post') }}
      {{ csrf_field() }}
      <input type="file" name="btnexcel" id="btnexcel" style="display: none;">
      <label for="btnexcel" style="color: green; margin-top: 0.45rem;" name="btnexcel">
        <i class="fas fa-upload"></i> Upload Excel
      </label>
    </form>
    
    
  </div>
  
  <div class="col-md-2 mt-2">
    <form class="form-horizontal" method="get" action="/downloadexcel">
      {{ method_field('get') }}
      {{ csrf_field() }}
      <button class="btn bt-action mb-3" style="justify-content: center" >
        <b>Template</b>
      </button>
    </form>
    
    
  </div>
  

</div>
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
          <a href="rute/rutedetail/{{$show->id}}" class="view"><i class="fas fa-eye"></i></a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>




@endsection


@section('scripts')

<script type="text/javascript">
    $('#btnexcel').change(function () {
      $(this).closest('form').submit();
    })
</script>
@endsection