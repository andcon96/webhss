<div class="table-responsive offset-lg-2 col-lg-8 col-md-12 mt-3">
    <table class="table table-bordered edittable mini-table" id="editTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="40%">Check In / Out</th>
                <th>Tanggal & Jam</th>
            </tr>
        </thead>
        <tbody id="edittable">
            @forelse ($detail as $key => $datas)
            <tr>
                <td>
                    {{$datas->cio_is_check_in ? "Check In" : "Check Out"}}
                </td>
                <td>
                    {{$datas->created_at}}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{$detail->links()}}
</div>