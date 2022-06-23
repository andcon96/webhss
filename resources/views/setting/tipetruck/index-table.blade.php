
<div class="table-responsive offset-lg-2 col-lg-8 col-md-12 mt-3">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="20%">Tipe truck code</th>
                <th>Tipe truck description</th>
                <th>Active</th>
                
                <th width="10%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $datas)
            <tr>
                <td>
                    {{$datas->tt_code}}
                </td>
                <td>
                    {{$datas->tt_desc}}
                </td>
                <td>
                    {{$datas->tt_isactive == 1 ? 'Active' : 'Not Active'}}
                </td>
                <td>
                    <a href="" class="editRole" data-id="{{$datas->id}}" data-code="{{$datas->tt_code}}" 
                        data-desc="{{$datas->tt_desc}}"  data-toggle='modal' data-target="#editModal">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="" class="deleteRole" data-id="{{$datas->id}}" data-code="{{$datas->tt_code}}" 
                        data-active="{{$datas->tt_isactive}}" data-toggle='modal' data-target="#deleteModal">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan='3' style="color:red;text-align:center;"> No Data Available</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{$data->withQueryString()->links()}}
</div>