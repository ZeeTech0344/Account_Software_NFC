<style>
    table{
        border:1px solid #e3e6f0;
        border-collapse: collapse;
        width: 100%;
    }
    td, th{
        border:1px solid #e3e6f0;
        padding: 3px;
        text-align: left;
      
    }
</style>

@php
    $sr = 1;
@endphp
<div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_employee" name="search" placeholder="Search Employee......." onchange="checkValues(this)" class="form-control w-25" >

</div>
<h5 style="text-align: center;">Salary Sheet ({{ date_format(date_create($month),"M-y") }})</h5>
<table id="paid_salary_table">
  
    <thead>
        <th  style="text-align: center">Sr No</th>
        <th>Paid_Date</th>
        <th>Name</th>
        <th>Post</th>
        <th>Branch</th>
        <th>Salary</th>
    </thead>
    <tbody>
        @foreach ($data as $salary)
                
                <tr>
                    <td style="text-align: center">{{ $sr++ }}</td>
                    <td>{{ date_format(date_create($salary->created_at),"d-m-Y") }}</td>
                    <td>{{ $salary->employee->employee_name}}</td>
                    <td>{{ $salary->employee->employee_post }}</td>
                    <td>{{ $salary->employee->getEmployeeBranch->location }}</td>
                    <td><ul>
                            <li>
                                Basic Salary: {{ $salary->employee->basic_sallary }}
                            </li>
                            <li>
                                Advance: {{ $salary->advance }}
                            </li>
                            <li>
                                Amount: {{ $salary->amount }}
                            </li>
                        </ul>
                    </td>
                </tr>
        @endforeach
    </tbody>
</table>


<script>
     $("#search_employee").keyup(function () {

var value = this.value.toLowerCase().trim();

$("#paid_salary_table tr").each(function (index) {
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