<!DOCTYPE html>
<html>

<head>
    <title>Report Invoice ASA</title>
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

    tbody{
        border: 1px solid black;
    }

    table tr td {
        /* border: 1px solid black; */
        vertical-align: middle;
        line-height: 30px;
        padding-left: 5px;
        padding-right: 5px;
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

    .middle {
        text-align: center;
    }

    .left-border {
        border: none;
        border-left: 1px solid black;
    }

    .right-border {
        border: none;
        border-right: 1px solid black;
    }
</style>

<body>
    <table>
        <tbody>
            <tr>
                <td width="25%">Kwitansi No.</td>
                <td width="40%" colspan="3">: {{ $data->im_nbr }}</td>
                <td colspan="2">PT. ADIL SENTOSA ABADI, SURABAYA</td>
            </tr>
            <tr>
                <td width="25%">Sudah Terima dari</td>
                <td width="40%" colspan="3">: {{ $data->getSalesOrder->getCOMaster->getCustomer->cust_desc ?? '' }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Banyaknya Uang</td>
                <td colspan="3">: {{$terbilang}}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Untuk Pembayaran</td>
                <td colspan="3">: Jasa Angkut {{ $data->getSalesOrder->getShipFrom->sf_desc ?? ''}} Ke :</td>
                <td colspan="2"></td>
            </tr>
            {{-- Loop Data Detail --}}
            @php($total = 0)
            @foreach($detail as $details)
                <tr>
                    <td></td>
                    <td width="20%">{{ $data->getSalesOrder->getShipTo->cs_shipto_name ?? '' }}</td>
                    <td width="15%">{{number_format($details['t_qtyinv'],0)}}</td>
                    <td width="5%">X</td>
                    <td width="15%">Rp. {{number_format($details['t_harga'],2)}}</td>
                    <td width="25%">Rp. {{number_format($details['t_qtyinv'] * $details['t_harga'],2)}}</td>
                </tr>
                {{$total += $details['t_qtyinv'] * $details['t_harga']}}
            @endforeach

            <tr>
                <td colspan="5"></td>
                <td>_________________</td>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td>Rp. {{number_format($total,2)}}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>Tanggal Jatuh Tempo</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
