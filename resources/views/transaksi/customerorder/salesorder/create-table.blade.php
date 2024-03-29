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
                    <td data-label="Line">{{$datas->cod_line}}</td>
                    <td data-label="Item">{{$datas->cod_part}} -- {{$datas->getItem->item_desc ?? ''}}</td>
                    <td data-label="UM">{{$datas->getItem->item_um}}</td>
                    <td data-label="Qty Ord">{{$datas->cod_qty_ord}}</td>
                    <td data-label="Qty Open">{{$datas->cod_qty_ord - $datas->cod_qty_used}}</td>
                    <td data-label="Qty SO">
                        <input type="hidden" name="operation[]" class="operation" value="M"
                            {{ $datas->cod_qty_ord - $datas->cod_qty_used == 0 ? 'disabled' : '' }}>
                        <input type="hidden" name="idcodetail[]" value="{{$datas->id}}"
                            {{ $datas->cod_qty_ord - $datas->cod_qty_used == 0 ? 'disabled' : '' }}>
                        <input type="hidden" name="line[]" value="{{$datas->cod_line}}"
                            {{ $datas->cod_qty_ord - $datas->cod_qty_used == 0 ? 'disabled' : '' }}>
                        <input type="hidden" name="um[]" value="{{$datas->getItem->item_um}}"
                            {{ $datas->cod_qty_ord - $datas->cod_qty_used == 0 ? 'disabled' : '' }}>
                        <input type="hidden" name="part[]" class="operation" value="{{$datas->cod_part}}"
                            {{ $datas->cod_qty_ord - $datas->cod_qty_used == 0 ? 'disabled' : '' }}>
                        <input type="number" class="form-control" name="qtyord[]" 
                            value="{{$datas->cod_qty_ord - $datas->cod_qty_used}}" 
                            max="{{$datas->cod_qty_ord - $datas->cod_qty_used}}"
                            {{ $datas->cod_qty_ord - $datas->cod_qty_used == 0 ? 'disabled' : '' }}>
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