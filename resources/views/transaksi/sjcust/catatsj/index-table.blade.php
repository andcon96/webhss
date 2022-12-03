<div class="table-responsive offset-lg-2 col-lg-8 col-md-12 mt-3">
    <table class="table table-bordered edittable" id="editTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="15%">Trip Ke</th>
                <th>No Polis & Driver</th>
                <th>Waktu Pencatatan</th>
                <th>Surat jalan</th>
            </tr>
        </thead>
        @php($trip = 1)
        @php($driver = '')
        @php($totaltrip = 0)
        @php($ongoingtrip = 0)
        <tbody id="edittable">
            @forelse ($data->getHistTrip as $key => $datas)

            <tr>
                <td>{{$key + 1}}</td>
                <td>{{$datas->getTruck->truck_no_polis}} -- {{$datas->getTruck->getUserDriver->name ?? ''}}</td>
                <td>{{$datas->created_at}}</td>
                <td>
                    <input type="hidden" name="idsangu[]" value="{{$data->id}}">
                    <input type="hidden" name="idhist[]" value="{{$datas->id}}">
                    <input type="text" class="form-control" name="sj[]" value="{{$datas->sjh_remark}}" >
                </td>
            </tr>

            @empty
            <tr>
                <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
