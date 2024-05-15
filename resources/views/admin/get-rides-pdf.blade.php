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

<table width="100%"
        cellspacing="0">
        <caption>Rider Report</caption>
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

        <tbody>

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
                    
                   


                   



                   
                //    if($collected_data->status == "Paid"){

                //     $total_paid_amount = $total_paid_amount + $collected_data->amount;
                //     $total_paid_rides =  $total_paid_rides + $collected_data->rides;

                //     if($collected_data->getShift->location == "New City"){
                //         $new_city = $new_city + $collected_data->amount;
                //     }

                //     if($collected_data->getShift->location == "Basti"){
                //         $basti = $basti + $collected_data->amount;
                //     }

                //     if($collected_data->getShift->location == "Taxila"){
                //         $taxila = $taxila + $collected_data->amount;
                //     }

                //     if($collected_data->getShift->location == "Attock"){
                //         $attock = $attock + $collected_data->amount;
                //     }

                //     if($collected_data->getShift->location == "Pindi"){
                //         $pindi = $pindi + $collected_data->amount;
                //     }
                    
                //    }elseif($collected_data->status == "Unpaid"){
                    
                //     $total_unpaid_amounts = $total_unpaid_amounts +  $collected_data->amount;;
                //     $total_unpaid_rides =  $total_unpaid_rides + $collected_data->rides;

                //     if($collected_data->getShift->location == "New City"){
                //         $unpaid_new_city = $unpaid_new_city + $collected_data->amount;
                //     }

                //     if($collected_data->getShift->location == "Basti"){
                //         $unpaid_basti = $unpaid_basti + $collected_data->amount;
                //     }

                //     if($collected_data->getShift->location == "Taxila"){
                //         $unpaid_taxila = $unpaid_taxila + $collected_data->amount;
                //     }

                //     if($collected_data->getShift->location == "Attock"){
                //         $unpaid_attock = $unpaid_attock + $collected_data->amount;
                //     }

                //     if($collected_data->getShift->location == "Pindi"){
                //         $unpaid_pindi = $unpaid_pindi + $collected_data->amount;
                //     }


                //    }

                @endphp

            @endforeach



            {{-- <tr>
                <td colspan="8">
                    Total Paid Rides: {{ $total_paid_rides }}
                </td>
            </tr>
            <tr>
                <td colspan="8">
                    Total Paid Amount: {{  $total_paid_amount }}
                </td>
            </tr>
            <tr>
                <td colspan="8">
                  <b>Total Paid Amount To:  New City: {{  $new_city }} , Basti: {{ $basti }} , Taxila: {{ $taxila }} , Attock {{ $attock }} , Pindi {{ $pindi }}</b>
                </td>
            </tr>
            <tr>
                <td colspan="8">
                    Total Unpaid Rides: {{  $total_unpaid_rides }}
                </td>
            </tr>
            <tr>
                <td colspan="8">
                 <b> Total Unpaid Amount To:  New City: {{  $unpaid_new_city }} , Basti: {{ $unpaid_basti }} , Taxila: {{ $unpaid_taxila }} , Attock {{ $unpaid_attock }} , Pindi {{ $unpaid_pindi }} </b>
                </td>
            </tr>

            <tr>
                <td colspan="8">
                    Total Unpaid Amounts: {{ $total_unpaid_amounts  }}
                </td>
            </tr> --}}

            <tr>
                <td colspan="5">
                  Total Paid Amount To:  <b>  ( New City:{{  $new_city }} , Basti: {{ $basti }} , Taxila: {{ $taxila }} , Attock {{ $attock }} , Pindi {{ $pindi }} )</b>
                </td>
            </tr>

            <tr>
                
                <td colspan="5">
                    Total Paid Amounts: {{ "Rs.".$grand_total }}
                </td>
            </tr>

        </tbody>
    </table>