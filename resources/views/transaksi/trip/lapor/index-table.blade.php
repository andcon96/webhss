<div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Nomor SO</th>
                <th>Nomor SPK</th>
                <th>Customer</th>
                <th>Total Sangu</th>
                <th>Ship To</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php($totalsangu = 0)
            @forelse ($data as $key => $datas)
            @php($totalsangu += $datas->sj_tot_sangu)
            <tr>
                <td data-label="SO Nbr">{{$datas->getSOMaster->so_nbr}}</td>
                <td data-label="SJ Nbr">{{$datas->sj_nbr}}</td>
                <td data-label="Customer">{{$datas->getSOMaster->getCOMaster->co_cust_code}} 
                    - {{$datas->getSOMaster->getCOmaster->getCustomer->cust_desc}}</td>
                <td data-label="Sangu">{{number_format($datas->sj_tot_sangu,0)}}</td>
                <td data-label="Ship To">{{$datas->getSOMaster->so_ship_to}} -- {{$datas->getSOMaster->getShipTo->cs_shipto_name}}</td>
                <td data-label="Status">{{$datas->sj_status}}</td>
                <td data-label="Due Date">{{$datas->getSOMaster->so_due_date}}</td>
                <td data-label="Action">
                    <a href="{{route('laportrip.edit',$datas->id) }}">
                        <i class="fas fa-sticky-note"></i>
                    </a>
                </td>
            </tr>
        @empty
        <tr>
            <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
        </tr>
        @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><b>Total Sangu</b></td>
                <td><b>{{ number_format($totalsangu,0) }}</b></td>
                <td colspan="5"></td>
            </tr>
        </tfoot>
    </table>
</div>