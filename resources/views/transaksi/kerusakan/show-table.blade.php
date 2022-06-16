<div class="table-responsive offset-lg-2 col-lg-8 col-md-12 mt-3">
    <table class="table table-bordered edittable" id="editTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="80%">Jenis Kerusakan</th>
            </tr>
        </thead>
        <tbody id="edittable">
            @forelse ($data->getDetail as $key => $datas)
            <tr>
                <td>
                    <input type="text" class="form-control" value="{{$datas->getKerusakan->kerusakan_code}} -- {{$datas->getKerusakan->kerusakan_desc}}" readonly>
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