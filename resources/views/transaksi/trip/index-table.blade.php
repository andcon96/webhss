<div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Sales Order</th>
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
                    <td data-label="SO Number">{{$datas->getMaster->so_nbr}}</td>
                    <td data-label="SO Customer">{{$datas->getMaster->so_cust}}</td>
                    <td data-label="SO Type">{{$datas->getMaster->so_type}}</td>
                    <td data-label="SO Ship To">{{$datas->getMaster->so_ship_to}}</td>
                    <td data-label="SO Due Date">{{$datas->getMaster->so_due_date}}</td>
                    <td data-label="Total Sangu">{{number_format($datas->sos_sangu,0)}}</td>
                    <td data-label="Total Trip">{{$datas->sos_tot_trip}}</td>
                    <td data-label="Trip Dilaporkan">{{$datas->countLaporanHist->count()}}</td>
                    <td data-label="Surat Jalan">{{$datas->countLaporanHist->whereNotNull('soh_sj')->count() > 0 ? 'Yes' : 'No'}}</td>
                </tr>
            @empty
            <tr>
                <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>