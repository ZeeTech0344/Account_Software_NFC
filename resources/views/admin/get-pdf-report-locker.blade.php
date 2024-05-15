

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
    }
    caption{
        padding:5px;
        border:1px solid black;
    }

</style>


{{-- sadqa_caculate','sum_of_sale_datewise', 'locker' --}}


@php
    $sr = 1;
    $total_amount = 0;
    $total_paid_amount = 0;
    
     $total_paid_locker_old_amount = (isset($sum_of_paid_locker[0]) ? $sum_of_paid_locker[0]->sum : 0 );

     $outsource_locker_amount = $from_foodpanda_amount_locker[0]->sum + (isset($sum_locker_out_source[0]) ?   $sum_locker_out_source[0]->sum : 0 );
@endphp


<table width="100%">
<caption>Loacker Paid List from {{ date_format(date_create($from),"d-m-Y") }}  to {{date_format(date_create($to),"d-m-Y")}} </caption>
    <thead>
        <tr>
            <th>Sr#</th>
            <th>Paid_Date</th>
            <th>Paid_To</th>
            <th>Purpose</th>
            <th>Status</th>
            <th>Amount</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $collect_date)
            <tr>
                <td>{{  $sr++ }}</td>
                <td>{{  date_format(date_create($collect_date->created_at),"d-m-Y") }}</td>
                <td>{{  $collect_date->getEmployee->employee_name }}</td>
                <td>{{  $collect_date->purpose }}</td>
                <td>{{  $collect_date->status }}</td>
                <td>{{  $collect_date->amount }}</td>
                <td>{{  $collect_date->remarks }}</td>
                @php
                if($collect_date->status !== "Return"){
                    $total_paid_amount = $total_paid_amount +  $collect_date->amount;
                }
                    
                @endphp
            </tr>
            
        @endforeach
        
            @if ($employee_others == "" && $type == "")
            <tr>
            <th colspan="7">Locker Amount (Was): Rs. {{ (636300 + ($outsource_locker_amount + ($locker[0]->sum -  $sadqa_caculate))) - $total_paid_locker_old_amount  }}</th>
            </tr>
            @endif
        
        <tr>
           
            <th colspan="7">Total Paid Amount: Rs.{{ $employee_others == 137 ? $total_paid_amount + 200000 :  $total_paid_amount}}</th>
            
        </tr>
            @if ($employee_others == "" && $type == "")
            <tr>
            <th colspan="7">Total Remaining: Rs. {{    ( 636300 +  ($outsource_locker_amount + ($locker[0]->sum -  $sadqa_caculate)) - $total_paid_locker_old_amount ) - $total_paid_amount }}</th>
            </tr>   
            @endif
    </tbody>
</table>



<script>





</script>