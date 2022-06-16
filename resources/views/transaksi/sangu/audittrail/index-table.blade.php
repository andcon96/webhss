<div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Nomor SO</th>
                <th>No Polis</th>
                <th>Customer</th>
                <th>Total Sangu</th>
                <th>Ship To</th>
                <th>Status</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $datas)
            <tr>
                <td data-label="SO Nbr">{{$datas->getMaster->so_nbr}}</td>
                <td data-label="No Polis">{{$datas->getTruckDriver->getTruck->truck_no_polis}}</td>
                <td data-label="Customer">{{$datas->getMaster->so_cust}} -- {{$datas->getMaster->getCustomer->cust_desc ?? ''}}</td>
                <td data-label="Sangu">{{number_format($datas->sos_sangu,0)}}</td>
                <td data-label="Ship To">{{$datas->getMaster->so_ship_to}}</td>
                <td data-label="Status">{{$datas->getMaster->so_status}}</td>
                <td data-label="Due Date">{{$datas->getMaster->so_due_date}}</td>
            </tr>
            @empty
            <tr>
                <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>