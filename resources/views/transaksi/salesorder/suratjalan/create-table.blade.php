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
                    <td>{{$datas->sod_line}}</td>
                    <td>{{$datas->sod_part}} -- {{$datas->getItem->item_desc}}</td>
                    <td>{{$datas->sod_um}}</td>
                    <td>{{$datas->sod_qty_ord}}</td>
                    <td>{{$datas->sod_qty_ord - $datas->sod_qty_ship}}</td>
                    <td>
                        <input type="hidden" name="line[]" value="{{$datas->sod_line}}">
                        <input type="hidden" name="part[]" value="{{$datas->sod_part}}">
                        <input type="number" class="form-control qtysj" name="qtysj[]" value="{{$datas->sod_qty_ord - $datas->sod_qty_ship}}" max="{{$datas->sod_qty_ord}}">
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