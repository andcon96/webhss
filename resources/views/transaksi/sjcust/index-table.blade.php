<div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th style="width:10%">Sales Order</th>
                <th style="width:10%">Surat Jalan</th>
                <th style="width:25%">Customer</th>
                <th style="width:10%">Ship To</th>
                <th style="width:10%">Ship From</th>
                <th style="width:10%">Due Date</th>
                <th style="width:10%">SJ Status</th>
                <th style="width:10%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $datas)
            <tr>
                <td>{{$datas->getSOMaster->so_nbr}}</td>
                <td>{{$datas->sj_nbr}}</td>
                <td>{{$datas->getSOMaster->getCOMaster->co_cust_code}} -- 
                    {{$datas->getSOMaster->getCOMaster->getCustomer->cust_desc}}</td>
                <td>{{$datas->getSOMaster->so_ship_to}}</td>
                <td>{{$datas->getSOMaster->so_ship_from}}</td>
                <td>{{$datas->getSOMaster->so_due_date}}</td>
                <td>{{$datas->sj_status}}</td>
                <td>
                    @if($datas->sj_status == 'Selesai')
                        <a href="{{ route('LaporSJ', ['sj' => $datas->id, 'truck' => $datas->sj_truck_id]) }}">
                            <i class="fas fa-truck"></i>
                        </a>
                    @endif
                    
                    <a href="{{ route('CatatSJ', ['sj' => $datas->id, 'truck' => $datas->sj_truck_id]) }}">
                        <i class="fas fa-book"></i>
                    </a>
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