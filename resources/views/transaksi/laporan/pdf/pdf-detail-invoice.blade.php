<!DOCTYPE html>
<html>

<head>
    <title>Report Invoice Detail ASA</title>
</head>

<style>
    @page {
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

    .top-bottom-border {
        border: none;
        border-bottom: 1px solid black;
        border-top: 1px solid black;
        line-height: 3em;
        vertical-align: top;
    }

    .right-border {
        border: none;
        border-right: 1px solid black;
    }

    .table-noborder tbody {
        border: none;

    }
</style>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="2">PT. ADIL SENTOSA ABADI</th>
                <th colspan="6"></th>
                <th colspan="3"></th>
            </tr>
            <tr>
                <th colspan="{{ $iscontainer == 0 ? '11' : '13' }}" style="line-height: 3em;text-align: center">LAPORAN
                    TAGIHAN CUSTOMER</th>
            </tr>
            <tr>
                <th colspan="{{ $iscontainer == 0 ? '11' : '13' }}" style="line-height: 3em;text-align:center;">PERIODE
                    {{ $oldestdate->sj_eff_date ?? '' }} - {{ $latestdate->sj_eff_date ?? '' }}</th>
            </tr>
            <tr>
                <th colspan="{{ $iscontainer == 0 ? '9' : '11' }}"
                    style="line-height: 3em; text-align:left;font-weight:bold;padding-bottom: 10px;padding-left: 10px;">
                    NO. INVOICE :
                    {{ $data->im_nbr ?? '' }}</th>
            </tr>
            <tr>
                <th class="top-bottom-border">CUSTOMER</th>
                <th class="top-bottom-border">NAMA BARANG</th>
                <th class="top-bottom-border">NO. PO / EKS</th>
                <th class="top-bottom-border">TANGGAL</th>
                <th class="top-bottom-border">SJ CUSTOMER</th>
                <th class="top-bottom-border">NO POLIS</th>
                <th class="top-bottom-border">DARI</th>
                <th class="top-bottom-border">TUJUAN</th>
                <th class="top-bottom-border">QTY</th>
                <th class="top-bottom-border">TARIF</th>
                @if ($iscontainer == 1)
                    <th class="top-bottom-border">NO CONTAINER</th>
                    <th class="top-bottom-border">FEET</th>
                @endif
                <th class="top-bottom-border">NILAI Rp.</th>
            </tr>
        </thead>
        <tbody>
            @php($total = 0)
            @php($subtotal = 0)
            @php($tempshipto = '')
            @foreach ($detail as $details)
                @if ($tempshipto != '' && $tempshipto != $details->cs_shipto_name)
                    <tr>
                        <td colspan="7">

                        </td>
                        <td colspan="{{ $iscontainer == 0 ? '3' : '5' }}" style="font-weight:bold">
                            Sub Total {{ $tempshipto }}
                        </td>
                        <td style="font-weight:bold; text-align:right">
                            {{ number_format($subtotal, 0) }}
                        </td>
                    </tr>
                    @php($subtotal = 0)
                @endif
                @php($tempshipto = $details->cs_shipto_name ?? ($details->t_part ?? ''))
                @php($subtotal += $details->t_harga * $details->t_qtyinv)
                @php($total += $details->t_harga * $details->t_qtyinv)

                <tr>
                    {{-- Print PDF Gabung antara Detail & Master --}}
                    <td>{{ $details->cust_desc ?? ($data->im_cust_qad ?? ($data->getMaster->im_cust_qad ?? '')) }}</td>
                    /
                    <td>{{ $details->barang_deskripsi ?? ($details->t_part ?? '') }}</td>
                    <td>{{ $details->so_po_aju }} &nbsp; / &nbsp; {{ $details->co_kapal }}</td>
                    <td>{{ $details->sj_eff_date ?? ($details->t_orddate ?? '') }}</td>
                    <td>{{ $details->sj_surat_jalan_customer }}</td>
                    <td>{{ $details->truck_no_polis }}</td>
                    <td>{{ $details->sf_desc }}</td>
                    <td>{{ $details->cs_shipto_name ?? ($details->t_part ?? '') }}</td>
                    <td class="angka">{{ $details->t_qtyinv }}</td>
                    <td class="angka">{{ number_format($details->t_harga, 0) }}</td>
                    @if ($iscontainer == 1)
                        <td>{{ $details->sj_surat_jalan_customer }}</td>
                        <td>{{ $details->truck_tipe_id == null
                            ? ''
                            : ($details->truck_tipe_id == 6
                                ? '40"'
                                : ($details->truck_tipe_id == 5
                                    ? '20"'
                                    : 'Loosing')) }}
                        </td>
                    @endif
                    <td class="angka">{{ number_format($details->t_harga * $details->t_qtyinv, 0) }}</td>
                </tr>

                @if ($loop->last)
                    <tr>
                        <td colspan="7">
                        </td>
                        <td colspan="{{ $iscontainer == 0 ? '3' : '5' }}" style="font-weight:bold">
                            Sub Total {{ $details->cs_shipto_name ?? ($details->t_part ?? '') }}
                        </td>
                        <td style="font-weight:bold; text-align:right">
                            {{ number_format($subtotal, 0) }}
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="top-bottom-border" colspan="{{ $iscontainer == 0 ? '10' : '12' }}"></td>
                {{-- <td  class="top-bottom-border" colspan="10"></td> --}}
                <td class="top-bottom-border angka">{{ number_format($total, 0) }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
