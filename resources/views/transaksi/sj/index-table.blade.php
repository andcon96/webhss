<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="15%">SO Number</th>
                <th width="15%">SJ Number</th>
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
                <td data-label="CUSTOMER">{{$datas->getSOMaster->so_cust}} -- {{$datas->getSOMaster->getCustomer->cust_desc}}</td>
                <td data-label="SHIP TO">{{$datas->sj_shipto}}</td>
                <td data-label="CO STATUS">{{$datas->sj_status}}</td>
                <td>
                    <a href="" class="viewModal" data-id="{{$datas->id}}"
                        data-sonbr="{{$datas->getSOMaster->so_nbr}}" data-sjnbr="{{$datas->sj_nbr}}"
                        data-cust="{{$datas->getSOMaster->so_cust}}" data-shipto="{{$datas->sj_shipto}}"
                        data-status="{{$datas->sj_status}}"
                        data-truck="{{$datas->getTruck->truck_no_polis}}" data-trip="{{$datas->sj_jmlh_trip}}"
                        data-sangu="{{number_format($datas->sj_tot_sangu,2)}}" 
                        data-pengurus="{{$datas->getTruck->getActiveDriver->getPengurus->name}}"
                        data-toggle='modal' data-target="#myModal"><i
                        class="fas fa-eye"></i></button>
                    
                    <a href="{{route('customerorder.edit',$datas->id) }}"><i class="fas fa-edit"></i></a>

                    <a href="" class="deleteModal" 
                        data-id="{{$datas->id}}" data-sonbr="{{$datas->so_nbr}}"
                        data-toggle='modal' data-target="#deleteModal"><i class="fas fa-trash"></i></a>
                        
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