<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="createTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="7%">Line</th>
                <th width="50%">Part</th>
                <th width="7%">UM</th>
                <th width="12%">Qty CO</th>
                <th width="12%">Sisa Qty CO</th>
                <th width="12%">Qty SO</th>
            </tr>
        </thead>
        <tbody id="addtable">
            @forelse($data->getDetail as $key => $datas)
                <tr>
                    <td>{{$datas->cod_line}}</td>
                    <td>{{$datas->cod_part}} -- {{$datas->getItem->item_desc ?? ''}}</td>
                    <td>{{$datas->getItem->item_um}}</td>
                    <td>{{$datas->cod_qty_ord}}</td>
                    <td>{{$datas->cod_qty_ord - $datas->cod_qty_used}}</td>
                    <td>
                        <input type="hidden" name="operation[]" class="operation" value="M">
                        <input type="hidden" name="idcodetail[]" value="{{$datas->id}}">
                        <input type="hidden" name="line[]" value="{{$datas->cod_line}}">
                        <input type="hidden" name="um[]" value="{{$datas->getItem->item_um}}">
                        <input type="hidden" name="part[]" class="operation" value="{{$datas->cod_part}}">
                        <input type="number" class="form-control" name="qtyord[]" value="{{$datas->cod_qty_ord - $datas->cod_qty_used}}" max="{{$datas->cod_qty_ord - $datas->cod_qty_used}}">
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5"> No Data Avail </td>
                </tr>

            @endforelse
        </tbody>
    </table>
</div>