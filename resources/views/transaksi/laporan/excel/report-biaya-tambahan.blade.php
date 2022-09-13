<!DOCTYPE html>
<html>

<head>
    <title>Report Biaya Tambahan</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="7" align="center">Report Biaya Tambahan</th>
            </tr>
            <tr>
                <th colspan="7">PERIODE : &nbsp; {{ \Carbon\Carbon::parse($datefrom)->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse($dateto)->format('d M Y') }}</th>
            </tr>
            <tr>
                <th><b>No.</b></th>
                <th><b>No Polis</b></th>
                <th><b>Tanggal</b></th>
                <th><b>Keterangan</b></th>
                <th><b>Rit</b></th>
                <th><b>Biaya Tambahan</b></th>
                <th><b>Total</b></th>
            </tr>
        </thead>
        <tbody>
            @php($idrbhist = '')
            @foreach($rbhist as $key => $datas)
               @php($total = 0)
               @if($idrbhist == '' || $idrbhist != $datas->rb_truck_id)
                  <tr>
                     <td>{{$key+1}}</td>
                     <td colspan="6">{{$datas->getTruck->truck_no_polis}}</td>
                  </tr>
               @endif

               @foreach($datas->getDetail as $keys => $details)
                  @php($total += $details->rbd_nominal)
                  <tr>
                     <td></td>
                     <td></td>
                     <td>{{$datas->rb_eff_date}}</td>
                     <td>{{$details->rbd_deskripsi}}</td>
                     <td>1</td>
                     <td>{{number_format($details->rbd_nominal,0)}}</td>
                     <td>{{number_format($details->rbd_nominal,0)}}</td>
                  </tr>

                  @if($loop->last)
                     <tr>
                        <td colspan="6" align="right"><b>Total</b></td>
                        <td><b>{{number_format($total,0)}}</b></td>
                     </tr>
                  @endif
               @endforeach
               
               @php($idrbhist = $datas->rb_truck_id)
            @endforeach
        </tbody>
    </table>
</body>

</html>
