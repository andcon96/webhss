<!DOCTYPE html>
<html>
<head>
  <title>Report Loosing HSST</title>
</head>
<body>
	<table>
        <thead>
            <tr>
                <th colspan="16" align="center">TOTALAN SOPIR LOOSING HSST</th>
            </tr>
            <tr>
                <th colspan="16">PERIODE : &nbsp; {{\Carbon\Carbon::parse($datefrom)->format('d M Y')}} - {{\Carbon\Carbon::parse($dateto)->format('d M Y')}}</th>
            </tr>
            <tr>
                <th rowspan="2" style="vertical-align: top; text-align:center;"><b>NAMA SOPIR</b></th>
                <th rowspan="2" style="vertical-align: top; text-align:center;"><b>NO.POL</b></th>
                <th rowspan="2" style="vertical-align: top; text-align:center;"><b>BIAYA</b></th>
                <th colspan="3" style="text-align: center"><b>BON</b></th>
                <th rowspan="2" style="vertical-align: top; text-align:center;"><b>JUMLAH BON</b></th>
                <th rowspan="2" style="vertical-align: top; text-align:center;"><b>Sisa</b></th>
                <th colspan="2" style="text-align: center"><b>TABUNGAN</b></th>
                <th rowspan="2" style="vertical-align: top; text-align:center;"><b>TANGGUNGAN</b></th>
                <th rowspan="2" style="vertical-align: top; text-align:center;"><b>TOTAL</b></th>
            </tr>
            <tr>
                <th style="text-align: center"><b>SANGU</b></th>
                <th style="text-align: center"><b>PRIBADI</b></th>
                <th style="text-align: center"><b>KLAIM</b></th>
                <th style="text-align: center"><b>KE</b></th>
                <th style="text-align: center"><b>(Rp.)</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($listtruck as $trucks)
                @php($totalSanguTruck = $data->where('sj_truck_id',$trucks->id)->sum('totalSangu'))
                @php($defaultSanguTruck = $data->where('sj_truck_id',$trucks->id)->sum('defaultSangu'))
                @php($klaimTruck = $rbhist->where('rb_truck_id',$trucks->id)->sum('total'))
                <tr>
                    <td>{{$trucks->getUserDriver->name ?? ''}}</td>
                    <td>{{$trucks->truck_no_polis}}</td>
                    <td>{{number_format($totalSanguTruck,0)}}</td>
                    <td>{{number_format($defaultSanguTruck,0)}}</td>
                    <td>0</td>
                    <td>{{number_format($klaimTruck,0)}}</td>
                    <td>{{number_format($defaultSanguTruck + $klaimTruck,0)}}</td>
                    <td>{{number_format($totalSanguTruck - ($defaultSanguTruck + $klaimTruck),0)}}</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>{{number_format($totalSanguTruck - ($defaultSanguTruck + $klaimTruck),0)}}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"></td>
                <td><b>{{number_format($data->sum('totalSangu'),0)}}</b></td>
                <td><b>{{number_format($data->sum('defaultSangu'),0)}}</b></td>
                <td>0</td>
                <td><b>{{number_format($rbhist->sum('total'),0)}}</b></td>
                <td><b>{{number_format($data->sum('defaultSangu') + $rbhist->sum('total'),0)}}</b></td>
                <td><b>{{number_format($data->sum('totalSangu') - ($data->sum('defaultSangu') + $rbhist->sum('total')),0)}}</b></td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td><b>{{number_format($data->sum('totalSangu') - ($data->sum('defaultSangu') + $rbhist->sum('total')),0)}}</b></td>
            </tr>
        </tfoot>
	</table>
</body>
</html>