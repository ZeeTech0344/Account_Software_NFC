

{{-- {{ $return_amount_detail }} --}}


<style>
    table{
        border:1px solid black;
        border-collapse: collapse;
    }
    td,th{
        border:1px solid black;
        padding:3px;
    }
</style>


@php
    $count_return_amount = 0;
    $total_amount = 0;

    $count_original_amount = 0;


    // amount_recieved_from_easypaisa

    foreach ($amount_recieved_from_easypaisa as $count_amount){
        $count_original_amount =  $count_original_amount + $count_amount->current_amount;
    }

    foreach ($return_amount_detail as $return_amount){
        $count_return_amount =  $count_return_amount + $return_amount->return_amount;
    }
   
    $count_return;
@endphp

<table>
    <caption>Easypaisa Detail List</caption>
    <thead>
        <tr>
        <th>Date</th>
        <th>Paid To</th>
        <th>Purpose</th>
        <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($paid_detail as $paid)
                <tr>
                    <td>{{ date_format(date_create($paid->easypasia_amount_date),"d-m-Y") }}</td>
                    <td>{{ $paid->employees->employee_name }}</td>
                    <td>{{ $paid->purpose }}</td>
                    <td>{{ $paid->paid_amount }}</td>
                </tr>
            @php
                $total_amount = $total_amount + $paid->paid_amount;
            @endphp
        @endforeach
    <tr><td  colspan="4">Grand Total: {{ $total_amount }}</td></tr>
    <tr><td  colspan="4">Easypaisa Account: {{ $count_original_amount }}</td></tr>
    <tr><td  colspan="4">Remaining_Amount: {{ $count_original_amount - $total_amount}}</td></tr>
    </tbody>
</table>

