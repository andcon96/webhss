<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-3">
    <table class="table table-bordered edittable" id="editTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="5%">Line</th>
                <th width="25%">Part</th>
                <th width="5%">UM</th>
                <th width="10%">Qty SJ</th>
                <th width="10%">Qty Diakui</th>
            </tr>
        </thead>
        <tbody id="edittable">
            @forelse ($data->getDetail as $key => $datas)
            <tr>
                <td>{{$datas->sjd_line}}</td>
                <td>{{$datas->sjd_part}} -- {{$datas->getItem->item_desc}}</td>
                <td>{{$datas->getItem->item_um}}</td>
                <td>{{$datas->sjd_qty_ship}}</td>
                <td>
                    <input type="hidden" name="iddetail[]" value="{{$datas->id}}">
                    <input type="number" name="qtyakui[]" value="{{$datas->sjd_qty_ship}}" class="form-control">
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