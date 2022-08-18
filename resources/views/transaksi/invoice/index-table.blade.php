<div class="table-responsive col-lg-10 offset-lg-1 col-md-12 mt-3">
    <table class="table table-bordered mini-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="25%">Invoice Number</th>
                <th width="25%">SO Number</th>
                <th>Invoice Date</th>
                <th>Invoice Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $datas)
                <tr>
                    <td data-label="Invoice">{{ $datas->im_nbr }}</td>
                    <td data-label="SO Number">{{ $datas->getSalesOrder->so_nbr ?? '' }}</td>
                    <td data-label="Date">{{ $datas->im_date }}</td>
                    <td data-label="Total">
                        {{ number_format($datas->getDetail->sum('id_total'))}}
                    </td>
                    <td>
                        @if ($datas->rb_is_active == 1)
                            <form action="{{ route('invoicemt.destroy', $datas->id) }}" method="POST" id="submit">
                                @csrf
                                @method('delete')
                                <a href="" class="deleteModal" id="btnconf" data-id="{{ $datas->id }}"
                                    data-imnbr="{{ $datas->im_nbr }}">
                                    <i class="fas fa-trash"></i></a>
                            </form>
                        @endif
                        <a href="{{ route('printInvoice', $datas->id) }}" target="_blank" ><i
                                class="fa fa-print"></i></a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan='8' style="color:red;text-align:center;"> No Data Avail</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $data->withQueryString()->links() }}
</div>
