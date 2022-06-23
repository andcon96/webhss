<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-3">
    <table class="table table-bordered edittable mini-table" id="editTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="10%">Line</th>
                <th width="40%">Part</th>
                <th width="10%">UM</th>
                <th width="10%">Qty Open SO</th>
                <th width="10%">Qty Order</th>
                <th width="10%">Qty Ship</th>
                <th width="10%">Delete</th>
            </tr>
        </thead>
        <tbody id="edittable">
            @forelse ($data->getDetail as $key => $datas)
            <tr>
                @php($qtyco = 0)
                @php($sodetail = $data->getSOMaster->getDetail->where('sod_part',$datas->sjd_part)->where('sod_line',$datas->sjd_line)->first())
                @php($sisa = $sodetail->sod_qty_ord - $sodetail->sod_qty_ship + $datas->sjd_qty_ship)
                
                <input type="hidden" name="operation[]" class="operation" value="M">
                <input type="hidden" name="iddetail[]" value="{{$datas->id}}">
                <input type="hidden" name="idsodetail[]" value="{{$sodetail->id}}">
                <td data-label="Line"><input type="number" class="form-control" value="{{$datas->sjd_line}}" name="line[]" readonly></td>
                <td data-label="Item Part">
                    <input type="hidden" value="{{$datas->sjd_part}}" name="part[]">
                    <input type="text" class="form-control" value="{{$datas->sjd_part}} - {{$datas->getItem->item_desc}}" readonly>
                </td>
                <td data-label="UM"><input type="text" class="form-control" value="{{$datas->getItem->item_um}}" name="um[]" readonly></td>
                <td data-label="Qty Open CO">
                    <input type="text" class="form-control" value="{{$sisa}}" name="sisa[]" readonly>
                </td>
                <td data-label="Qty Order">
                    <input type="number" class="form-control" value="{{$datas->sjd_qty_ship}}" name="qtyord[]" min="{{$datas->sjd_qty_conf}}" max="{{$sisa}}">
                    <input type="hidden" class="form-control" value="{{$datas->sjd_qty_ship}}" name="qtyold[]" min="{{$datas->sjd_qty_conf}}" max="{{$sisa}}">
                </td>
                <td data-label="Qty Ship">
                    <input type="number" class="form-control" value="{{$datas->sjd_qty_conf}}" name="qtyship[]" readonly>
                </td>
                <td data-label="Delete" style="vertical-align:middle;text-align:center;"> 
                    @if($datas->sjd_qty_conf != 0)
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