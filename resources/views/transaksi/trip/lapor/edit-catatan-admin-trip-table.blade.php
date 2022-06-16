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
        @php($ongoingtrip = 0)
        <tbody id="edittable">
            @foreach($listdriver as $key => $datas)
                @forelse($sohbyso->where('soh_driver',$datas->sos_truck) as $flag => $sohbysos)
                <tr>
                    <td>Trip ke {{$trip}}</td>
                    <td data-label="No Polis & Driver">{{$sohbysos->getTruckDriver->getTruck->truck_no_polis}} -- {{$sohbysos->getTruckDriver->getUser->name}}</td>
                    <td data-label="Waktu Pencatatan">{{$sohbysos->tglhist}}</td>
                </tr>
                @php($trip += 1)
                @php($ongoingtrip += 1)
                @empty
                <tr>
                    <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
                </tr>
                @endforelse
                <tr>
                    <td colspan="2" style="font-weight: bold">
                        {{$datas->getTruckDriver->getUser->username}} - 
                        {{$datas->getTruckDriver->getUser->name}} --
                        {{$datas->getTruckDriver->getTruck->truck_no_polis}} 
                    </td>
                    <td style="font-weight: bold">
                        {{$ongoingtrip}} / {{$datas->sos_tot_trip}}
                    </td>
                </tr>

                @php($trip = 1)
                @php($ongoingtrip = 0)
            @endforeach
        </tbody>
    </table>
</div>