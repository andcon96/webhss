@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Struktur Kerusakan Maintenance</li>
</ol>
@endsection

@section('content')
      
    <form class="form-horizontal" method="POST" id="edit" action="{{route('strukturkerusakanmt.store')}}" onkeydown="return event.key != 'Enter';">
            {{ csrf_field() }}

        <div class="offset-md-2 col-8 form-group row">
            <table id='editTable' class='table table-striped table-bordered dataTable no-footer edit-list mini-table'>
                <thead>
                    <tr>
                        <th style="width:50%">Description </th>
                        <th style="width:25%">Order</th>
                        <th style="width:15%">Active</th>
                        <th style="width:10%">Action</th>
                    </tr>
                </thead>
                <tbody id='e_detailapp'>
                    @foreach($data as $datas)
                    <tr>
                        <td data-title="Deskripsi" data-label="% Start">
                            <input type="text" class="form-control form-control-sm" autocomplete="off" name="desc[]" value="{{$datas->ks_desc}}" style="height:37px" disabled/>
                            
                        </td>
                        <td data-title="Order" data-label="% Reward">
                            <input type="number" class="form-control form-control-sm total" autocomplete="off" name="order[]" style="height:37px" max="200" min="1" value="{{$datas->ks_order}}" step="1" disabled/>
                            
                        </td>
                        <td data-title="Active" data-label="% Active">
                            {{$datas->ks_isactive == 1 ? 'Yes' : 'No'}}
                        </td>
                        <td data-title="Action"><a type="button" href="/activestruc/{{$datas->id}}" class="ibtnDel btn btn-danger" style="color: white;">{{$datas->ks_isactive == 1 ? 'Deactivate' : 'Activate'}}</a></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <input type="button" class="btn btn-lg btn-block" 
                            id="addrowedit" value="Add Row" style="background-color:#1234A5; color:white; font-size:16px" />
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="offset-md-2 col-md-8 row" style="overflow:auto">
                <button type="submit" class="btn btn-success bt-action ml-auto"  data-toggle="modal" 
                data-target="#loadingtable" data-backdrop="static" data-keyboard="false" id="btnconf">Save</button>
        </div>
    </form>
@endsection


@section('scripts')
	<script>
		$(document).ready(function () {
	        var counter = 0;


	        $("table.order-list").on("click", ".ibtnDel", function (event) {
	            $(this).closest("tr").remove();       
	            counter -= 1
	        });


	        $("#addrowedit").on("click", function () {

	            var newRow = $("<tr>");
	            var cols = "";

	            cols += '<td data-title="Description" data-label="% Start"><input type="text" class="form-control form-control-sm" autocomplete="off" name="desc[]" value="" style="height:37px" required/>';
                cols += '</td>';
	            cols += '<td data-title="Order" data-label="% End"><input type="number" class="form-control form-control-sm total" autocomplete="off" name="order[]" style="height:37px" max="200" min="1" value="" step="1" required/>';
                cols += '</td>';
	            cols += '<td>-</td>'
                cols += '<td></td>'
	            cols += '</tr>'
	            newRow.append(cols);
	            $("table.edit-list").append(newRow);
	            counter++;
	        });




		});

	</script>
@endsection