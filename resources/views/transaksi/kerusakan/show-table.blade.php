<div class="table-responsive offset-lg-2 col-lg-8 col-md-12 mt-3">
    <table class="table table-bordered edittable" id="editTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="60%">Jenis Kerusakan</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody id="edittable">
            @forelse ($data->getDetail as $key => $datas)
            <tr>
                <td>
                    <input type="text" class="form-control" value="{{$datas->getKerusakan->kerusakan_code}} -- {{$datas->getKerusakan->kerusakan_desc}}" readonly>
                </td>
                <td>
                    <textarea type="text" class="form-control" readonly>{{$datas->krd_note}}</textarea>
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