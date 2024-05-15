

<style>
    th{
        text-align: left;
        border:1px solid rgb(203, 200, 200);
        padding:3px;
    }
    td{ 
        border:1px solid rgb(203, 200, 200);
        padding:3px;
    }
    table{
        border-collapse: collapse;
        width: 100%;
    }
    caption{
        padding:5px;
        border:1px solid rgb(203, 200, 200);
    }

</style>




@php

$total_in_amount = 0;
$total_out_amount = 0;
$get_difference = 0;

$create_grand_array = array_merge($sum_of_sale_datewise , $array_created_for_sadqa_deduction ,$data, $foodpanda, $outsource);


usort($create_grand_array, function ($a, $b) {
    return strtotime($a['created_at']) - strtotime($b['created_at']);
});

@endphp

<table id="locker_view_get">
    <caption>Locker Detail (NFC)</caption>
    <thead>
        <tr>
            <th>Date</th>
            <th>Head</th>
            <th>In</th>
            <th>Out</th>
            <th>Diff.</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>


        @foreach ($create_grand_array as $get_date)

        <tr>
            <td>{{ date_format(date_create($get_date["created_at"]), "d-m-Y") }}</td>
            <td>{{ $get_date["head"]}}</td>
            <td class="amount_in">{{ $get_date["amount_status"] == "In" ? $get_date["amount"] : "-" }}
            </td>
            <td>
                {{ $get_date["amount_status"] == "Out" ? $get_date["amount"] : "-" }}
            </td>
        
            @php

                    if($get_date["amount_status"] == "In"){
                        $get_difference = $get_difference + $get_date["amount"];
                        $total_in_amount =  $total_in_amount + $get_date["amount"];
                    }elseif($get_date["amount_status"] == "Out"){
                        $get_difference = $get_difference - $get_date["amount"];
                        $total_out_amount =  $total_out_amount + $get_date["amount"];
                    }
                    
            @endphp

            {{-- this is value that get old amount (Grand final old amount) --}}
            <td>{{ $get_difference + $grand_final_old_amount}}</td>
            <td>{{ isset($get_date["remarks"]) ? $get_date["remarks"] : "-" }}</td>
               
        </tr>
       
        @endforeach
{{--         
    <tr>
        <td colspan="6">
            <b>Amount (Start) : {{ $grand_final_old_amount }} </b>
        </td>
    </tr> --}}



    <tr>
        <td colspan="6" id="set_in_amount">
            <b>Amount (In) : {{ $total_in_amount }}</b>
        </td>
    </tr> 
    <tr>
        <td colspan="6" id="set_out_amount">
            <b>Amount (Out) : {{ $total_out_amount }}</b>
        </td>
    </tr> 
    
    </tbody>

    

</table>
<script>



    $("#search_employee").keyup(function () {
    
    var value = this.value.toLowerCase().trim();
    
    $("#locker_view_get tr").each(function (index) {
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