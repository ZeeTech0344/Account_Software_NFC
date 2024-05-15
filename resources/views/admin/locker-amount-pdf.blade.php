

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

<table>
    <caption>Locker Amount {{ date_format(date_create($from),"d-m-Y") }} to {{ date_format(date_create($to),"d-m-Y")  }}</caption>
    <thead>
        <th>Date</th>
        <th>Amount</th>
        <th>Operator</th>
        <th>Remarks</th>
    </thead>

@php
    $total = 0;
@endphp


    <tbody>
        @foreach ($data as $collect_data)
        <tr>
            <td>{{ date_format(date_create($collect_data->created_at),"d-m-Y") }}</td>
            <td>{{ $collect_data->amount }}</td>
            <td>{{ $collect_data->operator }}</td>
            <td>{{ $collect_data->remarks ?   $collect_data->remarks : "-"}}</td>
        </tr>
        @php
            $total = $total + $collect_data->amount;
        @endphp
        @endforeach
        <tr>
            <td colspan="4">Total Amount: Rs.{{ $total }}</td>
        </tr>
    </tbody>
</table>
