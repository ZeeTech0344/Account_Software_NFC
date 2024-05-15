

<style>
    th{
        text-align: left;
        border:1px solid rgb(172, 168, 168);
        padding:3px;
    }
    td{ 
        border:1px solid rgb(172, 168, 168);
        padding:3px;
    }
    table{
        border-collapse: collapse;
    }
    caption{
        padding:5px;
        border:1px solid rgb(172, 168, 168);
    }

</style>


{{-- sadqa_caculate','sum_of_sale_datewise', 'locker' --}}


@php
    $sr = 1;
    $total_amount = 0;
    $total_paid_amount = 0;
    
     $total_paid_locker_old_amount = (isset($sum_of_paid_locker[0]) ? $sum_of_paid_locker[0]->sum : 0 );

     $outsource_locker_amount = $from_foodpanda_amount_locker[0]->sum + (isset($sum_locker_out_source[0]) ?   $sum_locker_out_source[0]->sum : 0 );

     $final_amount = 636300 +  $sum_locker_out_source[0]->sum + $from_foodpanda_amount_locker[0]->sum + ($locker[0]->sum -  $sadqa_caculate) - $total_paid_locker_old_amount ;
     $create_diff = $final_amount;
@endphp


<table width="100%">
{{-- <caption>Loacker Paid List from {{ date_format(date_create($from),"d-m-Y") }}  to {{date_format(date_create($to),"d-m-Y")}} </caption> --}}
    <thead>
        <tr>
            <th>Sr#</th>
            <th>Paid_Date</th>
            <th>Paid_To</th>
            <th>Purpose</th>
            <th>Status</th>
            {{-- <th>Amount</th> --}}
            <th>Out</th>
            <th>In</th>
            <th>Diff.</th>
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
                <td></td>
                <td>{{  number_format($create_diff = $create_diff - $collect_date->amount)  }}</td>

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
            <th colspan="9">Locker Amount (Was): Rs. {{ (636300 + ($outsource_locker_amount + ($locker[0]->sum -  $sadqa_caculate))) - $total_paid_locker_old_amount  }}</th>
            </tr>
            @endif
        
        <tr>
           
            <th colspan="9">Total Paid Amount: Rs. {{ $employee_others == 137 ? ($total_paid_amount + 200000) . " (This amount include Rs. Two Lac of old installment)" :  $total_paid_amount}}</th>
            
        </tr>
            @if ($employee_others == "" && $type == "")
            <tr>
            <th colspan="9">Total Remaining: Rs. {{    ( 636300 +  ($outsource_locker_amount + ($locker[0]->sum -  $sadqa_caculate)) - $total_paid_locker_old_amount ) - $total_paid_amount }}</th>
            </tr>   
            @endif
    </tbody>
</table>


{{-- 

<style>
    th{
        text-align: left;
        border:1px solid rgb(228, 228, 228);
        padding:3px;
    }
    td{
        border:1px solid rgb(228, 228, 228);
        padding:3px;
    }
    table{
        border-collapse: collapse;
    }
    caption{
        padding:5px;
        border:1px solid rgb(228, 228, 228);
    }

</style>


{{-- sadqa_caculate','sum_of_sale_datewise', 'locker' --}}


@php
    // $sr = 1;
    // $total_amount = 0;
    // $total_paid_amount = 0;
    
    //   $total_paid_locker_old_amount = (isset($sum_of_paid_locker[0]) ? $sum_of_paid_locker[0]->sum : 0 );

    //  $outsource_locker_amount = $from_foodpanda_amount_locker[0]->sum + (isset($sum_locker_out_source[0]) ?   $sum_locker_out_source[0]->sum : 0 );

    //  $final_amount = 636300 + ( ($sum_locker_out_source[0]->sum + $from_foodpanda_amount_locker[0]->sum + ($locker[0]->sum -  $sadqa_caculate) ) - $total_paid_locker_old_amount );

    //    $final_amount = 636300 +  $sum_locker_out_source[0]->sum + $from_foodpanda_amount_locker[0]->sum + ($locker[0]->sum -  $sadqa_caculate) - $total_paid_locker_old_amount ;
    //    $create_diff = $final_amount;
@endphp

{{-- <div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_employee" name="search" placeholder="Search Employee......." onchange="checkValues(this)" class="form-control w-25" >

</div>
<table width="100%" id="locker_view"> --}}
{{-- <caption>Loacker Paid List from {{ date_format(date_create($from),"d-m-Y") }}  to {{date_format(date_create($to),"d-m-Y")}} </caption> --}}
    {{-- <thead>
        <tr>
            <th>Sr#</th>
            <th>Paid_Date</th>
            <th>Paid_To</th>
            <th>Purpose</th>
            <th>Status</th>
            <th>Amount</th>
            <th>Diff.</th>
            <th>Remarks</th>
        </tr>
    </thead> --}}
    {{-- <tbody>
        @foreach ($data as $collect_date)
            <tr>
                <td>{{  $sr++ }}</td>
                <td>{{  date_format(date_create($collect_date->created_at),"d-m-Y") }}</td>
                <td>{{  $collect_date->getEmployee->employee_name }}</td>
                <td>{{  $collect_date->purpose }}</td>
                <td>{{  $collect_date->status }}</td>
                <td>{{  number_format($collect_date->amount) }}</td>
                <td>{{  number_format($create_diff = $create_diff - $collect_date->amount)  }}</td>
                <td>{{  $collect_date->remarks }}</td>
                @php
                
                    $total_paid_amount = $total_paid_amount +  $collect_date->amount;
                
                    
                @endphp
            </tr>
            
        @endforeach
        
           
            <tr>
            <th colspan="8">Locker Amount (Was): Rs. {{ number_format($final_amount) }} </th>
            </tr>
          
        
        <tr> --}}
            {{-- <th colspan="8">Total Paid Amount: Rs.{{ number_format($total_paid_amount) }}</th>
            
        </tr>
          
            <tr>
            <th colspan="8">Total Remaining: Rs. {{  number_format($final_amount - $total_paid_amount)  }}</th>
            </tr>   
           
    </tbody>
</table>
 --}}


<script>

$("#search_employee").keyup(function () {

var value = this.value.toLowerCase().trim();

$("#locker_view tr").each(function (index) {
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