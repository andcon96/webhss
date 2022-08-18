<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="createTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="7%">Line</th>
                <th width="38%">Part</th>
                <th width="7%">UM</th>
                <th width="12%">Qty Order</th>
                <th width="12%">Qty Open</th>
                <th width="16%">Qty SJ</th>
            </tr>
        </thead>
        <tbody id="addtable">
            @forelse($data->getDetail as $key => $datas)
                <tr>
                    <td data-label="Line">{{$datas->sod_line}}</td>
                    <td data-label="Item">{{$datas->sod_part}} -- {{$datas->getItem->item_desc}}</td>
                    <td data-label="UM">{{$datas->sod_um}}</td>
                    <td data-label="Qty Ord">{{(int)$datas->sod_qty_ord}}</td>
                    <td data-label="Qty Open">{{$datas->sod_qty_ord - $datas->sod_qty_ship}}</td>
                    <td data-label="Qty SJ">
                        <input type="hidden" name="line[]" value="{{$datas->sod_line}}" 
                                {{$datas->sod_qty_ord - $datas->sod_qty_ship <= 0 ? 'Disabled' : '' }}>
                        <input type="hidden" name="part[]" value="{{$datas->sod_part}}" 
                                {{$datas->sod_qty_ord - $datas->sod_qty_ship <= 0 ? 'Disabled' : '' }}>
                        <input type="number" class="form-control qtysj" name="qtysj[]" 
                                value="{{$datas->sod_qty_ord - $datas->sod_qty_ship > 25000 
                                    ? 25000 
                                    : $datas->sod_qty_ord - $datas->sod_qty_ship}}" 
                                max="{{$datas->sod_qty_ord}}"
                                {{$datas->sod_qty_ord - $datas->sod_qty_ship <= 0 ? 'Disabled' : '' }}>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9"> No Data Avail</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>