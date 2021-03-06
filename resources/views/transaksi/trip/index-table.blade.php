<div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Sales Order</th>
                <th>Surat Jalan</th>
                <th>Customer</th>
                <th>Type</th>
                <th>Ship To</th>
                <th>Due Date</th>
                <th>Total Sangu</th>
                <th>Total Trip</th>
                <th>Trip Dilaporkan</th>
                <th>Surat Jalan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $datas)
                <tr>
                    <td data-label="SO Number">{{$datas->getSOMaster->so_nbr}}</td>
                    <td data-label="SJ Number">{{$datas->sj_nbr}}</td>
                    <td data-label="SO Customer">{{$datas->getSOMaster->getCOMaster->co_cust_code}}</td>
                    <td data-label="SO Type">{{$datas->getSOMaster->getCOMaster->getCustomer->cust_desc}}</td>
                    <td data-label="SO Ship To">{{$datas->getSOMaster->so_ship_to}}</td>
                    <td data-label="SO Due Date">{{$datas->getSOMaster->so_due_date}}</td>
                    <td data-label="Total Sangu">{{number_format($datas->sj_tot_sangu,0)}}</td>
                    <td data-label="Total Trip">{{$datas->sj_jmlh_trip}}</td>
                    <td data-label="Trip Dilaporkan">{{$datas->getHistTrip->count()}}</td>
                    <td data-label="Surat Jalan">{{$datas->getHistTrip->whereNotNull('soh_sj')->count() > 0 ? 'Yes' : 'No'}}</td>
                </tr>
            @empty
            <tr>
                <td colspan='9' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>