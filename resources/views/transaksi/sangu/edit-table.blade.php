<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-3">
    <table class="table table-bordered edittable mini-table" id="editTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="10%">Line</th>
                <th>Part</th>
                <th>UM</th>
                <th>Qty Order</th>
                <th>Qty Ship</th>
            </tr>
        </thead>
        <tbody id="edittable">
            @forelse ($data->getDetail as $key => $datas)
            <tr>
                <input type="hidden" class="operation" value="M">
                <input type="hidden" value="{{$datas->id}}">
                <td data-label="Line"><input type="number" class="form-control" value="{{$datas->sod_line}}" readonly></td>
                <td data-label="Item Part"><input type="text" class="form-control" value="{{$datas->sod_part}}" readonly></td>
                <td data-label="UM"><input type="text" class="form-control" value="{{$datas->sod_um}}" readonly></td>
                <td data-label="Qty Order"><input type="number" class="form-control" value="{{$datas->sod_qty_ord}}" min="{{$datas->sod_qty_ship}}" readonly></td>
                <td data-label="Qty Ship"><input type="number" class="form-control" value="{{$datas->sod_qty_ship}}" readonly></td>
            </tr>
            @empty
            <tr>
                <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>