<div class="table-responsive offset-lg-2 col-lg-8 col-md-12 mt-3">
    <table class="table table-bordered edittable" id="editTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="30%">Kerusakan</th>
                <th width="60%">Mekanik</th>
            </tr>
        </thead>
        <tbody id="edittable">
            @forelse($data->getDetail as $key => $datas)
                <tr>
                    <td style="width:20%">{{$datas->getKerusakan->kerusakan_code}} -- {{$datas->getKerusakan->kerusakan_desc}}</td>
                    <td>
                        <table style="width:100%">
                            @forelse($struktur as $flg => $strukturs)
                                <tr>

                                    <td style="width: 50%">{{$strukturs->ks_desc}}</td>
                                    <td>
                                        
                                        <input type="hidden" name="struk_detail_id[]" value="{{$datas->id}}">
                                        <input type="hidden" name="struk_mekanik_id[]" value="{{$strukturs->id}}">
                                        <input type="text" class="form-control" name="struk_desc[]">
                                    </td>
                                </tr>
                            @empty

                            @endforelse
                        </table>
                    </td>
                </tr>
            @empty

            @endforelse
        </tbody>
    </table>
</div>