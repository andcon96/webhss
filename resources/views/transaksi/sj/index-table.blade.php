<div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="15%">SO Number</th>
                <th width="15%">SPK Number</th>
                <th>Customer</th>
                <th>Ship To</th>
                <th width="15%">Status</th>
                <th width="15%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $datas)
            <tr>
                <td data-label="SO NUMBER">{{$datas->getSOMaster->so_nbr}}</td>
                <td data-label="SJ NUMBER">{{$datas->sj_nbr}}</td>
                <td data-label="CUSTOMER">{{$datas->getSOMaster->getCOMaster->co_cust_code}} -- {{$datas->getSOMaster->getCOMaster->getCustomer->cust_desc}}</td>
                <td data-label="SHIP TO">{{$datas->getSOMaster->so_ship_to}} -- {{$datas->getSOMaster->getShipTo->cs_shipto_name ?? ''}}</td>
                <td data-label="CO STATUS">{{$datas->sj_status}}</td>
                <td>
                    <a href="" class="viewModal" data-id="{{$datas->id}}"
                        data-sonbr="{{$datas->getSOMaster->so_nbr}}" data-sjnbr="{{$datas->sj_nbr}}"
                        data-cust="{{$datas->getSOMaster->getCOMaster->co_cust_code}}" 
                        data-custdesc="{{$datas->getSOMaster->getCOMaster->getCustomer->cust_desc}}"
                        data-shipto="{{$datas->getSOMaster->so_ship_to}}"
                        data-shiptodesc="{{$datas->getSOMaster->getShipTo->cs_shipto_name}}"
                        data-status="{{$datas->sj_status}}"
                        data-truck="{{$datas->getTruck->truck_no_polis}}" data-trip="{{$datas->sj_jmlh_trip}}"
                        data-sangu="{{number_format($datas->sj_tot_sangu,2)}}" 
                        data-pengurus="{{$datas->getTruck->getUserPengurus->name ?? ''}}"
                        data-toggle='modal' data-target="#myModal"><i
                        class="fas fa-eye"></i></button>
                        
                    @if($datas->sj_status == 'Open')
                        <a href="{{route('suratjalan.edit',$datas->id) }}"><i class="fas fa-edit"></i></a>

                        <a href="" class="deleteModal" 
                            data-id="{{$datas->id}}" data-sonbr="{{$datas->sj_nbr}}"
                            data-toggle='modal' data-target="#deleteModal"><i class="fas fa-trash"></i></a>
                    @endif

                    
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