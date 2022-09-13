@extends('layout.layout')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Master</a></li>
        <li class="breadcrumb-item active">Customer Ship From Maintenance</li>
    </ol>
@endsection

@section('content')
    <div class="col-lg-10 offset-lg-1 col-md-12">
        <a href="{{Route('shipfrom.create')}}" class="btn bt-ref">Create Data</a>
    </div>
    <!-- page heading -->
    <form action="{{ route('shipfrom.index') }}" method="get">
        <div class="col-md-12 col-lg-10 offset-lg-1 mb-4">
            <div class="row form-group mt-4">
                <label for="s_sfcode" class="col-md-2 col-form-label">{{ __('Customer Code') }}</label>
                <div class="col-md-4">
                    <select name="s_sfcode" id="s_sfcode" class="form-control">
                        <option value="">Select Data</option>
                        @foreach ($data as $listsf)
                            <option value="{{ $listsf->id }}">{{ $listsf->sf_code }} -- {{ $listsf->sf_desc }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 offset-md-1">
                    <input type="submit" class="btn bt-ref" id="btnsearch" value="Search" />
                    <button class="btn bt-action ml-2" id='btnrefresh' style="width: 40px !important"><i class="fa fa-sync"></i></button>
                </div>
            </div>
        </div>
    </form>

    <div class="table-responsive col-lg-10 offset-lg-1 col-md-12">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th width="10%">Ship From</th>
                    <th width="50%">Description</th>
                    <th width="10%">Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $show)
                    <tr>
                        <td>{{ $show->sf_code }}</td>
                        <td>{{ $show->sf_desc }}</td>
                        <td>{{ $show->sf_is_active == 1 ? 'Active' : 'Not Active' }}</td>
                        <td class="d-flex">
                            <a href="{{route('shipfrom.edit',$show->id)}}"><i class="fas fa-edit"></i></a>
                            
                            <form action="{{route('shipfrom.destroy',$show->id)}}" method="POST" id="submit">
                                @csrf
                                @method('delete')
                                <a href="" class="deleteModal"  id="btnDelete"
                                    data-id="{{$show->id}}" data-code="{{$show->sf_code}}"
                                    data-status="{{$show->sf_is_active}}">
                                    @if($show->sf_is_active == 1)
                                    <i class="fas fa-trash"></i>
                                    @elseif($show->sf_is_active == 0)
                                    <i class="fas fa-check"></i>
                                    @endif
                                </a>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align:center;color:red">No Data Available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $data->withQueryString()->links() }}
    </div>
@endsection


@section('scripts')
    <script>
        $('#s_sfcode').select2({
            width: '100%',
        });
        $('#btnrefresh').on('click', function() {
            $('#s_sfcode').val('');
        });

        $(document).on('click', '#btnDelete', function(e){
            e.preventDefault();
            let sfcode = $(this).data('code');
            let id = $(this).data('id');
            let status = $(this).data('status');

            let text = status == 1 ? 'Not Active' : 'Active';

            Swal.fire({
                title: "Delete / Recover Data " + sfcode,
                text: "Status akan dirubah menjadi " + text,
                type: "warning",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Confirm",
                closeOnConfirm: false
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {   
                    $(this).closest('tr').find('#submit').submit();
                    // $('#submit').submit();
                } 
            })
        });

        $(document).ready(function(){
            var cur_url = window.location.href;

            let paramString = cur_url.split('?')[1];
            let queryString = new URLSearchParams(paramString);

            let sfcode = queryString.get('s_sfcode');

            $('#s_sfcode').val(sfcode).trigger('change');
        });
    </script>
@endsection
