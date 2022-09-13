<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-3">
    <table class="table table-bordered edittable mini-table" id="editTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="7%">No</th>
                <th width="10%">Domain</th>
                <th width="20%">Invoice Nbr</th>
                <th width="10%">Due Date</th>
                <th width="10%">Amount</th>
                <th width="10%">Check</th>
                <th width="5%">Delete</th>
            </tr>
        </thead>
        <tbody id="edittable">
            @forelse ($data->getDetail as $key => $datas)
                <tr>
                    <input type="hidden" name="iddetail[]" value="{{ $datas->id }}">
                    <td>{{$key + 1}}</td>
                    <td>
                        <input type="text" name="domain[]" id="domain" class="form-control domain" value="{{$datas->id_domain}}" readonly>
                    </td>
                    <td>
                        <input type="text" name="invnbr[]" id="invnbr" class="form-control invnbr" value="{{$datas->id_nbr}}" readonly>
                    </td>
                    <td>
                        <input type="text" name="duedate[]" id="duedate" class="form-control duedate" value="{{$datas->id_duedate}}" readonly>
                    </td>
                    <td>
                        <input type="text" name="total[]" id="total" class="form-control total" value="{{$datas->id_total}}" readonly>
                    </td>
                    <td>
                        <input type="button" class="btncheck btn btn-info btn-focus mr-2"  value="Check">
                    </td>
                    <td>
                        <input type="hidden" name="operation[]" value="M" class="operation">
                        <input type="checkbox" class="qaddel" value="Y" name="qaddel[]"> 
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
                </tr>
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
