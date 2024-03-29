<div class="table-responsive col-lg-12 col-md-12 mt-3" style="overflow-x: scroll">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="10%">@sortablelink('truck_no_polis', 'Truck', ['filter' => 'active, visible'], ['class' => 'noHover', 'rel' => 'nofollow'])</th>
                <th width="10%">@sortablelink('driver_name', 'Driver', ['filter' => 'active, visible'], ['class' => 'noHover', 'rel' => 'nofollow'])</th>
                <th width="10%">@sortablelink('cicilan_eff_date', 'Eff Date', ['filter' => 'active, visible'], ['class' => 'noHover', 'rel' => 'nofollow'])</th>
                <th width="10%">@sortablelink('cicilan_nominal', 'Nominal', ['filter' => 'active, visible'], ['class' => 'noHover', 'rel' => 'nofollow'])</th>
                <th width="10%">Sisa Cicilan</th>
                <th width="25%">Remarks</th>
                <th width="10%">Status</th>
                <th width="10%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cicilan as $key => $datas)
                <tr>
                    <td data-label="Truck">{{ $datas->getDriverNopol->getTruck->truck_no_polis ?? '' }}</td>
                    <td data-label="Driver">{{ $datas->getDriverNopol->getDriver->driver_name ?? '' }}</td>
                    <td data-label="Tanggal Lapor">{{ $datas->cicilan_eff_date }}</td>
                    <td data-label="Nominal">
                        {{ number_format($datas->cicilan_nominal, 3) }}
                    </td>
                    <td data-label="Sisa Cicilan">
                        {{ number_format($datas->cicilan_nominal - $datas->getTotalPaidActive->sum('hc_nominal'), 3)}}
                    </td>
                    <td data-label="Catatan">{{ $datas->cicilan_remarks }}</td>
                    <td data-label="Status">{{ $datas->cicilan_is_active == 1 ? 'Active' : 'Not Active' }}</td>
                    <td class="row ml-1">
                        <a href="" class="viewmodal" data-toggle='modal' data-target="#viewModal"
                                data-id="{{ $datas->id }}" data-detail="{{$datas->getTotalPaid}}"
                                data-truck="{{$datas->getDriverNopol->getTruck->truck_no_polis ?? ''}}"
                                data-driver="{{$datas->getDriverNopol->getDriver->driver_name ?? ''}}"
                                data-total="{{number_format($datas->cicilan_nominal,3)}}">
                                <i class="fas fa-eye"></i></a>
                        @if ($datas->cicilan_is_active == 1)
                            <a href="{{ route('cicilanmt.edit', $datas->id) }}"><i class="fas fa-edit"></i></a>

                            <a href="" class="deletemodal" data-toggle='modal' data-target="#deleteModal"
                                data-id="{{ $datas->id }}">
                                <i class="fas fa-trash"></i></a>
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
    {!! $cicilan->appends(\Request::except('page'))->withQueryString()->render() !!}
</div>
