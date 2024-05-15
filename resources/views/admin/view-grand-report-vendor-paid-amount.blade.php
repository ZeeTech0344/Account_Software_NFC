<style>
    table{
        border:1px solid #e3e6f0;
        width: 100%;
        border-collapse: collapse;
    }
    td,th{
        border:1px solid #e3e6f0;
        text-align: center;
    }
</style>


@php
    
    $sr = 1;

@endphp

<table>
    <thead>
        <th>Id</th>
        <th>Vendors</th>
        <th>Paid Amounts</th>
        <th>Old Amount</th>
        <th>Remaining Amount</th>
    </thead>
    <tbody>

        @foreach ($pay_vendor_amounts as $pay_vendor_amount)
        
        <tr>
            <td>{{ $sr++ }}</td>
            <td>{{ $pay_vendor_amount->employee_name }}</td>
            <td>{{ $pay_vendor_amount->sum }}</td>


            @foreach ($vendor_detail_sum as $vendor_detail)
            @if ($vendor_detail->employee_name ==  $pay_vendor_amount->employee_name)
                <td>{{ $vendor_detail->sum  }}</td>
            @endif
            @endforeach

            @foreach ($vendor_detail_sum as $vendor_detail)
            @if ($vendor_detail->employee_name ==  $pay_vendor_amount->employee_name)
                <td>{{  $vendor_detail->sum - $pay_vendor_amount->sum}}</td>
              
    
                @endif
            @endforeach
        </tr>

        @endforeach

        
    </tbody>
</table>

