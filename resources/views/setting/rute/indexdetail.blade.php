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
<div id="myModal" class="modal fade" role="dialog" data-backdrop="static">
  <div class="modal-dialog">
    <!-- konten modal-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Create Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="panel-body">
        <!-- heading modal -->
        <form class="form-horizontal" role="form" method="POST" action="/newrute">
          {{ method_field('post') }}
          {{ csrf_field() }}
          <input type="hidden" name="tipecode" value="{{$id}}">
          <div class="modal-body">
            <div class="form-group row">
              <label for="role" class="col-md-3 col-form-label text-md-right">{{ __('Ship From') }}</label>
              <div class="col-md-7">
                <select id="shipfrom" class="form-control role" name="shipfrom" required autofocus>
                  <option value=""> Select Data </option>
                  @foreach($shipfrom as $sf)
                  <option value="{{$sf->id}}">{{$sf->sf_code}} -- {{$sf->sf_desc}}</option>
                  
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="shipto" class="col-md-3 col-form-label text-md-right">{{ __('Ship To') }}</label>
              <div class="col-md-7">
                <select id="shipto" class="form-control shipto" name="shipto" required autofocus>
                  <option value=""> Select Data </option>
                  @foreach($shipto as $st)
                    <option value="{{$st->id}}">{{$st->cs_shipto}} -- {{$st->cs_shipto_name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-info bt-action" id="btnclose" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success bt-action" id="btnconf">Save</button>
            <button type="button" class="btn bt-action" id="btnloading" style="display:none">
              <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="col-lg-12">
  <button class="btn btn-info bt-action newRole" data-toggle="modal" data-target="#myModal">
    Create Rute</button>
</div>
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
          {{$show->getTipe->tt_code ?? ''}} -- {{$show->getTipe->tt_desc ?? ''}}
        </td>
        <td>
          {{$show->getShipFrom->sf_code ?? ''}} -- {{$show->getShipFrom->sf_desc ?? ''}}
        </td>
        <td>
          {{$show->getShipTo->cs_shipto ?? ''}} -- {{$show->getShipTo->cs_shipto_name ?? ''}}
        </td>
        <td>
          <a href="/historydetail/{{$show->id}}" class="view"><i class="fas fa-eye"></i></a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>




@endsection


@section('scripts')

<script type="text/javascript">
  $('#shipfrom').select2({
    width: '100%'
  });
  $('#shipto').select2({
    width: '100%'
  });
    
  $('form').submit(function(e){
    document.getElementById('btnclose').style.display = 'none';
    document.getElementById('btnconf').style.display = 'none';
    document.getElementById('btnloading').style.display = '';
  })
  
</script>
@endsection