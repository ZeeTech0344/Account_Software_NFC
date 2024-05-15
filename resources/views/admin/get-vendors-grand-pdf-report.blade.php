{{-- {{ $vendors_paid_amounts }} --}}


<style>
    table{
        border:1px solid rgb(232, 231, 231);
    }
    th,td{
        border:1px solid rgb(232, 231, 231);
        padding:3px;
        text-align: center;
    }
    
    h4{
        text-align: center;
    }
</style>

@php
    $sr = 1;
    $total_weight = 0;
    $total_amount = 0;
@endphp

<table width="100%"
cellspacing="0">
<thead>
    <tr>
        <th>Sr#</th>
        <th>Date</th>
        <th>Vendors</th>
        <th>T_Amount</th>
       
    </tr>
</thead>
<tbody>

    @foreach ($data as $collect_data)
    
    <tr>
        <td>{{ $sr++ }}</td>
        <td>{{ date_format(date_create($collect_data->created_at),"d-m-Y") }}</td>
        <td>{{ $collect_data->getEmployee->employee_name }}</td>
        <td>{{ number_format($collect_data->total_amount) }}</td>
    </tr>

    @php
        $total_weight = $total_weight + $collect_data->weight;
        $total_amount = $total_amount  + $collect_data->total_amount;

    @endphp

    @endforeach

<tr>
    <td colspan="8" style="text-align: left;">Total Weight: {{ $total_weight }}</td>
</tr>
<tr>
    <td colspan="8" style="text-align: left;">Total Amount: Rs.{{  number_format($total_amount) }}</td>
</tr>
<tr>
    <td colspan="8" style="text-align: left;">Total Pay Amount: Rs.{{ isset($vendors_paid_amounts[0]) ?  number_format($vendors_paid_amounts[0]->vendor_amount) : 0 }}</td>
</tr>
<tr>
    <td colspan="8" style="text-align: left;">Remaining Amount: Rs.{{  number_format($total_amount -  (isset($vendors_paid_amounts[0]) ? $vendors_paid_amounts[0]->vendor_amount : 0)) }}</td>
</tr>
</tbody>
</table>
