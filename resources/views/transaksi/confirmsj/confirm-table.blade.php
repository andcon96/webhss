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
            @php($sodetail = $data->getSOMaster->getDetail->where('sod_part',$datas->sjd_part)->where('sod_line',$datas->sjd_line)->first())
                
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

                    <select id="harga" class="form-control harga" required>
                        <option value="">Select Data</option>
                        @if($price)
                        @foreach($price->getAllActivePrice as $keys => $harga)
                            @php($hargapakai = $data->getSOMaster->getCOMaster->co_type == 'TRIP' ? $harga->iph_trip_price : $harga->iph_tonase_price)
                            <option value="{{$hargapakai}}">
                                {{$hargapakai}}
                            </option>
                        @endforeach
                        @endif
                        <option value="Custom">Custom Price</option>
                    </select>

                    <input type="number" name="price[]" step="0.01" value="" readonly class="form-control price mt-2" required>
                    {{-- perubahan harga langsung --}}
                    @if($price)
                        @foreach($price->getAllActivePrice as $keys => $harga)
                            @php($hargapakai = $data->getSOMaster->getCOMaster->co_type == 'TRIP' ? $harga->iph_trip_price : $harga->iph_tonase_price)
                            <input type="hidden" name="price[]" value="{{$hargapakai}}">
                        @endforeach
                    @endif
                </td>
                <td>
                    <input type="hidden" value="{{$datas->sjd_qty_ship}}" class="form-control oldship" readonly>
                    <input type="number" name="qtyship[]" value="{{$datas->sjd_qty_ship}}" class="form-control qtyship" readonly>
                </td>
                <td>
                    <input type="number" name="qtyangkut[]" value="{{$datas->sjd_qty_angkut == 0 ? $datas->sjd_qty_ship : $datas->sjd_qty_angkut}}" class="form-control" readonly>
                </td>
                <td>
                    <input type="hidden" name="iddetail[]" value="{{$datas->id}}">
                    <input type="hidden" name="idsodetail[]" value="{{$sodetail->id}}">
                    <input type="hidden" name="part[]" value="{{$datas->sjd_part}}">
                    <input type="number" name="qtyakui[]" value="{{$datas->sjd_qty_akui == 0 ? $datas->sjd_qty_ship : $datas->sjd_qty_akui}}" class="form-control" readonly>
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