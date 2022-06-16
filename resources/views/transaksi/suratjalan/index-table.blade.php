<div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th style="width:15%">Sales Order</th>
                <th style="width:25%">Customer</th>
                <th style="width:10%">Ship To</th>
                <th style="width:10%">Ship From</th>
                <th style="width:15%">Due Date</th>
                <th style="width:10%">SO Status</th>
                <th style="width:10%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $datas)
            <tr>
                <td>{{$datas->getMaster->so_nbr}}</td>
                <td>{{$datas->getMaster->so_cust}} -- {{$datas->getMaster->getCustomer->cust_desc}}</td>
                <td>{{$datas->getMaster->so_ship_to}}</td>
                <td>{{$datas->getMaster->so_ship_from}}</td>
                <td>{{$datas->getMaster->so_due_date}}</td>
                <td>{{$datas->getMaster->so_status}}</td>
                <td>
                    <a href="{{ route('LaporSJ', ['so' => $datas->getMaster->id, 'truck' => $datas->getTruckDriver->getTruck->id]) }}">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('CatatSJ', ['so' => $datas->getMaster->id, 'truck' => $datas->getTruckDriver->getTruck->id]) }}">
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