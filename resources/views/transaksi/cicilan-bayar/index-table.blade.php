<div class="table-responsive col-lg-12 col-md-12 mt-3" style="overflow-x: scroll">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="10%">@sortablelink('truck_no_polis', 'Truck', ['filter' => 'active, visible'], ['class' => 'noHover', 'rel' => 'nofollow'])</th>
                <th width="10%">@sortablelink('driver_name', 'Driver', ['filter' => 'active, visible'], ['class' => 'noHover', 'rel' => 'nofollow'])</th>
                <th width="10%">@sortablelink('cicilan_eff_date', 'Eff Date', ['filter' => 'active, visible'], ['class' => 'noHover', 'rel' => 'nofollow'])</th>
                <th width="15%">@sortablelink('cicilan_nominal', 'Nominal', ['filter' => 'active, visible'], ['class' => 'noHover', 'rel' => 'nofollow'])</th>
                <th width="15%">Nominal Paid</th>
                <th width="30%">Remarks</th>
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
                        {{ number_format($datas->cicilan_nominal, 0) }}
                    </td>
                    <td data-label="Nominal Paid">
                        {{ number_format($datas->getTotalPaid->sum('hc_nominal', 0)) }}
                    </td>
                    <td data-label="Catatan">{{ $datas->cicilan_remarks }}</td>
                    <td class="row ml-1">
                        <a href="{{ route('bayarcicilanmt.edit', $datas->id) }}"><i class="fas fa-money-bill"></i></a>
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
