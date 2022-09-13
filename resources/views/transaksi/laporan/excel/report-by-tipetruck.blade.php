<!DOCTYPE html>
<html>

<head>
    <title>Report By Tipe Truck</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="11" align="center">Report Truck {{ $tipetruck->tt_desc }}</th>
            </tr>
            <tr>
                <th colspan="11">PERIODE : &nbsp; {{ \Carbon\Carbon::parse($datefrom)->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse($dateto)->format('d M Y') }}</th>
            </tr>
            <tr>
                <th><b>No.</b></th>
                <th><b>No Polis</b></th>
                <th><b>SO Number</b></th>
                <th><b>SPK Number</b></th>
                <th><b>Tanggal SPK</b></th>
                <th><b>Status</b></th>
                <th><b>Customer</b></th>
                <th><b>Ship From</b></th>
                <th><b>Ship To</b></th>
                <th><b>Default Sangu</b></th>
                <th><b>Sangu yang diberikan</b></th>
            </tr>
        </thead>
        <tbody>
            @php($truckid = '')
            @php($totaldefaultsangu = 0)
            @php($totalsangudiberikan = 0)
            @foreach ($suratjalan as $keys => $datas)
                @if (($truckid != '' && $truckid != $datas->sj_truck_id))
                    <tr>
                        <td colspan="9" style="text-align: right"><b>Sub Total</b></td>
                        <td><b>{{number_format($totaldefaultsangu,0)}}</b></td>
                        <td><b>{{number_format($totalsangudiberikan,0)}}</b></td>
                    </tr>
                    @php($totaldefaultsangu = 0)
                    @php($totalsangudiberikan = 0)
                @endif
                <tr>
                    <td>{{ $keys + 1 }}</td>
                    <td>{{$datas->getTruck->truck_no_polis ?? ''}}</td>
                    <td>{{$datas->getSOMaster->so_nbr ?? ''}}</td>
                    <td>{{$datas->sj_nbr}}</td>
                    <td>{{$datas->sj_eff_date}}</td>
                    <td>{{$datas->sj_status}}</td>
                    <td>{{$datas->getSOMaster->getCOMaster->getCustomer->cust_code}} -- {{$datas->getSOMaster->getCOMaster->getCustomer->cust_desc ?? ''}}</td>
                    <td>{{$datas->getSOMaster->getShipFrom->sf_code ?? ''}} -- {{$datas->getSOMaster->getShipFrom->sf_desc ?? ''}}</td>
                    <td>{{$datas->getSOMaster->getShipTo->cs_shipto}} -- {{$datas->getSOMaster->getShipTo->cs_shipto_name}}</td>
                    <td>{{number_format($datas->sj_default_sangu,0)}}</td>
                    <td>{{number_format($datas->sj_tot_sangu,0)}}</td>
                </tr>

                @php($truckid = $datas->sj_truck_id)
                @php($totaldefaultsangu += $datas->sj_default_sangu)
                @php($totalsangudiberikan += $datas->sj_tot_sangu)

                @if($loop->last)
                    <tr>
                        <td colspan="9"  style="text-align: right"><b>Sub Total</b></td>
                        <td><b>{{number_format($totaldefaultsangu,0)}}</b></td>
                        <td><b>{{number_format($totalsangudiberikan,0)}}</b></td>
                    </tr>
                    <tr>
                        <td colspan="9"  style="text-align: right"><b>Total</b></td>
                        <td><b>{{number_format($suratjalan->sum('sj_default_sangu'),0)}}</b></td>
                        <td><b>{{number_format($suratjalan->sum('sj_tot_sangu'),0)}}</b></td>
                    </tr>
                    @php($totaldefaultsangu = 0)
                    @php($totalsangudiberikan = 0)
                @endif
            @endforeach
        </tbody>
    </table>

</body>

</html>
