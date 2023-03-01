<div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="10%">SO Number</th>
                <th width="10%">SPK Number</th>
                <th width="25%">Customer</th>
                <th width="10%">No Polis</th>
                <th width="15%">Ship From</th>
                <th width="15%">Ship To</th>
                <th width="6%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $datas)
            <tr>
                <td>{{$datas->getSOMaster->so_nbr}}</td>
                <td>{{$datas->sj_nbr}}</td>
                <td>{{$datas->getSOMaster->getCOMaster->getCustomer->cust_code ?? ''}} -- {{$datas->getSOMaster->getCOMaster->getCustomer->cust_desc ?? ''}}</td>
                <td>{{$datas->getTruck->truck_no_polis}}</td>
                <td>{{$datas->getSOMaster->so_ship_from}} -- {{$datas->getSOMaster->getShipFrom->sf_desc ?? ''}}</td>
                <td>{{$datas->getSOMaster->so_ship_to}} -- {{$datas->getSOMaster->getShipTo->cs_shipto_name}}</td>
                <td>
                    <a href="{{ route('ConfirmSJ', ['sj' => $datas->id, 'truck' => $datas->sj_truck_id]) }}">
                        <i class="fas fa-book"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{$data->withQueryString()->links()}}
</div>