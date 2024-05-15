<style>
    th{
       text-align: left;
       border:1px solid rgb(156, 156, 156);
       padding:3px;
   }
   td{
       border:1px solid rgb(156, 156, 156);
       padding:3px;
   }
   table{
       border-collapse: collapse;
       width: 100%;
   }
   caption{
       padding:5px;
       border:1px solid rgb(156, 156, 156);
   }
</style>

@php
   $sr = 1;
@endphp

<div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_employee" name="search" placeholder="Search Employee......." onchange="checkValues(this)" class="form-control w-25" >

</div>
<table id="employee_table_view">
  
   <thead>
       <th>Sr#</th>
       <th>Name</th>
       <th>Type</th>
       <th>Branch</th>
       <th>CNIC</th>
       <th>Salary</th>
   </thead>
   <tbody>
       @foreach ($data as $collected_data)
       <tr>
           <td>{{ $sr++ }}</td>
           <td>{{ $collected_data->employee_no ? "(" . $collected_data->employee_no . ") " . $collected_data->employee_name . "-" . $collected_data->employee_post : $collected_data->employee_name; }}</td>
           <td>{{ $collected_data->employee_type }}</td>
           <td>{{  $collected_data->getEmployeeBranch->location}}</td>
           <td>{{ $collected_data->cnic ? $collected_data->cnic : "-" }}</td>
           <td>{{ $collected_data->basic_sallary ?  $collected_data->basic_sallary : "-" }}</td>
       </tr>
       @endforeach
   </tbody>

   
</table>


<script>
    $("#search_employee").keyup(function () {

var value = this.value.toLowerCase().trim();

$("#employee_table_view tr").each(function (index) {
    if (!index) return;
    $(this).find("td").each(function () {
        var id = $(this).text().toLowerCase().trim();
        var not_found = (id.indexOf(value) == -1);
        $(this).closest('tr').toggle(!not_found);
        return not_found;
    });
});
});

</script>