<style>
    th{
        text-align: left;
        border:1px solid rgb(206, 198, 198);
        padding:3px;
    }
    td{
        border:1px solid rgb(206, 198, 198);
        padding:3px;
    }
    table{
        border-collapse: collapse;
    }
    caption{
        padding:5px;
        border:1px solid rgb(206, 198, 198);
    }

</style>

@php

    $total_unpaid_rides = 0;
    $total_paid_rides = 0;

    $total_paid_amount = 0;
    $total_unpaid_amounts = 0;
    $new_city = 0;
    $basti = 0;
    $taxila = 0;
    $attock = 0;
    $pindi = 0;

    $unpaid_new_city = 0;
    $unpaid_basti = 0;
    $unpaid_taxila = 0;
    $unpaid_attock = 0;
    $unpaid_pindi = 0;

    $grand_total = 0;

@endphp
<div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_employee" name="search" placeholder="Search Employee......."  class="form-control w-25" >

</div>
<table width="100%"
        cellspacing="0">
        {{-- <caption>Rider Report</caption> --}}
        <thead>
            <tr>
                {{-- <th>Date</th> --}}
                <th>Date</th>
                <th>Employee</th>
                <th>Shift</th>
                <th>Rides</th>
                <th>Amount</th>
                {{-- <th>Status</th> --}}
                {{-- <th>Paid_Date</th> --}}
                {{-- <th>Acc_Name</th> --}}
            </tr>
        </thead>

        <tbody id="rider_view_list">

            @foreach ($data as $collected_data)
                <tr>
                    <td>{{ date_format(date_create($collected_data->created_at), "d-m-Y") }}</td>
                    <td>{{ $collected_data->getEmployee->employee_name }}</td>
                    <td>{{ $collected_data->getShift->location }}</td>
                    <td>{{ $collected_data->rides }}</td>
                    <td>{{ $collected_data->amount }}</td>
                    {{-- <td>{{ $collected_data->status }}</td> --}}
                    {{-- <td>{{ $collected_data->paid_date }}</td> --}}
                    {{-- <td>{{ $collected_data->account_name }}</td> --}}
                </tr>

                @php

                $grand_total =  $grand_total + $collected_data->amount;


                    // $total_paid_amount = $total_paid_amount + $collected_data->amount;
                    // $total_paid_rides =  $total_paid_rides + $collected_data->rides;

                    if($collected_data->getShift->location == "New City"){
                        $new_city = $new_city + $collected_data->amount;
                    }

                    if($collected_data->getShift->location == "Basti"){
                        $basti = $basti + $collected_data->amount;
                    }

                    if($collected_data->getShift->location == "Taxila"){
                        $taxila = $taxila + $collected_data->amount;
                    }

                    if($collected_data->getShift->location == "Attock"){
                        $attock = $attock + $collected_data->amount;
                    }

                    if($collected_data->getShift->location == "Pindi"){
                        $pindi = $pindi + $collected_data->amount;
                    }
                    
                

                @endphp

            @endforeach



            <tr>
                <td colspan="5" style="color:#4e73df; font-weight:bolder;">
                  Total Paid Amount To:  ( New City:{{  $new_city }} , Basti: {{ $basti }} , Taxila: {{ $taxila }} , Attock {{ $attock }} , Pindi {{ $pindi }} )
                </td>
            </tr>

            <tr>
                
                <td colspan="5" style="color:#4e73df; font-weight:bolder;">
                    Total Paid Amounts: {{ "Rs.".$grand_total }}
                </td>
            </tr>

        </tbody>
    </table>


    <script>
        $("#search_employee").keyup(function () {
    
    var value = this.value.toLowerCase().trim();
    
    $("#rider_view_list tr").each(function (index) {
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