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

          
       


<div class="col-md-12" style="display:block;max-height:30ch;overflow-y:scroll;white-space:nowrap">
    <h2><center>History</center></h2>
    <div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
        <tr>
            <th>Tipe</th>
            <th>Ship From</th>
            <th>Ship To</th>
            <th>Harga</th>
            <th>Sangu</th>
            <th>Ongkos</th>
            <th>Active</th>
            <th>Last Active</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($history_data as $index => $show)
            <tr>
                
              <td>
                {{$show->getRute->getTipe->tt_desc ?? ''}}
              </td>
              <td>
                {{$show->getRute->getShipFrom->sf_desc ?? ''}}
              </td>
              <td>
                {{$show->getRute->getShipTo->cs_shipto_name ?? ''}}
              </td>
              <td>
                {{number_format($show->history_harga) ?? ''}}
              </td>
              <td>
                {{number_format($show->history_sangu) ?? ''}}
              </td>
              <td>
                {{number_format($show->history_ongkos) ?? ''}}
              </td>
              <td>
                {{$show->history_is_active ?? ''}}
              </td>
              <td>
                {{$show->history_last_active ?? ''}}
              </td>
              
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>

</div>

<div class="col-md-12" >
<form class="form-horizontal" method="post" action="{{route('rute.store')}}">
    {{ method_field('post') }}
    {{ csrf_field() }}

    <h2 class="mt-5"><center>Update Rute</center></h2>
        <div class="col-12 form-group row mt-3" >
                
                <div class="modal-body">
                  <div class="form-group row">
                    <label for="tipe" class="col-md-3 col-form-label text-md-right" style="color: black">Tipe</label>
                    <div class="col-md-5 {{ $errors->has('uname') ? 'has-error' : '' }}">
                      <input id="tipe" type="text" class="form-control" value="{{ $rute->getTipe->tt_desc }}" readonly>
                      <input type="hidden" name="idrute" value="{{$rute->id}}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="shipfrom" class="col-md-3 col-form-label text-md-right" style="color: black">Ship From</label>
                    <div class="col-md-5">
                      <input id="shipfrom" type="text" class="form-control" value="{{ $rute->getShipFrom->sf_desc }}"  readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="shipto" class="col-md-3 col-form-label text-md-right" style="color: black">Ship To</label>
                    <div class="col-md-7">
                        <input id="shipto" type="text" class="form-control"  value="{{ $rute->getShipTo->cs_shipto_name }}" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="harga" class="col-md-3 col-form-label text-md-right" style="color: black">Harga</label>
                    <div class="col-md-7">
                        <input id="harga" type="number" class="form-control" name="harga" step=".01" value="0" min="0" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="sangu" class="col-md-3 col-form-label text-md-right" style="color: black">Sangu</label>
                    <div class="col-md-7">
                        <input id="sangu" type="text" class="form-control" name="sangu" step=".01" value="0" min="0" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="ongkos" class="col-md-3 col-form-label text-md-right" style="color: black">Ongkos</label>
                    <div class="col-md-7">
                        <input id="ongkos" type="text" class="form-control" name="ongkos" step=".01" value="0" min="0" required>
                    </div>
                  </div>
        
        
                <div class="modal-footer">
                  <a href="/rute/rutedetail/{{$id}}" class="btn btn-success bt-action" id="btnback" color="white">Back</a>
                  <button type="submit" class="btn btn-success bt-action" id="btnconf" >Save</button>
                  <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                    <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                  </button>
                </div>
                </div>
        </div>

</form>
</div>
@endsection


@section('scripts')

<script type="text/javascript">
      $(document).on('keyup', '#ongkos', function() {
        letterRegex = /[^\0-9\,]/;
        var data = $(this).val();

        var newdata = data.replace(/([^0-9])/g, '');

        console.log(Number(newdata).toLocaleString('en-US'));

        $(this).val(Number(newdata).toLocaleString('en-US'));
    });
    $(document).on('keyup', '#sangu', function() {
        letterRegex = /[^\0-9\,]/;
        var data = $(this).val();

        var newdata = data.replace(/([^0-9])/g, '');

        console.log(Number(newdata).toLocaleString('en-US'));

        $(this).val(Number(newdata).toLocaleString('en-US'));
    });

    $('form').submit(function(e){
      document.getElementById('btnloading').style.display = '';
      document.getElementById('btnconf').style.display = 'none';
      document.getElementById('btnback').style.display = 'none';
    });


</script>
@endsection