<!DOCTYPE html>
<html>

<head>
    <title>Report Totalan Sopir</title>
</head>

<style>
    body {
        font-size: 12px;
        font-family: 'Calibri', Helvetica, sans-serif;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    table tr th {
        border: 1px solid black;
        line-height: 25px;
        padding-left: 5px;
    }

    table tbody tr td {
        border: 1px solid black;
        font-size: 10px !important;
        line-height: 20px;
        padding-left: 5px;
        padding-right: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: clip;
    }

    table tfoot tr td {
        border: 1px solid black;
        font-size: 10px !important;
        padding: 0px;
        margin: 0px;
        white-space: nowrap;
        text-overflow: clip;
    }

    table thead tr th {
        text-align: left;
    }

    .judul {
        text-align: center;
        vertical-align: top;
    }

    .head {
        text-align: center !important;
    }

    .head:after {
        content: "@";
        display: block;
        line-height: 15px;
        text-indent: -99999px;
    }

    .angka {
        text-align: right;
    }
    .middle{
        text-align: center;
    }
    
    .left-border{
        border: none;
        border-left: 1px solid black;
    }
    .right-border{
        border: none;
        border-right: 1px solid black;
    }
    .bottom-border{
        border-bottom: 1px solid black;
    }
    .no-border{
        border:none;
    }
</style>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="10" class="head" style="font-size: 18px;">TOTALAN SOPIR</th>
            </tr>
            <tr>
                <th colspan="3" class="left-border">Nama</th>
                <th colspan="7" class="right-border">: &nbsp; {{ $driver->driver_name }}</th>
            </tr>
            <tr>
                <th colspan="3" class="left-border">No. Polis</th>
                <th colspan="7" class="right-border">: &nbsp; {{ $nopol }}</th>
            </tr>
            <tr>
                <th colspan="3" class="left-border">No. Lambung</th>
                <th colspan="7" class="right-border">:</th>
            </tr>
            <tr>
                <th colspan="3" class="left-border">No. Periode</th>
                <th colspan="7" class="right-border">: &nbsp;{{ \Carbon\Carbon::parse($datefrom)->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse($dateto)->format('d M Y') }}</th>
            </tr>
            <tr>
                <th class="judul"><b>No.</b></th>
                <th class="judul"><b>BARANG</b></th>
                <th class="judul"><b>TGL</b></th>
                <th class="judul"><b>KAPAL</b></th>
                <th class="judul"><b>TUJUAN</b></th>
                <th class="judul"><b>ORDER</b></th>
                <th class="judul"><b>RIT</b></th>
                <th class="judul"><b>SANGU</b></th>
                <th class="judul"><b>KOMISI</b></th>
                <th class="judul"><b>HARGA</b></th>
            </tr>
        </thead>
        <tbody>
            {{ $total = 0 }}
            {{ $totaldefault = 0 }}
            {{ $nominal = 0 }}
            {{ $no = 1 }}
            {{ $totalrit = 0 }}
            @foreach ($data as $keys => $datas)
                {{ $bonus = 0 }}
                {{-- Cek Tipe Loosing ato Container --}}
                @if($datas->getRuteHistory->history_ongkos == 0)
                    @if($datas->getBonusBarang && $datas->getSOMaster->getCOMaster->co_has_bonus == 1)
                    {{ $bonus = $datas->getBonusBarang->bb_price * floor($datas->getDetail->sum('sjd_qty_conf') / 1000)  }}
                    @endif
                @else
                    {{ $bonus = $datas->getRuteHistory->history_ongkos }} 
                @endif

                <tr>
                    <td class="middle">{{ $no }}</td>
                    <td>{{ $datas->getSOMaster->getCOMaster->getBarang->barang_deskripsi ?? '' }}</td>
                    <td>{{ $datas->sj_eff_date }}</td>
                    <td>{{ $datas->getSOMaster->getCOMaster->co_kapal ?? ''}}</td>
                    <td>{{ $datas->getSOMaster->getShipTo->cs_shipto_name }}</td>
                    <td>{{ $datas->getSOMaster->getCOMaster->getCustomer->cust_desc ?? '' }}</td>
                    <td class="middle">{{ $datas->sj_jmlh_trip }}</td>
                    <td class="angka">{{ number_format($datas->getRuteHistory->history_sangu, 0) }} </td>
                    <td class="angka">
                        {{number_format($bonus,0)}}
                    </td>
                    <td class="angka">
                        {{ number_format(($datas->getRuteHistory->history_sangu + $bonus) * $datas->sj_jmlh_trip, 0) }}
                    </td>
                </tr>
                {{ $totaldefault += ($datas->getRuteHistory->history_sangu + $bonus) * $datas->sj_jmlh_trip }}
                {{ $total += $datas->sj_tot_sangu }}
                {{ $no++ }}
                {{ $totalrit += $datas->sj_jmlh_trip }}
            @endforeach

            @if($tipetruck != 5 && $tipetruck != 6)
            @foreach ($rbhist as $rbhists)
                {{ $nominal = $rbhists->rb_is_pemasukan == 1 ? -$rbhists->rb_nominal : $rbhists->rb_nominal }}
                <tr>
                    <td class="middle">{{ $no }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ $rbhists->rb_remark }}</td>
                    <td></td>
                    <td class="middle">1</td>
                    <td class="angka">{{ number_format($nominal, 0) }}</td>
                    <td></td>
                    <td class="angka">{{ number_format($nominal, 0) }}</td>
                </tr>
                {{ $totaldefault += (float) $nominal }}
                {{ $no++ }}
                {{ $totalrit += 1 }}
            @endforeach
            @endif

            {{ $bonsopir = 0 }}
            <tr>
                <td colspan="6">RIT</td>
                <td class="middle">{{$totalrit}}</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="6">Total</td>
                <td class="middle">:</td>
                <td colspan="2"></td>
                <td style="text-align:right"><b>{{ number_format($totaldefault, 0) }}</b></td>
            </tr>
            <tr>
                <td colspan="6">Sangu</td>
                <td class="middle">:</td>
                <td colspan="2"></td>
                <td style="text-align:right"><b>{{ number_format($total, 0) }}</b></td>
            </tr>
            <tr>
                <td colspan="6">Totalan Sopir</td>
                <td class="middle">:</td>
                <td colspan="2"></td>
                <td style="text-align:right"><b>{{ number_format($totaldefault - $total, 0) }}</b></td>
            </tr>
            <tr>
                <td colspan="6">Bon Sopir</td>
                <td class="middle">:</td>
                <td colspan="2"></td>
                <td style="text-align:right">{{$bonsopir}}</td>
            </tr>
            <tr>
                <td colspan="6">Sisa Totalan</td>
                <td class="middle">:</td>
                <td colspan="2"></td>
                <td style="text-align:right"><b>{{ number_format($totaldefault - $total - $bonsopir, 0) }}</b></td>
            </tr>
            <tr>
                @php($totalcicilan = $histcicilan->sum('hc_nominal'))
                <td colspan="6">Cicilan</td>
                <td class="middle">:</td>
                <td colspan="2"></td>
                <td style="text-align:right"><b>{{ number_format($totalcicilan, 0) }}</b></td>
            </tr>
            <tr>
                <td colspan="6">Tabungan</td>
                <td class="middle">:</td>
                <td colspan="2"></td>
                <td style="text-align:right"></td>
            </tr>
            <tr>
                <td colspan="6">Uang Diterima Supir</td>
                <td class="middle">:</td>
                <td colspan="2"></td>
                <td style="text-align:right"><b>{{ number_format($totaldefault - $total - $totalcicilan, 0) }}</b></td>
            </tr>
        </tbody>
        <tfoot> 
            <tr>
                <td colspan="10">
                    <table class="insideTable">
                        <tr>
                            <td colspan="5" class="middle right-border bottom-border">
                                Menyetujui,
                            </td>
                            <td colspan="5" class="middle left-border bottom-border">
                                Penerima,
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="middle right-border" style="line-height: 100px;">
                                _______________
                            </td>
                            <td colspan="5" class="middle left-border" style="line-height: 100px;">
                                _______________
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
