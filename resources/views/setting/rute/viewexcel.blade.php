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

          
       

<h2><center>Hasil Excel</center></h2>
<form class="form-horizontal" method="post" action="/importrute">
    {{ method_field('post') }}
    {{ csrf_field() }}
<div class="col-md-12" style="display:block;max-height:30ch;overflow-y:scroll;white-space:nowrap">
    <div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
        <tr>
            <th>Tipe</th>
            <th>Ship From</th>
            <th>Ship To</th>
            <th>Harga</th>
            <th>Ongkos</th>
            <th>Sangu</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($rute_collect as $index => $show)
            <tr>
                
              <td>
                {{$show['tipe'] ?? ''}}
                
              </td>
              <td>
                {{$show['shipfrom'] ?? ''}}
                
              </td>
              <td>
                {{$show["shipto"] ?? ''}}
                
              </td>
              <td>
                {{$show["harga"] ?? ''}}
                
              </td>
              <td>
                {{$show["ongkos"] ?? ''}}
                
              </td>
              <td>
                {{$show["sangu"] ?? ''}}
                
              </td>
              
            </tr>
            @endforeach
        </tbody>
    </table>

    
    
    </div>

</div>
<div class="col-md-12 mt-5">
    <input type="hidden" name="tipe" value="{{$stringtipe}}">
    <input type="hidden" name="shipfrom" value="{{$stringshipfrom}}">
    <input type="hidden" name="shipto" value="{{$stringshipto}}">
    <input type="hidden" name="harga" value="{{$stringharga}}">
    <input type="hidden" name="ongkos" value="{{$stringongkos}}">
    <input type="hidden" name="sangu" value="{{$stringsangu}}">
    <button class="btn bt-action" style="float: right;" >
        <b>Import</b>
      </button>
</div>
</form>

@endsection


@section('scripts')

<script type="text/javascript">
</script>
@endsection