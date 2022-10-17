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
                @forelse($sohbyso->where('sjh_truck',$datas->sj_truck_id)->where('sjh_sj_mstr_id',$datas->id) as $flag => $sohbysos)
                <tr>
                    <td>Trip ke {{$trip}}</td>
                    <td data-label="No Polis & Driver">{{$sohbysos->getTruck->truck_no_polis}} -- {{$sohbysos->getTruck->getUserDriver->name ?? ''}}</td>
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
                        {{$datas->getTruck->getUserDriver->username ?? ''}} - 
                        {{$datas->getTruck->getUserDriver->name ?? ''}} --
                        {{$datas->getTruck->truck_no_polis}} 
                    </td>
                    <td style="font-weight: bold">
                        {{$ongoingtrip}} / {{$datas->sj_jmlh_trip}}
                    </td>
                </tr>

                @php($trip = 1)
                @php($ongoingtrip = 0)
            @endforeach
        </tbody>
    </table>
</div>