<div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Nomor SO</th>
                <th>Customer</th>
                <th>Type</th>
                <th>Ship-From</th>
                <th>Ship-To</th>
                <th>Due Date</th>
                <th>Status</th>
                <th width="10%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $datas)
            <tr>
                <td data-label="SO Number">{{$datas->so_nbr}}</td>
                <td data-label="SO Customer">{{$datas->so_cust}}</td>
                <td data-label="SO Type">{{$datas->so_type}}</td>
                <td data-label="SO Ship From">{{$datas->so_ship_from}}</td>
                <td data-label="SO Ship To">{{$datas->so_ship_to}}</td>
                <td data-label="SO Due Date">{{$datas->so_due_date}}</td>
                <td data-label="SO Status">{{$datas->so_status}}</td>
                <td>
                    <a href="{{route('sosangu.show',$datas->id) }}"><i class="fas fa-eye"></i></a>
                    @if($datas->new_open_so)
                    <a href="{{route('sosangu.edit',$datas->id) }}"><i class="fas fa-edit"></i></a>
                    @endif
                </td>
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