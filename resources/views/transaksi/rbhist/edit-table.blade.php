<div class="table-responsive col-lg-10 offset-lg-1 col-md-12 mt-3">
   <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
       <thead>
           <tr>
               <th width="40%">Deskripsi</th>
               <th width="40%">Nominal</th>
               <th width="10%">Action</th>
           </tr>
       </thead>
       <tbody id="bodyrbhist">
        @foreach($rbhist->getDetail as $list)
           <tr>
               <td>{{$list->rbd_deskripsi}}</td>
               <td>
                    <input type="text" name="nominal[]" class="form-control nominal" min="0" 
                    value="{{number_format($list->rbd_nominal,0)}}">
                    <input type="hidden" name="deskripsi[]" value="{{$list->rbd_deskripsi}}">
                    <input type="hidden" name="iddeskripsi[]" value="{{$list->id}}">
              </td>
              <td></td>
           </tr>
        @endforeach
       </tbody>
       <tfoot>
           
        <tr>
           <td colspan="3" class="text-center">
               <span class="btn btn-info bt-action" id="addrow">Add Row</span>
           </td>
       </tr>
       </tfoot>
   </table>
</div>
