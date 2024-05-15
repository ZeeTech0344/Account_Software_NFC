

<style>

    .highcharts-figure,
.highcharts-data-table table {
    min-width: 360px;
    max-width: 800px;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}

.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

</style>




@php
 

    // Assuming you have your data arrays as $salesArray, $demandArray, $easypaisaArray, and $lockerArray

// Create an associative array to store merged data
$mergedArray = array();

// Merge the data based on month for sale
foreach ($create_data_by_sale as $saleItem) {
    $month = $saleItem["month"];
    $mergedArray[$month]["sale"] = $saleItem["sale"];
}

// Merge the data based on month for demand
foreach ($create_data_by_demand as $demandItem) {
    $month = $demandItem["month"];
    $mergedArray[$month]["demand"] = $demandItem["demand"];
}

// Merge the data based on month for easypaisa
foreach ($create_data_by_easypaisa_out as $easypaisaItem) {
    $month = $easypaisaItem["month"];
    $mergedArray[$month]["easypaisa"] = $easypaisaItem["easypaisa"];
}



// Merge the data based on month for locker
foreach ($create_data_by_locker_out as $lockerItem) {
    $month = $lockerItem["month"];
    $mergedArray[$month]["locker"] = $lockerItem["locker"];
    
}




// Merge the data based on month for locker
foreach ($create_data_by_hbl_out as $lockerItem) {
    $month = $lockerItem["month"];
    $mergedArray[$month]["locker"] = $lockerItem["hbl"];
}


// Merge the data based on month for locker
foreach ($create_data_by_locker_in as $lockerItem) {
    $month = $lockerItem["month"];
    $mergedArray[$month]["locker_in"] = $lockerItem["locker_in"];
}


$array_for_chart["month"] = array();
$array_for_chart["profit"] = array();



@endphp

<table class="table">
    <thead>
        <tr>
            <th>Month</th>
            <th>Demand</th>
            <th>Sale</th>
            <th>Easypaisa Out</th>
            <th>HBL Out</th>
            <th>Locker Out</th>
            <th>Locker In</th>
            <th>Profit/Loss</th>
        </tr>
    </thead>
    <tbody>


        @foreach ($mergedArray as $month => $data)

                
                <tr>
                    <td>{{ $month }}</td>
                    <td>
                        {{ $data["demand"] }}
                    </td>
                    <td>
                        {{ $data["sale"] }}
                    </td>
                    <td>
                        {{ isset($data["easypaisa"]) ? $data["easypaisa"] : "-" }}
                    </td>
                    <td>
                        {{ isset( $data["hbl"] ) ?  $data["hbl"] : "-"}}
                    </td>

                    <td>
                        {{isset( $data["locker"] ) ? $data["locker"] : "-" }}
                    </td>

                    <td>
                        {{isset( $data["locker_in"] ) ? $data["locker_in"] : "-" }}
                    </td>

                    <td>
                        {{ $profit = (($data["sale"] - $data["demand"]) + (isset( $data["locker_in"] ) ? $data["locker_in"] : 0)  )- ( (isset($data["easypaisa"]) ? $data["easypaisa"] : 0)  + (isset( $data["hbl"] ) ?  $data["hbl"] : 0) + (isset( $data["locker"] ) ? $data["locker"] : 0))}}
                    </td>

                </tr>

                @php

                array_push($array_for_chart["month"], $month);
                array_push($array_for_chart["profit"], $profit);
                    
                @endphp

        @endforeach
    </tbody>
</table>










<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
        
    </p>
</figure>



<script>


var month = '<?php echo json_encode($array_for_chart["month"]); ?>';
month_created = JSON.parse(month);


var profit = '<?php echo json_encode($array_for_chart["profit"]); ?>';
profit_created = JSON.parse(profit);


//convert to number profit
final_profit = profit_created.map(Number);

console.log(final_profit);

   
Highcharts.chart('container', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Monthly Profit & Loss Report'
    },
    subtitle: {
        text: 'NFC'
            
    },
    xAxis: {
        
    categories: month_created,
    labels: {
            enabled: true
        }
    
    },
    yAxis: {
      
        title: {
            text: 'Amount Flow'
        }
    },
    plotOptions: {
        series: {
            dataLabels: {
                enabled: false
            }
        }
    },
    series: [{
        
        name: 'Profit',
        data: final_profit
    }]
});

</script>


