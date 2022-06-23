<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-3">
    <table class="table table-bordered edittable mini-table" id="editTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="10%">Line</th>
                <th width="40%">Part</th>
                <th width="10%">UM</th>
                <th width="10%">Qty Ship</th>
                <th width="10%">Qty Confirm</th>
            </tr>
        </thead>
        <tbody id="edittable">
            @forelse ($data->getDetail as $key => $datas)
            <tr>
                <input type="hidden" name="operation[]" class="operation" value="M">
                <input type="hidden" name="iddetail[]" value="{{$datas->id}}">
                <td data-label="Line"><input type="number" class="form-control" value="{{$datas->sjd_line}}" name="line[]" readonly></td>
                <td data-label="Item Part">
                    <input type="hidden" value="{{$datas->sjd_part}}" name="part[]">
                    <input type="text" class="form-control" value="{{$datas->sjd_part}} - {{$datas->getItem->item_desc}}" readonly>
                </td>
                <td data-label="UM"><input type="text" class="form-control" value="{{$datas->getItem->item_um}}" name="um[]" readonly></td>
                <td data-label="Qty Order">
                    <input type="number" class="form-control" value="{{$datas->sjd_qty_ship}}" name="qtyord[]" readonly>
                    <input type="hidden" class="form-control" value="{{$datas->sjd_qty_ship}}" name="qtyold[]" readonly>
                </td>
                <td data-label="Qty Ship">
                    <input type="number" class="form-control" value="{{$datas->sjd_qty_ship}}" name="qtyship[]">
                </td>
            </tr>
            @empty
            <tr>
                <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>