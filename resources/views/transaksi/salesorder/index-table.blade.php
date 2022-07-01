<div class="table-responsive col-lg-12 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
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
                <td data-label="SO SHIP FROM">{{$datas->so_ship_from}}</td>
                <td data-label="SO SHIP TO">{{$datas->so_ship_to}}</td>
                <td data-label="SO DUE DATE">{{$datas->so_due_date}}</td>
                <td data-label="SO STATUS">{{$datas->so_status}}</td>
                <td>
                    <a href="" class="viewModal" data-id="{{$datas->id}}" data-sonbr="{{$datas->so_nbr}}"
                        data-cust="{{$datas->getCOMaster->co_cust_code}}" data-type="{{$datas->getCOMaster->co_type}}" 
                        data-shipfrom="{{$datas->so_ship_from}}" data-shipto="{{$datas->so_ship_to}}" 
                        data-duedate="{{$datas->so_due_date}}" data-custdesc="{{$datas->getCOMaster->getCustomer->cust_desc ?? ''}}"
                        data-toggle='modal' data-target="#myModal"><i
                        class="fas fa-eye"></i></button>
                        
                    @if($datas->new_open_so)
                    <a href="{{route('salesorder.edit',$datas->id) }}"><i class="fas fa-edit"></i></a>
                        
                    <a href="{{route('soCreateSJ',['id' => $datas->id]) }}"><i class="fas fa-plus-square"></i></a>
                    
                    <a href="" class="detailModal" data-id="{{$datas->id}}" data-sonbr="{{$datas->so_nbr}}"
                        data-cust="{{$datas->getCOMaster->co_cust_code}}" data-type="{{$datas->getCOMaster->co_type}}" 
                        data-shipfrom="{{$datas->so_ship_from}}" data-shipto="{{$datas->so_ship_to}}" 
                        data-duedate="{{$datas->so_due_date}}" data-custdesc="{{$datas->getCOMaster->getCustomer->cust_desc ?? ''}}"
                        data-toggle='modal' data-target="#detailModal"><i
                        class="fas fa-book"></i></button>
                    @endif

                    
                    @if($datas->new_so)
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