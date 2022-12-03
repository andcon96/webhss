<!DOCTYPE html>
<html>
<head>
  <title>Report Loosing HSST</title>
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

   table tr td {
       border: 1px solid black;
       line-height: 20px;
       padding-left: 5px;
       padding-right: 5px;
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
</style>
<body>
	<table>
        <thead>
            <tr>
                <th colspan="{{$tipe == 1 ? 12 : 11}}" class="judul">TOTALAN SOPIR {{$tipe == 1 ? 'LOOSING' : 'CONTAINER'}} {{strtoupper($domain)}}</th>
            </tr>
            <tr>
                <th colspan="{{$tipe == 1 ? 12 : 11}}" class="judul">PERIODE : &nbsp; {{\Carbon\Carbon::parse($datefrom)->format('d M Y')}} - {{\Carbon\Carbon::parse($dateto)->format('d M Y')}}</th>
            </tr>
            <tr>
                <th rowspan="2" style="vertical-align: top; text-align:center;"><b>NAMA SOPIR</b></th>
                <th rowspan="2" style="vertical-align: top; text-align:center;"><b>NO.POL</b></th>
                <th rowspan="2" style="vertical-align: top; text-align:center;"><b>BIAYA</b></th>
                <th colspan="3" style="text-align: center"><b>BON</b></th>
                <th rowspan="2" style="vertical-align: top; text-align:center;"><b>JUMLAH BON</b></th>
                <th rowspan="2" style="vertical-align: top; text-align:center;"><b>Sisa</b></th>
                <th colspan="2" style="text-align: center"><b>TABUNGAN</b></th>
                @if ($tipe == 1) {{-- Loosing --}}
                    <th rowspan="2" style="vertical-align: top; text-align:center;"><b>TANGGUNGAN</b></th>
                @endif
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
            @php($totalsisa = 0)
            @php($totaltanggungan = 0)
            @foreach ($listtruck as $trucks)
                @php($totalSanguTruck = $data->where('sj_truck_id',$trucks->id)->sum('totalSangu'))
                @php($defaultSanguTruck = $data->where('sj_truck_id',$trucks->id)->sum('defaultSangu'))
                @php($extraBiayaSangu = $cicilan->where('dn_truck_id',$trucks->id)->sum('total'))
                @php($sisa = $defaultSanguTruck - ($totalSanguTruck + $extraBiayaSangu))
                @php($sisa < 0 ? $totaltanggungan += $sisa : $totaltanggungan += 0)
                @php($totalsisa += $sisa)
                <tr>
                    <td>{{$trucks->getUserDriver->name ?? ''}}</td>
                    <td>{{$trucks->truck_no_polis}}</td>
                    <td>{{number_format($defaultSanguTruck,0)}}</td> <!-- Biaya, Default Sangu -->
                    <td>{{number_format($totalSanguTruck,0)}}</td>  <!-- Sangu, Sangu yang diberikan -->
                    <td>0</td>  <!-- Pribadi -->
                    <td>{{number_format($extraBiayaSangu,0)}}</td>  <!-- Klaim -->
                    <td>{{number_format($totalSanguTruck + $extraBiayaSangu ,0)}}</td> <!-- Jumlah BON, Sangu + Pribadi + Klaim -->
                    <td>{{number_format($sisa,0)}}</td> <!-- Sisa, Biaya - Jumlah Bon -->
                    <td>0</td> <!-- Tabungan Ke -->
                    <td>0</td> <!-- Tabungan Ke (Rp) -->
                    @if ($tipe == 1) {{-- Loosing --}}
                        <td>{{$sisa < 0 ? number_format($sisa,0) : 0}}</td> <!-- Tanggungan -->
                    @endif
                    <td>{{number_format($sisa,0)}}</td> <!-- Total  -->
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"></td>
                <td><b>{{number_format($data->sum('defaultSangu'),0)}}</b></td> <!-- Biaya, Default Sangu -->
                <td><b>{{number_format($data->sum('totalSangu'),0)}}</b></td>  <!-- Sangu, Sangu yang diberikan -->
                <td>0</td>
                <td><b>{{number_format($cicilan->sum('total'),0)}}</b></td> <!-- Klaim -->
                <td><b>{{number_format($data->sum('totalSangu') + $cicilan->sum('total'),0)}}</b></td> <!-- Jumlah BON, Sangu + Pribadi + Klaim -->
                <td><b>{{number_format($totalsisa),0}}</b></td> <!-- Sisa, Biaya - Jumlah Bon -->
                <td>0</td>
                <td>0</td>
                @if ($tipe == 1) {{-- Loosing --}}
                    <td><b>{{number_format($totaltanggungan)}}</b></td> <!-- Tanggungan -->
                @endif
                <td><b>{{number_format($totalsisa),0}}</b></td> <!-- Total  -->
            </tr>
        </tfoot>
	</table>
</body>
</html>