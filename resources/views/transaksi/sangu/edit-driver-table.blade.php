<div class="table-responsive col-md-12 mt-3">
    <table class="table table-bordered drivertable mini-table" id="driverTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="40%">No Polis & Driver</th>
                <th width="20%">Jumlah Trip</th>
                <th width="30%">Sangu</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody id="driverbody">
            @forelse($data->getSangu as $key => $datas)
                <tr>
                    <td data-label="NO Polis">
                        {{ $datas->getTruckDriver->getTruck->truck_no_polis }} --
                        {{ $datas->getTruckDriver->getUser->name }}
                        <input type="hidden" value="{{ $datas->sos_truck }}" name="polis[]" />
                    </td>
                    <td data-label="Jumlah Trip">
                        <input type="number" value="{{ $datas->sos_tot_trip }}" class="form-control"
                            name="qtytrip[]" />
                    </td>
                    <td data-label="Jumlah Sangu">
                        <input type="text" value="{{ number_format($datas->sos_sangu, 0) }}" class="form-control sangu"
                            name="totsangu[]" />
                    </td>
                    <td style="text-align: center;">
                        <input type="hidden" value="{{ $datas->id }}" name="iddetail[]" />
                        <input type="hidden" value="M" name="operation[]" class="inpdeltrip" />
                        <input type="checkbox" class="form-group deltrip">
                    </td>
                </tr>
            @empty
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" class="text-center">
                    <span class="btn btn-info bt-action" id="addrow">Add Row</span>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
