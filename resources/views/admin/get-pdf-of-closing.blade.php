
<style>
    table,
    td,
    th {
        border: 1px solid black;
        text-align: center;
        padding:5px;
    }

    table {
        width: 100%;
    }
    h4{
        text-align: center;
    }
</style>


@php
        $sr = 1;
        $total = 0;
        
        //heads total
        $Demand = 0;
        $Sale = 0;
        $Foodpanda = 0;
        $HBL = 0;
        $EasyPaisa = 0;
        $Expenses = 0;
        $Pending = 0;
        $Cancel = 0;
        $Cash_in_hand = 0;
        // $sadqa = 0;

    $total_sum_sale_for_sadqa = 0;

        foreach($sum_of_sale_datewise as $get_sale_sum){
            $total_sum_sale_for_sadqa = $total_sum_sale_for_sadqa + ($get_sale_sum->sum > 0 ? ceil((($get_sale_sum->sum  / 100) * 2) / 10) * 10 : 0);
        }
                
        

 @endphp





<table class="table table-bordered table_employee_other" id="dataTable" width="100%" cellspacing="0">
    <thead> 
        <tr>
            <th>Sr#</th>
            <th>Date</th>
            <th>Head</th>
            <th>Location</th>
            <th>Amount</th>
        </tr>
     </thead>
    
    <tbody>

        @foreach ($data as $collect_data)
            <tr>
                <td>{{ $sr++ }}</td>
                <td>{{ $collect_data->date }}</td>
                <td style="text-align: left;">{{ $collect_data->head }}</td>
                <td>{{ $collect_data->location }}</td>
                <td>{{ $collect_data->sum }}</td>
            </tr>
            @php
                $total = $total + $collect_data->sum;
                if ($collect_data->head == 'Demand') {
                    $Demand = $Demand + $collect_data->sum;
                } elseif ($collect_data->head == 'Sale') {
                    $Sale = $Sale + $collect_data->sum;
                } elseif ($collect_data->head == 'Food Panda') {
                    $Foodpanda = $Foodpanda + $collect_data->sum;
                } elseif ($collect_data->head == 'HBL') {
                    $HBL = $HBL + $collect_data->sum;
                } elseif ($collect_data->head == 'Easypaisa') {
                    $EasyPaisa = $EasyPaisa + $collect_data->sum;
                } elseif ($collect_data->head == 'Expenses') {
                    $Expenses = $Expenses + $collect_data->sum;
                } elseif ($collect_data->head == 'Pending') {
                    $Pending = $Pending + $collect_data->sum;
                } elseif ($collect_data->head == 'Cancel') {
                    $Cancel = $Cancel + $collect_data->sum;
                } elseif ($collect_data->head == 'Cash In Hand') {
                    if ($collect_data->location == 'New City' || $collect_data->location == 'Basti' || $collect_data->location == 'Taxila') {
                        $Cash_in_hand = $Cash_in_hand + $collect_data->sum;
                    }
                }
                
            @endphp
        @endforeach
       
        <tr>
            <th style="text-align: left" colspan="5">
                Demand: Rs.{{ $Demand }}
            </th>
        </tr>
        <tr>
            <th style="text-align: left" colspan="5">
                Sale: Rs.{{ $Sale }}
            </th>
        </tr>

        <tr>
            <th style="text-align: left" colspan="5">
                Foodpanda: Rs.{{ $Foodpanda }}
            </th>
        </tr>

        <tr>
            <th style="text-align: left" colspan="5">
                HBL: Rs.{{ $HBL }}
            </th>
        </tr>

        <tr>
            <th  style="text-align: left" colspan="5">
                Easypaisa: Rs.{{ $EasyPaisa }}
            </th>
        </tr>

        <tr>
            <th style="text-align: left" colspan="5">
                Expense: Rs.{{ $Expenses }}
            </th>
        </tr>

        <tr>
            <th style="text-align: left" colspan="5">
                Pending: Rs.{{ $Pending }}
            </th>
        </tr>


        <tr>
            <th  style="text-align: left" colspan="5">
                Cancel: Rs.{{ $Cancel }}
            </th>
        </tr>

        <tr>
            <th style="text-align: left" colspan="5">
                Total Cash In Hand (Three Branches):  Rs.{{ $Cash_in_hand }}
            </th>
        </tr>

        <tr>
            <th  style="text-align: left" colspan="5">
                Total Sadqa:  Rs.{{ $Sale>0 ?  $total_sum_sale_for_sadqa : 0 }}
            </th>
        </tr>

        <tr>
            <th style="text-align: left" colspan="5">
                Total Cash In Hand (After Sadqa Deduction): Rs.{{ $Cash_in_hand > 0 ?  $Cash_in_hand - $total_sum_sale_for_sadqa : 0 }}
               
            </th>
        </tr>
    </tbody>

</table>


{{-- <tr><th colspan="7">Sadqah: Rs. {{  $sale > 0 ? ceil(($sale /100 * 2)/10) * 10 : 0 }} ({{ $sale > 0 ? number_format((ceil(($sale /100 * 2)/10) * 10)/$sale*100,4) :"" }})(%)</th></tr>
<tr><th colspan="7">Grand Total: Rs. {{ $sale > 0 ?  $grand_total - (ceil(($sale /100 * 2)/10) * 10) : 0 }}</th></tr>
<tr><th colspan="7">Cash in hand (After sadqa deduction): {{  $sale > 0 ? $inner_total - (ceil(($sale /100 * 2)/10) * 10) : 0}}</th></tr> --}}
