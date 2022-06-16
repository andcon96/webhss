<div class="table-responsive offset-lg-3 col-lg-6 col-md-12 mt-3">
    <table class="table table-bordered edittable mini-table" id="editTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="30%">Trip Ke</th>
                <th>No Polis & Driver</th>
                <th>Waktu Pencatatan</th>
            </tr>
        </thead>
        @php($trip = 1)
        @php($driver = '')
        @php($totaltrip = 0)
        @php($ongoingtrip = 0)
        <tbody id="edittable">
            @forelse ($sohbyso as $key => $datas)
            @if($datas->soh_driver != $driver && $driver != '')
            <tr>
                <td colspan="3" style="text-align: right;" data-label="Total Trip"><b>{{$ongoingtrip}} / {{$totaltrip}}</b></td>
            </tr>
            @endif
            @if($datas->soh_driver != $driver)
            @php($trip = 1)
            @php($ongoingtrip = 0)
            <tr>
                <td colspan="3"><b>No Polis : {{$datas->getTruckDriver->getTruck->truck_no_polis}}</b></td>
            </tr>
            @endif

            <tr>
                <td>Trip ke {{$trip}}</td>
                <td data-label="No Polis & Driver">{{$datas->getTruckDriver->getTruck->truck_no_polis}} -- {{$datas->getTruckDriver->getUser->name}}</td>
                <td data-label="Waktu Pencatatan">{{$datas->tglhist}}</td>
            </tr>

            @php($trip += 1)
            @php($driver = $datas->soh_driver)
            @php($totaltrip = $datas->sos_tot_trip)
            @php($ongoingtrip += 1)

            @if($loop->last)
            <tr>
                <td colspan="3" style="text-align: right;"><b>{{$ongoingtrip}} / {{$totaltrip}}</b></td>
            </tr>
            @endif


            @empty
            <tr>
                <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>