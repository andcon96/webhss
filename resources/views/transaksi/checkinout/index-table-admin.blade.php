<div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>No Polis</th>
                <th>Driver</th>
                <th>Check In / Out</th>
                <th>Jam Check In / Out</th>
                <th>History</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $datas)
                <tr>
                    <td>{{$datas->truck_no_polis}}</td>
                    @if($datas->getUserDriver)
                    <td>{{$datas->getUserDriver->name}}</td>
                    @else
                    <td>No Data</td>
                    @endif
                    @if($datas->getLastCheckInOut)
                        @if($datas->getLastCheckInOut->cio_is_check_in == 1)
                            <td style="color: green">Check In</td>
                        @else
                            <td style="color: red">Check Out</td>
                        @endif
                        <td>{{$datas->getLastCheckInOut->created_at}}</td>
                    @else 
                        <td>No Data</td>
                        <td>No Data</td>
                    @endif
                    <td>
                        <a href="{{route('checkinout.show',$datas->id) }}"><i class="fas fa-history"></i></a>
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