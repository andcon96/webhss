<!DOCTYPE html>
<html>

<head>
    <title>Report Invoice ASA</title>
</head>

<style>
    @page{
        margin: 6mm 6mm 6mm 6mm;
    }

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

    .table-noborder tbody{
        border: none;
    }
</style>

<body>
    @php($flg = 0)
    <table>
        <tbody>
            <tr>
                <td width="25%">Kwitansi No.</td>
                <td width="40%" colspan="3">: <b>{{ $data->im_nbr ?? $data->id_nbr }}</b> </td>
                <td colspan="3" style="text-align: right;padding-right: 20px;"><b>PT. ADIL SENTOSA ABADI, SURABAYA</b></td>
            </tr>
            <tr>
                <td width="25%">Sudah Terima dari</td>
                <td width="40%" colspan="6">: <b>{{ $data->getSalesOrder->getCOMaster->getCustomer->cust_desc ?? $data->getMaster->getSalesOrder->getCOMaster->getCustomer->cust_desc ?? '' }}</b></td>
            </tr>
            <tr>
                <td>Banyaknya Uang</td>
                <td colspan="6">: {{$terbilang}}</td>
            </tr>
            <tr>
                <td>Untuk Pembayaran</td>
                <td colspan="3">: Jasa Angkut {{ $data->getSalesOrder->getShipFrom->sf_desc ?? $data->getMaster->getSalesOrder->getShipFrom->sf_desc ?? ''}} Ke :</td>
                <td colspan="3"></td>
            </tr>
            {{-- Loop Data Detail --}}
            @php($total = 0)
            @foreach($detail as $details)
                @php($flg++)
                <tr>
                    <td></td>
                    <td width="20%">{{ $data->getSalesOrder->getShipTo->cs_shipto_name ?? $data->getMaster->getSalesOrder->getShipTo->cs_shipto_name ?? '' }}</td>
                    <td width="15%">{{number_format($details['t_qtyinv'],0)}}</td>
                    <td width="5%">X</td>
                    <td width="25%">Rp. {{number_format($details['t_harga'],2)}}</td>
                    <td width="5%">Rp.</td>
                    <td width="20%">{{number_format($details['t_qtyinv'] * $details['t_harga'],2)}}</td>
                </tr>
                {{$total += $details['t_qtyinv'] * $details['t_harga']}}
            @endforeach
            <tr>
                <td colspan="5"></td>
                <td colspan="2">_________________</td>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td>Rp.</td>
                <td>{{number_format($total,2)}}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="3" style="text-align: center">Surabaya, {{\carbon\Carbon::now()->isoFormat('DD-MMM-YYYY')}}</td>
            </tr>
            <tr>
                <td colspan="7">
                    <table class="table-noborder">
                        <tbody>
                            <tr>
                                <td style="width: 20%"></td>
                                <td style="width: 5%;border:1px solid black;border-right:none !important;">Rp.</td>
                                <td style="text-align:right !important;border: 1px solid black;border-left:none !important;"><b>{{number_format($total,2)}}</b></td>
                                <td style="line-height:6em; color:white;" colspan="5">x</td>
                            </tr>
                        </tbody>
                    </table>

                </td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="3" style="text-align: center;">(PT.ASA)</td>
            </tr>
        </tbody>
    </table>

    @if($flg > 2)
        <div style='page-break-before:always'></div>
    @endif
    <table>
        <tbody style="border: none;">
            <tr>
                <td colspan="2">Pembayaran melalui giro/transfer :</td>
                <td>a/n</td>
                <td colspan="4">: PT. ADIL SENTOSA ABADI</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td>Bank</td>
                <td colspan="4">: {{$bankacc->bc_acc_name ?? ''}}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td>No. acc</td>
                <td colspan="4">: {{$bankacc->bc_acc_nbr ?? ''}}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
