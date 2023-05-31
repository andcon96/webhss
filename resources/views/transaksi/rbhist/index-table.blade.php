<div class="table-responsive col-lg-10 offset-lg-1 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="15%">Truck</th>
                <th width="15%">Tanggal Lapor</th>
                <th width="15%">Nominal</th>
                <th>Catatan</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $datas)
            <tr>
                <td data-label="Truck">{{$datas->getTruck->truck_no_polis ?? ''}}</td>
                <td data-label="Tanggal Lapor">{{$datas->rb_eff_date}}</td>
                <td data-label="Nominal">
                    {{number_format($datas->getDetail->sum('rbd_nominal'),2)}}
                </td>
                <td data-label="Catatan">{{$datas->rb_remark}}</td>
                <td data-label="Status">{{$datas->rb_is_active == 1 ? 'Active' : 'Deleted'}}</td>
                <td class="row ml-1">
                    <a href="{{route('rbhist.edit', $datas->id)}}"><i class="fas fa-edit"></i></a>

                    @if($datas->rb_is_active == 1)
                    <form action="{{route('rbhist.destroy',$datas->id)}}" method="POST" id="submit">
                        @csrf
                        @method('delete')
                        <a href="" class="deleteModal"  id="btnconf"
                            data-id="{{$datas->id}}" data-sonbr="{{$datas->sj_nbr}}">
                            <i class="fas fa-trash"></i></a>
                    </form>
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