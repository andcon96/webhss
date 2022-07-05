<!DOCTYPE html>
<html>
<head>
  <title>Report Loosing HSST</title>
</head>
<body>
	<table>
        <thead>
            <tr>
                <th colspan="16" align="center">Rincian Sangu Loosing HSST</th>
            </tr>
            <tr>
                <th colspan="16">PERIODE : &nbsp; {{\Carbon\Carbon::parse($datefrom)->format('d M Y')}} - {{\Carbon\Carbon::parse($dateto)->format('d M Y')}}</th>
            </tr>
            <tr>
                <th><b>NO.POL</b></th>
                <th><b>NAMA SOPIR</b></th>
                @foreach ($period as $dt)
                    <th><b>{{$dt->format('d M Y')}}</b></th>
                @endforeach
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($listtruck as $trucks)
                <tr>
                    <td>{{$trucks->truck_no_polis}}</td>
                    <td>{{$trucks->getUserDriver->name ?? ''}}</td>
                    
                    @foreach ($period as $key => $dte)
                        @php($newdata = $data->where('sj_truck_id',$trucks->id)->where('sj_eff_date',$dte->format('Y-m-d'))->first())
                        <td>{{number_format($newdata->sangu ?? 0,2)}}</td>
                    @endforeach
                    
                    <td><b>{{number_format($data->where('sj_truck_id',$trucks->id)->sum('sangu') ?? 0 ,2)}}</b></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"><b>TOTAL</b></td>
                @foreach ($period as $dt)
                    <td><b>{{number_format($data->where('sj_eff_date',$dt->format('Y-m-d'))->sum('sangu') ?? 0 ,2)}}</b></td>
                @endforeach
                <td><b>{{number_format($data->sum('sangu') ?? 0 ,2)}}</b></td>
            </tr>
        </tfoot>
	</table>
</body>
</html>