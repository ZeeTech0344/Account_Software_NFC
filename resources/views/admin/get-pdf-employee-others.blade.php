<style>
     th{
        text-align: left;
        border:1px solid black;
        padding:3px;
    }
    td{
        border:1px solid black;
        padding:3px;
    }
    table{
        border-collapse: collapse;
        width: 100%;
    }
    caption{
        padding:5px;
        border:1px solid black;
    }
</style>

@php
    $sr = 1;
@endphp


<table>
    <caption> Employee Report </caption>
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