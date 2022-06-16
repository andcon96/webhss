<div class="card text-center">
    <div class="card-header">
        Status
    </div>
    <div class="card-body">
        <h5 class="card-title d-flex">Status Terakhir :
            @if($data->count() > 0)
                @if($data[0]->getActiveDriver->getLastCheckInOut)
                    @if($data[0]->getActiveDriver->getLastCheckInOut->cio_is_check_in == 1)
                        <p style="color: green" class="ml-4">Check In</p>
                    @else
                        <p style="color: red" class="ml-4">Check Out</p>
                    @endif
                @endif
                @else     
                        <p style="color: red" class="ml-4">No Data</p>
            @endif 
        </h5>
        <p class="card-text">Anda terakhir Checkin Checkout, Pada Tanggal : 
            <b>
                @if($data->count() > 0)
                {{$data[0]->getActiveDriver->getLastCheckInOut->created_at}}
                @else
                No Data
                @endif
            </b>
        </p>
        <a href="" class="editUser btn bt-ref" data-toggle="modal" data-target="#editModal">Check In / Out</a>
    </div>
    <div class="card-footer text-muted">
        Status
    </div>
</div>