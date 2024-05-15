


<style>
    table{
        border:1px solid #e3e6f0;
    }
    th,td{
        border:1px solid #e3e6f0;
        padding:3px;
    }
    
</style>

@php
    $sr = 1;
   
    $total_amount = 0;
@endphp


<table width="100%"
cellspacing="0">
<thead>
    <tr>
        <th>Sr#</th>
        <th>Date</th>
        <th>Amount</th>
        <th>Pay To</th>
    </tr>
</thead>
<tbody>

    @foreach ($sadqa as $collect_data)
    
    <tr>
        <td>{{ $sr++ }}</td>
        <td>{{ date_format(date_create($collect_data->created_at),"d-m-Y") }}</td>
        <td>{{ $collect_data->pay_sadqa_amount }}</td>
        <td>{{ $collect_data->pay_to }}</td>
    </tr>

    @php
        
        $total_amount = $total_amount  + $collect_data->pay_sadqa_amount;

    @endphp

    @endforeach

<tr>
    <td colspan="4">Total Amount: {{ $total_amount }}</td>
</tr>
<!-- <tr>
    <td colspan="4">Grand Total Amount: Rs.{{ $total }}</td>
</tr> -->
<tr>
    <td colspan="4">Remaining Sadqa: Rs.{{ (($total + 161670) - $total_amount) }}  (Add remaining_amount of Rs. 161670 (27-07-2023) for balancing of sadqa excel sheet)</td>
</tr>
</tbody>
</table>
