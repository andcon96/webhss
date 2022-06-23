<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-3">
    <table class="table table-bordered edittable mini-table" id="editTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="10%">Line</th>
                <th width="40%">Part</th>
                <th width="10%">Qty Order</th>
                <th width="10%">Qty Open</th>
                <th width="10%">Qty New</th>
                <th width="10%">Delete</th>
            </tr>
        </thead>
        <tbody id="edittable">
            @forelse ($data->getDetail as $key => $datas)
            <tr>
                <input type="hidden" name="operation[]" class="operation" value="M">
                <input type="hidden" name="iddetail[]" value="{{$datas->id}}">
                <td data-label="Line"><input type="number" class="form-control" value="{{$datas->cod_line}}" name="line[]" readonly></td>
                <td data-label="Item Part">
                    <input type="hidden" value="{{$datas->cod_part}}" name="part[]">
                    <input type="text" class="form-control" value="{{$datas->cod_part}} - {{$datas->getItem->item_desc ?? ''}}" readonly>
                </td>
                <td data-label="Qty Order">
                    <input type="text" class="form-control" value="{{(int)$datas->cod_qty_ord}}" name="qtyord[]" readonly>
                </td>
                <td data-label="Qty Open">
                    <input type="text" class="form-control" value="{{$datas->cod_qty_ord - $datas->cod_qty_used}}" name="qtyopen[]" readonly>
                </td>
                <td data-label="Qty Order">
                    <input type="number" class="form-control" value="{{(int)$datas->cod_qty_ord}}" name="qtynew[]" min="{{$datas->cod_qty_used}}">
                </td>
                <td data-label="Delete" style="vertical-align:middle;text-align:center;"> 
                    @if($datas->sod_qty_ship != 0)
                        <input type="checkbox" class="qaddel" value="" disabled> 
                    @else
                        <input type="checkbox" class="qaddel" value="Y" name="qaddel[]"> 
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" class="text-center">
                    <span class="btn btn-info bt-action" id="addrow">Add Row</span>
                </td>
            </tr>
        </tfoot>
    </table>
</div>