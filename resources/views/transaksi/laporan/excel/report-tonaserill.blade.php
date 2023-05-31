<!DOCTYPE html>
<html>

<head>
    <title>Report Tonase Rill</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="15" style="text-align:center">Report Tonase Rill dan Harga Rill</th>
            </tr>
            <tr>
                <th style="vertical-align: top; text-align:center;"><b>Sales Order</b></th>
                <th style="vertical-align: top; text-align:center;"><b>SPK</b></th>
                <th style="vertical-align: top; text-align:center;"><b>Truck</b></th>
                <th style="vertical-align: top; text-align:center;"><b>Kapal</b></th>
                <th style="vertical-align: top; text-align:center;"><b>Customer</b></th>
                <th style="vertical-align: top; text-align:center;"><b>Ship To</b></th>
                <th style="vertical-align: top; text-align:center;"><b>Ship From</b></th>
                <th style="vertical-align: top; text-align:center;"><b>Eff Date SPK</b></th>
                <th style="vertical-align: top; text-align:center;"><b>Due Date SO</b></th>
                <th style="vertical-align: top; text-align:center;"><b>SJ Customer</b></th>
                <th style="vertical-align: top; text-align:center;"><b>SJ Status</b></th>
                <th style="vertical-align: top; text-align:center;"><b>Qty SJ</b></th>
                <th style="vertical-align: top; text-align:center;"><b>Qty Diakui</b></th>
                <th style="vertical-align: top; text-align:center;"><b>Harga Diakui</b></th>
                <th style="vertical-align: top; text-align:center;"><b>List Sangu</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suratjalan as $sj)
                <tr>
                    <td>{{ $sj->getSOMaster->so_nbr ?? '' }}</td>
                    <td>{{ $sj->sj_nbr ?? '' }}</td>
                    <td>{{ $sj->getTruck->truck_no_polis ?? '' }}</td>
                    <td>{{ $sj->getSOMaster->getCOMaster->co_kapal ?? '' }}</td>
                    <td>
                        {{ $sj->getSOMaster->getCOMaster->getCustomer->cust_code ?? '' }} --
                        {{ $sj->getSOMaster->getCOMaster->getCustomer->cust_desc ?? '' }}
                    </td>
                    <td>
                        {{ $sj->getSOMaster->getShipTo->cs_shipto ?? '' }} -- 
                        {{ $sj->getSOMaster->getShipTo->cs_shipto_name ?? '' }}
                    </td>
                    <td>
                        {{ $sj->getSOMaster->getShipFrom->sf_code ?? '' }} -- 
                        {{ $sj->getSOMaster->getShipFrom->sf_desc ?? '' }}
                    </td>
                    <td>{{ $sj->sj_eff_date ?? '' }}</td>
                    <td>{{ $sj->getSOMaster->so_due_date ?? '' }}</td>
                    <td>{{ $sj->sj_surat_jalan_customer ?? ''}}</td>
                    <td>{{ $sj->sj_status ?? '' }}</td>
                    <td>{{ $sj->getDetail[0]->sjd_qty_conf ?? '' }}</td>
                    <td>{{ $sj->getDetail[0]->sjd_qty_akui ?? '' }}</td>
                    <td>{{ $sj->getDetail[0]->sjd_price ?? '' }}</td>
                    <td>{{ $sj->getRuteHistory->history_sangu ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
