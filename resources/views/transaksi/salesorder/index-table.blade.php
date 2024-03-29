<div class="table-responsive col-lg-12 col-md-12 mt-3" style="overflow-x:auto;">
    <table class="table table-bordered mini-table" id="dataTable">
        <thead>
            <tr>
                <th>Nomor SO</th>
                <th>Nomor CO</th>
                <th>Customer</th>
                <th>Type</th>
                <th>Ship-From</th>
                <th>Ship-To</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Remarks CO</th>
                <th>Remarks SO</th>
                <th width="13%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $datas)
            <tr>
                <td data-label="SO NUMBER">{{$datas->so_nbr}}</td>
                <td data-label="CO NUMBER">{{$datas->getCOMaster->co_nbr}}</td>
                <td data-label="SO CUSTOMER">{{$datas->getCOMaster->co_cust_code}} -- {{$datas->getCOMaster->getCustomer->cust_desc}}</td>
                <td data-label="SO TYPE">{{$datas->getCOMaster->co_type}}</td>
                <td data-label="SO SHIP FROM">{{$datas->so_ship_from}} -- {{$datas->getShipFrom->sf_desc ?? ''}}</td>
                <td data-label="SO SHIP TO">{{$datas->so_ship_to}} -- {{$datas->getShipTo->cs_shipto_name}}</td>
                <td data-label="SO DUE DATE">{{$datas->so_due_date}}</td>
                <td data-label="SO STATUS">{{$datas->so_status}}</td>
                <td data-label="CO REMARKS">{{$datas->getCOMaster->co_remark}}</td>
                <td data-label="CO REMARKS">{{$datas->so_remark}}</td>
                <td>
                    <a href="" class="viewModal" data-id="{{$datas->id}}" data-sonbr="{{$datas->so_nbr}}"
                        data-cust="{{$datas->getCOMaster->co_cust_code}}" data-type="{{$datas->getCOMaster->co_type}}" 
                        data-shipfrom="{{$datas->so_ship_from}}" data-shipfromdesc="{{$datas->getShipFrom->sf_desc ?? ''}}"
                        data-shipto="{{$datas->so_ship_to}}" data-shiptodesc="{{$datas->getShipTo->cs_shipto_name ?? ''}}"
                        data-duedate="{{$datas->so_due_date}}" data-custdesc="{{$datas->getCOMaster->getCustomer->cust_desc ?? ''}}"
                        data-remark="{{$datas->so_remark}}" data-barang="{{$datas->getCOMaster->getBarang->barang_deskripsi ?? ''}}"
                        data-poaju="{{$datas->so_po_aju}}"
                        data-toggle='modal' data-target="#myModal"><i
                        class="fas fa-eye"></i></button>
                        
                    @if($datas->new_open_so)
                    <a href="{{route('salesorder.edit',$datas->id) }}"><i class="fas fa-edit"></i></a>
                        
                    <a href="{{route('soCreateSJ',['id' => $datas->id]) }}"><i class="fas fa-plus-square"></i></a>
                    @endif

                    
                    <a href="" class="detailModal" data-id="{{$datas->id}}" data-sonbr="{{$datas->so_nbr}}"
                        data-cust="{{$datas->getCOMaster->co_cust_code}}" data-type="{{$datas->getCOMaster->co_type}}" 
                        data-shipfrom="{{$datas->so_ship_from}}" data-shipfromdesc="{{$datas->getShipFrom->sf_desc ?? ''}}"
                        data-shipto="{{$datas->so_ship_to}}" data-shiptodesc="{{$datas->getShipTo->cs_shipto_name ?? ''}}"
                        data-duedate="{{$datas->so_due_date}}" data-custdesc="{{$datas->getCOMaster->getCustomer->cust_desc ?? ''}}"
                        data-toggle='modal' data-target="#detailModal"><i
                        class="fas fa-book"></i></button>
                    
                    @if($datas->new_so && $datas->used_so == 0)
                    <a href="" class="deleteModal" 
                        data-id="{{$datas->id}}" data-sonbr="{{$datas->so_nbr}}"
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