<div class="table-responsive offset-md-3 col-md-6 mt-3">
    <table class="table table-bordered drivertable mini-table" id="driverTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="60%">No Polis & Driver</th>
                <th width="20%">Jumlah Trip</th>
                <th width="20%">Sangu</th>
            </tr>
        </thead>
        <tbody id="driverbody">
            @forelse($data->getSangu as $key => $datas)
                <tr>
                    <td data-label="No Polis">{{ $datas->getTruckDriver->getTruck->truck_no_polis }} --
                        {{ $datas->getTruckDriver->getUser->name }}</td>
                    <td data-label="Jumlah Trip">{{ $datas->sos_tot_trip }}</td>
                    <td data-label="Sangu">{{ number_format($datas->sos_sangu, 0) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan='3' style="color:red;text-align:center;"> No Data Avail</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
