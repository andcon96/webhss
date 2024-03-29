<div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Nomor Kerusakan</th>
                <th>Truck</th>
                <th>Gandengan</th>
                <th>Driver</th>
                <th>Kilometer</th>
                <th>Tanggal Lapor</th>
                <th>Jam Lapor</th>
                
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            
            @forelse ($data as $key => $datas)
            
                @if($datas->getTruck != null || $datas->getGandeng != null)
            
                    <tr>
                        <td data-label="Nomor Kerusakan">{{$datas->kr_nbr}}</td>
                        <td data-label="No Polis">{{isset($datas->getTruck->truck_no_polis) ? $datas->getTruck->truck_no_polis : ''}}</td>
                        <td data-label="Gandengan">{{isset($datas->getGandeng->gandeng_code) ? $datas->getGandeng->gandeng_code : ''}}</td>
                        <td data-label="Driver">{{isset($datas->getTruck->getUserDriver->name) ? $datas->getTruck->getUserDriver->name : '' }}</td>
                        <td data-label="Km">{{isset($datas->kr_km) ? $datas->kr_km : '' }}</td>
                        <td data-label="Tanggal Lapor">{{$datas->kr_date}}</td>
                        <td data-label="Jam Lapor">{{substr($datas->created_at,11)}}</td>
                        <td data-label="Status">{{$datas->kr_status}}</td>
                        
                        <td data-label="Action">
                            <a href="{{route('laporkerusakan.show',$datas->id) }}"><i class="fas fa-eye"></i></a>
                            @if(($datas->kr_status == 'New' || $datas->kr_status == 'WIP') && $access == 'yes')
                            <a href="{{route('laporkerusakan.edit',$datas->id) }}"><i class="fas fa-edit"></i></a>
                            
                            @endif
                            @if($datas->kr_status == 'WIP')
                                <a href="{{route('assignRemarks',$datas->id) }}"><i class="fas fa-book"></i></a>
                            @endif
                            @if($datas->kr_status == 'WIP' || $datas->kr_status == 'Close' || $datas->kr_status == 'Done')
                                <a href="{{route('krhistview',$datas->id) }}"><i class="fas fa-history"></i></a>
                            @endif
                            @if($datas->kr_status == 'WIP' )
                                <a href="" class="confirmModal" 
                                    data-id="{{$datas->id}}" data-krnbr="{{$datas->kr_nbr}}"
                                    data-toggle='modal' data-target="#confirmModal"><i class="fas fa-check-circle"></i></a>
                            @endif
                            @if($datas->kr_status == 'New')
                            <a href="{{route('assignKR',$datas->id) }}"><i class="fas fa-tasks"></i></a>
                            @if($access == 'yes')
                            <a href="" class="deleteModal" 
                                data-id="{{$datas->id}}" data-krnbr="{{$datas->kr_nbr}}"
                                data-toggle='modal' data-target="#deleteModal"><i class="fas fa-trash"></i></a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endif
            @empty
            <tr>
                <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            
            @endforelse
        </tbody>
    </table>
    {{$data->withQueryString()->links()}}
</div>