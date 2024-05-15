
<style>
    table{
        border:1px solid black;
        border-collapse: collapse;
    }
    td,th, caption{
        border:1px solid black;
        padding:3px;
    }
</style>


@php
    $paid = 0;
    $unpaid = 0;
    $weight_unpaid = 0;
    $weight_paid = 0;
@endphp

<table width="100%"
cellspacing="0">
<caption>Vendor List from {{ date_format(date_create($from),"d-m-Y") }} to {{  date_format(date_create($to),"d-m-Y") }}</caption>
<thead>
    <tr>
        <th>Date</th>
        <th>Employee</th>
        <th>Location</th>
        <th>Weight</th>
        <th>Measurement</th>
        <th>Rate</th>
        <th>T_Amount</th>
        <th>Status</th>
        <th>Paid_Date</th>
        <th>Acc_Name</th>
    </tr>
</thead>

<tbody>
@foreach ($data as $collectData)
        <tr>
            <td>{{ date_format(date_create($collectData->created_at), "d-m-Y") }}</td>
            <td>{{ $collectData->getEmployee->employee_name }}</td>
            <td>{{ $collectData->getBranch->location }}</td>
            <td>{{ $collectData->weight }}</td>
            <td>{{ $collectData->measurement }}</td>
            <td>{{ $collectData->rate }}</td>
            <td>{{ $collectData->total_amount }}</td>
            <td>{{ $collectData->status }}</td>
            <td>{{ date_format(date_create($collectData->paid_date), "d-m-Y") }}</td>
            <td>{{ $collectData->account_name }}</td>
        </tr>
        @php
           if($collectData->status == "Paid"){
            $paid = $paid + $collectData->total_amount;
            $weight_paid = $weight_paid +  $collectData->weight;

           }elseif($collectData->status == "Unpaid"){
            $unpaid = $unpaid + $collectData->total_amount;
            $weight_unpaid = $weight_unpaid +  $collectData->weight;
           }



        @endphp
@endforeach

<tr>
    <td colspan="10">Total Amount Paid: {{ $paid }}</td>
</tr>
<tr>
    <td colspan="10">Total Amount Unpaid: {{ $unpaid }}</td>
</tr>
<tr>
    <td colspan="10">Total Weight (Unpaid): {{ $weight_unpaid }} KG/Liter</td>
</tr>
<tr>
    <td colspan="10">Total Weight (Paid): {{ $weight_paid }} KG/Liter</td>
</tr>

<tr>
    <td colspan="10">Grand Weight: {{ $weight_paid+$weight_unpaid }} KG/Liter</td>
</tr>

</tbody>
</table>
