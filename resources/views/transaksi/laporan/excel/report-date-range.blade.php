<!DOCTYPE html>
<html>
<head>
  <title>Report Range</title>
</head>
<body>
	<table>
        <thead>
            <tr>
                <th colspan="10" align="center">TOTALAN SOPIR</th>
            </tr>
            <tr>
                <th colspan="10">PERIODE : &nbsp; {{\Carbon\Carbon::parse($datefrom)->format('d M Y')}} - {{\Carbon\Carbon::parse($dateto)->format('d M Y')}}</th>
            </tr>
            <tr>
                <th colspan="2">TRUCK : &nbsp; <b>{{$nopol}}</b></th>
            </tr>
            <tr>
                <th><b>No.</b></th>
                <th><b>BRG</b></th>
                <th><b>TGL</b></th>
                <th><b>NAMA KAPAL</b></th>
                <th><b>TUJUAN</b></th>
                <th><b>ORDER</b></th>
                <th><b>RIT</b></th>
                <th><b>TARIF</b></th>
                <th><b>KOMISI</b></th>
                <th><b>HARGA</b></th>
            </tr>
        </thead>
        <tbody>
            {{$total = 0}}
            {{ $totalrit = 0 }}
            {{$totaldefault = 0}}
            @foreach($data as $keys => $datas)
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
                    <td>{{$keys + 1}}</td> 
                    <td>{{$datas->getSOMaster->getCOMaster->getBarang->barang_deskripsi ?? ''}}</td>
                    <td>{{$datas->sj_eff_date}}</td>
                    <td>{{$datas->getSOMaster->getCOMaster->co_kapal ?? ''}}</td>
                    <td>{{$datas->getSOMaster->getShipTo->cs_shipto_name}}</td>
                    <td>{{$datas->getSOMaster->getCOMaster->co_cust_code}} -- {{$datas->getSOMaster->getCOMaster->getCustomer->cust_desc ?? ''}}</td>
                    <td>{{$datas->sj_jmlh_trip}}</td>
                    <td>{{number_format($datas->getRuteHistory->history_sangu,0)}} </td>
                    <td class="angka">
                        {{number_format($bonus,0)}}
                    </td>
                    <td style="text-align:right">
                        {{number_format(($datas->getRuteHistory->history_sangu + $bonus) * $datas->sj_jmlh_trip,2)}}
                    </td>
                </tr>
                {{$totaldefault += ($datas->getRuteHistory->history_sangu + $bonus) * $datas->sj_jmlh_trip}}
                {{$total += $datas->sj_tot_sangu}}
                {{ $totalrit += $datas->sj_jmlh_trip }}
		    @endforeach

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
                {{-- @php($totalcicilan = 0) --}}
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
                <td colspan="6" style="text-align:right">Total</td>
                <td>:</td>
                <td colspan="2"></td>
                <td style="text-align:right"><b>{{number_format($totaldefault,2)}}</b></td>
            </tr>
            <tr>
                <td colspan="6" style="text-align:right">Sangu</td>
                <td>:</td>
                <td colspan="2"></td>
                <td style="text-align:right"><b>{{number_format($total,2)}}</b></td>
            </tr>
        </tfoot>
	</table>

    {{-- Biaya Tambahan --}}
    <table>
        <thead>
            <tr>
                <th colspan="10"><b>Biaya Tambahan</b></th>
            </tr>
            <tr>
                <th colspan="2"><b>Tanggal</b></th>
                <th colspan="2"><b>Keterangan</b></th>
                <th style="text-align:right"><b>Nominal</b></th>
            </tr>
        </thead>
        <tbody>
            {{$totalbiaya = 0}}
            {{$nominal = 0}}
            {{$totalClosedSPK = 0}}
            @foreach ($rbhist as $rbhists)
                {{  $nominal = 
                    $rbhists->rb_is_pemasukan == 1 ? 
                    -$rbhists->rb_nominal : 
                    $rbhists->rb_nominal }}
                
                @foreach($rbhists->getDetail as $details)
                    <tr>
                        <td colspan="2">{{$rbhists->rb_eff_date}}</td>
                        <td colspan="2">{{$details->rbd_deskripsi}}</td>
                        <td style="text-align:right;">{{number_format($details->rbd_nominal,2)}}</td>
                    </tr>
                @endforeach
                
                <tr>
                    <td colspan="4" style="font-weight:bold;text-align:center">{{$rbhists->rb_remark}}</td>
                    <td style="text-align:right">{{number_format($nominal,2)}}</td>
                </tr>
                {{$totalbiaya += (float)$nominal}}
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right">Total :</td>
                <td style="text-align:right"><b>{{number_format($totalbiaya,2)}}</b></td>
            </tr>
            <tr>
                <td colspan="9"></td>
                <td style="text-align:right"><b>{{number_format($totalbiaya,2)}}</b></td>
            </tr>
            <tr>
                <td colspan="8" style="text-align:right">Total</td>
                <td>:</td>
                <td style="text-align:right"><b>{{number_format($totaldefault - $total + $totalbiaya,2)}}</b></td>
                {{$totalClosedSPK = $totaldefault - $total + $totalbiaya}}
            </tr>
            
        </tfoot>
    </table>

    
    {{-- SPK Gantung --}}
    <table>
        <thead>
            <tr>
                <th colspan="10"><b>SPK Gantung</b></th>
            </tr>
            <tr>
                <th><b>No.</b></th>
                <th><b>BRG</b></th>
                <th><b>TGL</b></th>
                <th><b>NAMA KAPAL</b></th>
                <th><b>TUJUAN</b></th>
                <th><b>ORDER</b></th>
                <th><b>RIT</b></th>
                <th><b>TARIF</b></th>
                <th><b>KOMISI</b></th>
                <th><b>HARGA</b></th>
            </tr>
        </thead>
        <tbody>
            {{$total = 0}}
            {{$totaldefault = 0}}
            @foreach($openSPK as $keys => $datas)
                <tr>
                    <td>{{$keys + 1}}</td>
                    <td>{{$datas->getSOMaster->getCOMaster->getBarang->barang_deskripsi ?? ''}}</td>
                    <td>{{$datas->sj_eff_date}}</td>
                    <td>{{$datas->getSOMaster->getCOMaster->co_kapal ?? ''}}</td>
                    <td>{{$datas->getSOMaster->getShipTo->cs_shipto_name}}</td>
                    <td>{{$datas->getSOMaster->getCOMaster->co_cust_code}} -- {{$datas->getSOMaster->getCOMaster->getCustomer->cust_desc ?? ''}}</td>
                    <td>{{$datas->sj_jmlh_trip}}</td>
                    <td>{{number_format($datas->getRuteHistory->history_sangu,0)}} </td>
                    <td>{{number_format($datas->getRuteHistory->history_ongkos,0)}} </td>
                    <td style="text-align:right">
                        {{number_format(($datas->getRuteHistory->history_sangu + $datas->getRuteHistory->history_ongkos) * $datas->sj_jmlh_trip,2)}}
                    </td>
                </tr>
                {{$totaldefault += ($datas->getRuteHistory->history_sangu + $datas->getRuteHistory->history_ongkos) * $datas->sj_jmlh_trip}}
                {{$total += $datas->sj_tot_sangu}}
		    @endforeach
        </tbody>
		<tfoot>
            <tr>
                <td colspan="6" style="text-align:right">Total</td>
                <td>:</td>
                <td colspan="2"></td>
                <td style="text-align:right"><b>{{number_format($totaldefault,2)}}</b></td>
            </tr>
            <tr>
                <td colspan="6" style="text-align:right">Sangu</td>
                <td>:</td>
                <td colspan="2"></td>
                <td style="text-align:right"><b>{{number_format($total,2)}}</b></td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: right">Total Diterima</td>
                <td>:</td>
                <td colspan="2"></td>
                <td style="text-align:right"><b>{{number_format($totaldefault - $total,2)}}</b></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>