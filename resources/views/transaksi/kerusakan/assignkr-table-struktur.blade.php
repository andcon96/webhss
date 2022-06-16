<div class="table-responsive offset-lg-3 col-lg-6 col-md-12 mt-3">
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
                    <td>{{$datas->getKerusakan->kerusakan_code}} -- {{$datas->getKerusakan->kerusakan_desc}}</td>
                    <td>
                        <table style="width:100%">
                            @forelse($struktur as $flg => $strukturs)
                                <tr>
                                    <td>{{$strukturs->slk_desc}}</td>
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