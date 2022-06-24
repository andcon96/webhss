<!DOCTYPE html>
<html>
<head>
  <title>Report Range</title>
</head>
<body>
	<table>
        <thead>
            <tr>
                <th colspan="8"><h2>TOTALAN SOPIR</h2></th>
            </tr>
            <tr>
                <th colspan="8"><h4>PERIODE : {{$datefrom}} - {{$dateto}}</h4></th>
            </tr>
            <tr>
                <th colspan="2"><h4>TRUCK : <b>{{$nopol}}</b></h4></th>
            </tr>
            <tr>
                <th><b>No.</b></th>
                <th><b>BRG</b></th>
                <th><b>TGL</b></th>
                <th><b>NAMA KAPAL</b></th>
                <th><b>TUJUAN</b></th>
                <th><b>ORDER</b></th>
                <th><b>RIT</b></th>
                <th><b>HARGA</b></th>
            </tr>
        </thead>
        <tbody>
            {{$total = 0}}
            @foreach($data as $keys => $datas)
                <tr>
                    <td>{{$keys + 1}}</td>
                    <td></td>
                    <td>{{$datas->sj_eff_date}}</td>
                    <td>KAPAL 1</td>
                    <td>{{$datas->getSOMaster->getShipTo->cs_shipto_name}}</td>
                    <td>FKS</td>
                    <td>1</td>
                    <td style="text-align:right">{{number_format($datas->sj_tot_sangu,2)}}</td>
                </tr>
                {{$total += $datas->sj_tot_sangu}}
		    @endforeach
        </tbody>
		<tfoot>
            <tr>
                <td colspan="7"></td>
                <td style="text-align:right"><b>{{number_format($total,2)}}</b></td>
            </tr>
            <tr>
                <td colspan="3" rowspan="3"><h2>BON</h2></td>
                <td>Pellet</td>
                <td></td>
            </tr>
            <tr>
                <td>Loosing</td>
                <td>{{$totalrb}}</td>
            </tr>
            <tr>
                <td>Oper-oper</td>
                <td></td>
            </tr>
        </tfoot>
	</table>
</body>
</html>