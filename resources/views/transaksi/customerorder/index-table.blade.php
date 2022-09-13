<div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="10%">Nomor CO</th>
                <th width="25%">Customer</th>
                <th width="15%">Nama Kapal</th>
                <th width="10%">Barang</th>
                <th width="10%">Status</th>
                <th width="15%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $datas)
            <tr>
                <td data-label="CO NUMBER">{{$datas->co_nbr}}</td>
                <td data-label="CO CUSTOMER">{{$datas->co_cust_code}} -- {{$datas->getCustomer->cust_desc}}</td>
                <td data-label="KAPAL">{{$datas->co_kapal}}</td>
                <td data-label="BARANG">{{$datas->getBarang->barang_deskripsi ?? ''}}</td>
                <td data-label="CO STATUS">{{$datas->co_status}}</td>
                <td>
                    <a href="" class="viewModal" data-id="{{$datas->id}}" data-conbr="{{$datas->co_nbr}}"
                        data-cust="{{$datas->co_cust_code}}" data-custdesc="{{$datas->getCustomer->cust_desc}}"
                        data-status="{{$datas->co_status}}" 
                        data-remark="{{$datas->co_remark}}"
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
                        class="fas fa-book"></i></a>

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