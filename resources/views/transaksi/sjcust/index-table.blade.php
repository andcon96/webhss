<div class="table-responsive col-lg-12 col-md-12 mt-3" style="overflow-x:auto;">
    <table class="table table-bordered mini-table" id="dataTable">
        <thead>
            <tr>
                <th style="width:10%">Sales Order</th>
                <th style="width:10%">SPK</th>
                <th style="width:10%">Truck</th>
                <th style="width:10%">Kapal</th>
                <th style="width:20%">Customer</th>
                <th style="width:15%">Ship To</th>
                <th style="width:15%">Ship From</th>
                <th style="width:10%">Eff Date SPK</th>
                <th style="width:10%">Due Date SO</th>
                <th style="width:10%">SJ Status</th>
                <th style="width:10%">CO Remarks</th>
                <th style="width:10%">SO Remarks</th>
                <th style="width:10%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $datas)
            <tr>
                <td>{{$datas->getSOMaster->so_nbr}}</td>
                <td>{{$datas->sj_nbr}}</td>
                <td>{{$datas->getTruck->truck_no_polis ?? ''}}</td>
                <td>{{$datas->getSOMaster->getCOMaster->co_kapal}}</td>
                <td>{{$datas->getSOMaster->getCOMaster->co_cust_code}} -- 
                    {{$datas->getSOMaster->getCOMaster->getCustomer->cust_desc}}</td>
                <td>{{$datas->getSOMaster->so_ship_to}} -- {{$datas->getSOMaster->getShipTo->cs_shipto_name ?? ''}}</td>
                <td>{{$datas->getSOMaster->so_ship_from}} -- {{$datas->getSOMaster->getShipFrom->sf_desc ?? ''}}</td>
                <td>{{$datas->sj_eff_date}}</td>
                <td>{{$datas->getSOMaster->so_due_date}}</td>
                <td>{{$datas->sj_status}}</td>
                <td>{{$datas->getSOMaster->getCOMaster->co_remark}}</td>
                <td>{{$datas->getSOMaster->so_remark}}</td>
                <td>
                    @if($datas->sj_status == 'Selesai' || $datas->sj_status == 'Open')
                        <a href="{{ route('LaporSJ', ['sj' => $datas->id, 'truck' => $datas->sj_truck_id]) }}">
                            <i class="fas fa-truck"></i>
                        </a>
                    @endif
                    
                    {{-- <a href="{{ route('CatatSJ', ['sj' => $datas->id, 'truck' => $datas->sj_truck_id]) }}">
                        <i class="fas fa-book"></i>
                    </a> --}}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{$data->withQueryString()->links()}}
</div>