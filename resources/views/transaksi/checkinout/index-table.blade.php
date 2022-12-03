<div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>No Polis</th>
                <th>Driver</th>
                <th>Check In / Out</th>
                <th>Jam Check In / Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $datas)
                <tr>
                    <td data-label="No Polis">{{$datas->truck_no_polis}}</td>
                    <td data-label="Driver">{{$datas->getUserDriver->name ?? ''}}</td>
                    <td data-label="Aktivitas">
                        {{$datas->cio_is_check_in == 1 ? 'Check In' : 'Check Out'}}
                    </td>
                    <td data-label="Jam Aktivitas">{{$datas->created_at}}</td>
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