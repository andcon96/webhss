<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="15%">Nomor CO</th>
                <th>Customer</th>
                <th width="15%">Status</th>
                <th width="15%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $datas)
            <tr>
                <td data-label="CO NUMBER">{{$datas->co_nbr}}</td>
                <td data-label="CO CUSTOMER">{{$datas->co_cust_code}} -- {{$datas->getCustomer->cust_desc}}</td>
                <td data-label="CO STATUS">{{$datas->co_status}}</td>
                <td>
                    <a href="" class="viewModal" data-id="{{$datas->id}}" data-conbr="{{$datas->co_nbr}}"
                        data-cust="{{$datas->co_cust_code}}" data-custdesc="{{$datas->getCustomer->cust_desc}}"
                        data-status="{{$datas->co_status}}" 
                        data-toggle='modal' data-target="#myModal"><i
                        class="fas fa-eye"></i></button>
                    
                    @if($datas->co_status == 'New' || $datas->co_status == 'Ongoing')
                    <a href="{{route('customerorder.edit',$datas->id) }}"><i class="fas fa-edit"></i></a>
                    @endif

                    @if($datas->co_status == 'New')
                    <a href="" class="deleteModal" 
                        data-id="{{$datas->id}}" data-conbr="{{$datas->co_nbr}}"
                        data-toggle='modal' data-target="#deleteModal"><i class="fas fa-trash"></i></a>
                    @endif
                    
                    @if($datas->co_status == 'New' || $datas->co_status == 'Ongoing')
                    <a href="{{route('coCreateSO',$datas->id) }}"><i class="fas fa-plus-square"></i></a>
                    @endif
                    
                    <a href="" class="detailModal" data-id="{{$datas->id}}" data-conbr="{{$datas->co_nbr}}"
                        data-cust="{{$datas->co_cust_code}}" data-custdesc="{{$datas->getCustomer->cust_desc}}"
                        data-status="{{$datas->co_status}}" 
                        data-toggle='modal' data-target="#detailModal"><i
                        class="fas fa-book"></i></button>

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