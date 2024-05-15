
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

@php
    $sr = 1;
    $total_amount = 0;
    $total_paid_amount = 0;
    foreach ($easypaisa_amount as $easypaisa_amounts) {
       $total_amount =  $total_amount + $easypaisa_amounts->sum;
    }

    //after old amount deduction
   $total_amount_paid_easypaisa =  ( $from_foodpanda_amount[0]->sum + 81926 + $total_amount) - (isset($easypaisa_old_amount_paid_sum[0]) ? $easypaisa_old_amount_paid_sum[0]->sum : 0);
@endphp


<table width="100%">
<caption>Easypaisa Paid List from {{ date_format(date_create($from),"d-m-Y") }}  to {{date_format(date_create($to),"d-m-Y")}} </caption>
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
                    if($collect_date->status !== "Return" ){
                        $total_paid_amount = $total_paid_amount +  $collect_date->amount;
                    }
                   
                @endphp
            </tr>
            
        @endforeach
        
        
           @if ($employee_others == "" && $type == "")
           <tr>
           <th colspan="7">Easypaisa  Amount (Was): Rs.{{ $total_amount_paid_easypaisa }}</th>
             </tr>
           @endif
        
        <tr>
            <th colspan="7">Total Paid Amount: Rs.{{ $total_paid_amount }}</th>
        </tr>
       
            @if ($employee_others == "" && $type == "")
            <tr>
            <th colspan="7">Total Remaining: Rs.{{$total_amount_paid_easypaisa - $total_paid_amount }}</th>
            </tr>
            @endif
        
    </tbody>
</table>



<script>





</script>