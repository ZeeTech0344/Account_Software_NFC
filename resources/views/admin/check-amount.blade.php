

<style>
  
    .amount_div{
        padding:5px;
        color:white;
    }
</style>

<div>
<div class="bg-success amount_div">
    Easypaisa: Rs.{{ number_format( isset($easypaisa_amount) ?  $from_foodpanda_amount[0]->sum + $easypaisa_reserve_amount +  ($easypaisa_amount[0]->sum -  $paid_amount[0]->sum) : 0 )}}
</div>
<div class="bg-secondary amount_div">
    HBL: Rs.  {{ number_format(  $hbl_reserve_amount + ($hbl_amount[0]->sum + $from_foodpanda_amount_hbl[0]->sum) - $hbl_paid_amount[0]->sum) }}

    {{-- <!-- {{ isset($hbl_amount) ? (129600 - $from_foodpanda_amount_hbl[0]->sum) + ($hbl_amount[0]->sum - $hbl_paid_amount[0]->sum): 0 }} --> --}}
</div>


<div class="bg-danger amount_div">
    Locker: Rs.{{ number_format( isset($locker) ?($locker_reserve_amount + $from_foodpanda_amount_locker[0]->sum  + (isset($sum_locker_out_source[0]) ? $sum_locker_out_source[0]->sum : 0 ) + ($locker[0]->sum - $sadqa_caculate) ) - $sum_of_paid_locker[0]->sum : 0) }}
</div>

</div>
