@forelse ($approval as $show)
  <tr>
    <td data-title="Name">{{ $show->approval_name }}</td>
    <td data-title="Email">{{ $show->approval_email }}</td>

    <td data-title="Edit" class="action">
      <a href="" class="editapproval" data-toggle="modal" data-target="#editModal" data-id="{{$show->id}}" 
        data-name="{{$show->approval_name}}" data-email="{{$show->approval_email}}"  
      ><i class="fas fa-edit"></i></a>
    </td>
    
    <td data-title="Delete" class="action">
      <a href="" class="deleteapproval" data-toggle="modal" data-target="#deleteModal" data-id="{{$show->id}}" 
        data-name="{{$show->approval_name}}" data-email="{{$show->approval_email}}"  
      ><i class="fas fa-trash"></i></a>
       
    </td>
  </tr>
@empty
<tr>
  <td class="text-danger" colspan='12'>
    <center><b>No Data Available</b></center>
  </td>
</tr>
@endforelse
<tr style="border:0 !important">
  <td colspan="12">
    {{$approval->withQueryString()->links()}}
  </td>
</tr>             
