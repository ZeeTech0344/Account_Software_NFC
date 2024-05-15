
<style>
     table{
        border:1px solid #e3e6f0;
        border-collapse: collapse;
        width: 100%;
    }
    th,td{
        border:1px solid #e3e6f0;
        padding:3px;
    }
</style>


@php
    $sr=1;
    $total_vendor_amount = 0;
    $total_vendor_pay_amount = 0;
    $total_remaining = 0;
@endphp

<table>
  
    <thead>
        <tr>
            <th>ID</th>
            <th>Vendor</th>
            <th>Total_Amount</th>
            <th>Pay_Amount</th>
            <th>Remaining</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vendors  as $get_detail)
                <tr>
                    <td>{{ $sr++ }}</td>
                    <td>{{ $get_detail->employee_name }}</td>
                    <td>{{ number_format($get_detail->get_vendors_reserve_amount_sum_total_amount) }}</td>
                    <td>{{  number_format($get_detail->get_vendors_pay_amount_sum_paid_amount) }}</td>
                    <td> {{  number_format($get_detail->get_vendors_reserve_amount_sum_total_amount - $get_detail->get_vendors_pay_amount_sum_paid_amount) }} </td>
                </tr>

                @php
                     $total_vendor_amount =  $total_vendor_amount  + $get_detail->get_vendors_reserve_amount_sum_total_amount;
                     $total_vendor_pay_amount =  $total_vendor_pay_amount  + $get_detail->get_vendors_pay_amount_sum_paid_amount;
                     $total_remaining =  $total_remaining + ($get_detail->get_vendors_reserve_amount_sum_total_amount - $get_detail->get_vendors_pay_amount_sum_paid_amount);
                @endphp

        @endforeach
        <tr>
            <td colspan="5">
                <b>Total Vendor Amount : {{  number_format( $total_vendor_amount ) }}</b>
            </td>
        </tr>
        <tr>
            <td colspan="5">
               <b> Total Pay Amount : {{   number_format($total_vendor_pay_amount)  }} </b>
            </td>
        </tr>
        <tr>
            <td colspan="5">
               <b> Total Remaining : {{   number_format($total_remaining)  }} </b>
            </td>
        </tr>
    </tbody>
</table>


