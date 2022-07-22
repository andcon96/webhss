<div class="table-responsive offset-lg-2 col-lg-8 col-md-12 mt-3" style="display: inline-table">
    <table class="table table-bordered edittable" id="editTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="60%">Jenis Kerusakan</th>
                <th width="40%">Tindakan</th>
                
            </tr>
        </thead>
        <tbody id="edittable">
            @forelse ($data->getDetail as $key => $datas)
            
            <tr>
                <td >
                    <input type="text" class="form-control" value="{{$datas->getKerusakan->kerusakan_code}} -- {{$datas->getKerusakan->kerusakan_desc}}" readonly>
                    <input type="hidden" name="operation[]" class="operation" value="M">
                    <input type="hidden" name="iddetail[]" value="{{$datas->id}}">
                    
                    <input type="hidden" name="jeniskerusakan[]" value="{{$datas->krd_kerusakan_id}}">
                </td>
                <td>
                    <input type="text" class="form-control" value="{{$datas->krd_remarks}}" name="remarks[]">
                </td>
                {{-- <td style="vertical-align:middle;text-align:center;">  --}}
                    
                    {{-- @if($data->kr_status == 'Done' && count($datas->getStrukturTrans) >0)
                        <input type="checkbox" class="qaddel" value="" disabled> 
                    @else
                        <input type="checkbox" class="qaddel" value="Y" name="qaddel[]"> 
                    @endif
                </td> --}}
            </tr>
            @empty
            <tr>
                <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
            </tr>
            @endforelse
        </tbody>
        {{-- <tfoot>
            <tr>
                <td colspan="8" class="text-center">
                    <span class="btn btn-info bt-action" id="addrow">Add Row</span>
                </td>
            </tr>
        </tfoot> --}}
    </table>
</div>