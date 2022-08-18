<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-3">
    <table class="table table-bordered edittable" id="editTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="5%">Line</th>
                <th width="25%">Part</th>
                <th width="5%">UM</th>
                <th width="10%">Price</th>
                <th width="10%">Qty SJ</th>
                <th width="10%">Qty Angkut</th>
                <th width="10%">Qty Diakui</th>
            </tr>
        </thead>
        <tbody id="edittable">
            @forelse ($data->getDetail as $key => $datas)
            <tr>
                <td>{{$datas->sjd_line}}</td>
                <td>{{$datas->sjd_part}} -- {{$datas->getItem->item_desc}}</td>
                <td>{{$datas->getItem->item_um}}</td>
                <td>
                    @php($price = $invoiceprice->where('ip_cust_id',$data->getSOMaster->getCOMaster->getCustomer->id)
                                    ->where('ip_customership_id', $data->getSOMaster->getShipTo->id)
                                    ->where('ip_shipfrom_id', $data->getSOMaster->getShipFrom->id)
                                    ->first())
                    @php($newprice = $data->getSOMaster->getCOMaster->co_type == 'TRIP' ?
                                        $price->getActivePrice->iph_trip_price ?? 0: 
                                        $price->getActivePrice->iph_tonase_price ?? 0)
                    <input type="number" name="price[]" value="{{number_format($newprice,2)}}" class="form-control">
                </td>
                <td>{{$datas->sjd_qty_ship}}</td>
                <td>
                    <input type="number" name="qtyangkut[]" value="{{$datas->sjd_qty_ship}}" class="form-control">
                </td>
                <td>
                    <input type="hidden" name="iddetail[]" value="{{$datas->id}}">
                    <input type="hidden" name="part[]" value="{{$datas->sjd_part}}">
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