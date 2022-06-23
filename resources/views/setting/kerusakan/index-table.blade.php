
<div class="table-responsive offset-lg-2 col-lg-8 col-md-12 mt-3">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="20%">Kerusakan Code</th>
                <th>Description</th>
                <th>Active</th>
                <th>Need Approval</th>
                <th width="10%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $datas)
            <tr>
                <td>
                    {{$datas->kerusakan_code}}
                </td>
                <td>
                    {{$datas->kerusakan_desc}}
                </td>
                <td>
                    {{$datas->kerusakan_is_active == 1 ? 'Active' : 'Not Active'}}
                </td>
                <td>
                    {{$datas->kerusakan_need_approval == 1 ? 'Yes' : 'No'}}
                </td>
                <td>
                    <a href="" class="editRole" data-id="{{$datas->id}}" data-code="{{$datas->kerusakan_code}}" 
                        data-desc="{{$datas->kerusakan_desc}}" data-appr="{{$datas->kerusakan_need_approval}}" data-toggle='modal' data-target="#editModal">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="" class="deleteRole" data-id="{{$datas->id}}" data-code="{{$datas->kerusakan_code}}" 
                        data-active="{{$datas->kerusakan_is_active}}" data-toggle='modal' data-target="#deleteModal">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan='3' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{$data->withQueryString()->links()}}
</div>