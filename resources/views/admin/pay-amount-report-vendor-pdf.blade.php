<style>
    table{
        border:1px solid black;
    }
    td, th{
        border:1px solid black;
        padding: 3px;
    }
    h4{
        text-align: center;
    }
</style>


@php
    $sr=1;
    $total = 0; 
@endphp

<h4>Vendor Paid Report</h4>
<table width="100%"
cellspacing="0">
<thead>
    <tr>
        <th>Sr#</th>
        <th>Date</th>
        <th>Vendor</th>
        <th>Paid Amount</th>
        <th>Account Name</th>
        <th>Remarks</th>
      
    </tr>
</thead>

<tbody>
    @foreach ($data as $collect_data)
        <tr>
            <td>{{ $sr++ }}</td>
            <td>{{ date_format(date_create($collect_data->created_at),"d-m-Y") }}</td>
            <td>{{ $collect_data->getEmployee->employee_name }}</td>
            <td>{{ $collect_data->paid_amount }}</td>
            <td>{{ $collect_data->account_name  }}</td>
            <td>{{ $collect_data->remarks }}</td>
        </tr>
        @php
            $total = $total + $collect_data->paid_amount ;
        @endphp
    @endforeach
    <tr>
        <td colspan="6">Total Amount: {{ $total }}</td>
    </tr>
</tbody>
</table>