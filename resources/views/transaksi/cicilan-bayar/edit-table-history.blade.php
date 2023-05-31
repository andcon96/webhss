<div class="table-responsive col-lg-10 col-md-10 offset-md-1 mb-3">
   <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
       <thead>
           <tr>
               <th width="15%">Truck</th>
               <th width="15%">Driver</th>
               <th>Tanggal Bayar</th>
               <th>Nominal Bayar</th>
               <th width="30%">Remark</th>
               <th width="8%">Status</th>
               <th width="8%">Edit</th>
           </tr>
       </thead>
       <tbody>
           @forelse ($cicilan->getTotalPaid as $key => $datas)
           <tr>
               <td data-label="TRUCK">{{$cicilan->getDriverNopol->getTruck->truck_no_polis ?? ''}}</td>
               <td data-label="DRIVER">{{$cicilan->getDriverNopol->getDriver->driver_name ?? ''}}</td>
               <td data-label="PAYMENT DATE">{{$datas->hc_eff_date}}</td>
               <td data-label="NOMINAL">{{number_format($datas->hc_nominal,3)}}</td>
               <td data-label="REMARKS">{{$datas->hc_remarks}}</td>
               <td>
                    {{$datas->hc_is_active == 1 ? 'Active' : 'Not Active'}}
               </td>
               <td>
                <a href="" class="editmodal" data-id="{{$datas->id}}" 
                    data-truck="{{$cicilan->getDriverNopol->getTruck->truck_no_polis ?? ''}}"
                    data-driver="{{$cicilan->getDriverNopol->getDriver->driver_name ?? ''}}"
                    data-tglbayar="{{$datas->hc_eff_date}}"
                    data-nominal="{{number_format($datas->hc_nominal,3)}}"
                    data-remarks="{{$datas->hc_remarks}}"
                    data-isactive="{{$datas->hc_is_active}}"
                    data-toggle='modal' data-target="#editModal"><i
                    class="fas fa-edit"></i></a>
               </td>
           </tr>
           @empty
           <tr>
               <td colspan='5' style="color:red;text-align:center;"> No Data Avail</td>
           </tr>
           @endforelse
       </tbody>
   </table>
</div>