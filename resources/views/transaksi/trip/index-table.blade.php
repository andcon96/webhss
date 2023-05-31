<div class="table-responsive col-lg-12 col-md-12 mt-3"  style="overflow-x: scroll">
    <table class="table table-bordered mini-table" style="white-space: nowrap" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Sales Order</th>
                <th>SPK</th>
                <th>Customer</th>
                <th>Ship From</th>
                <th>Ship To</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>No Polis</th>
                <th>Default Sangu</th>
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
                    <td data-label="SO Customer">{{$datas->getSOMaster->getCOMaster->co_cust_code}} -- {{$datas->getSOMaster->getCOMaster->getCustomer->cust_desc}}</td>
                    <td data-label="Ship From">{{$datas->getSOMaster->getShipFrom->sf_code ?? ''}} -- {{$datas->getSOMaster->getShipFrom->sf_desc ?? ''}}</td>
                    <td data-label="SO Ship To">{{$datas->getSOMaster->so_ship_to ?? ''}} -- {{$datas->getSOMaster->getShipTo->cs_shipto_name ?? ''}}</td>
                    <td data-label="SO Due Date">{{$datas->getSOMaster->so_due_date}}</td>
                    <td data-label="SJ Status">{{$datas->sj_status}}</td>
                    <td data-label="No Polis">{{$datas->getTruck->truck_no_polis}}</td>
                    <td data-label="Defaul Sangu">{{number_format($datas->sj_default_sangu,0)}}</td>
                    <td data-label="Total Sangu">{{number_format($datas->sj_tot_sangu,0)}}</td>
                    <td data-label="Total Trip">{{$datas->sj_jmlh_trip}}</td>
                    <td data-label="Trip Dilaporkan">{{$datas->getHistTrip->count()}}</td>
                    <td data-label="Surat Jalan">{{$datas->getHistTrip->whereNotNull('sjh_remark')->count() == $datas->sj_jmlh_trip ? 'Yes' : 'No'}}</td>
                </tr>
            @empty
            <tr>
                <td colspan='11' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{$data->withQueryString()->render()}}
</div>