
<style>

#container {
    height: 400px;
}

.highcharts-figure,
.highcharts-data-table table {
    min-width: 310px;
    max-width: 800px;
    margin: 1em auto;
}

#datatable {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

#datatable caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

#datatable th {
    font-weight: 600;
    padding: 0.5em;
}

#datatable td,
#datatable th,
#datatable caption {
    padding: 0.5em;
}

#datatable thead tr,
#datatable tr:nth-child(even) {
    background: #f8f8f8;
}

#datatable tr:hover {
    background: #f1f7ff;
}


</style>

@php

$mergedArray = array();
foreach ($create_data_by_month_demand as $item) {
    $mergedArray[$item["head_location"]] = $item["demand"];
}

// Merge the demand values into the first array
foreach ($create_data_by_month_sale as &$item) {
    if (isset($mergedArray[$item["head_location"]])) {
        $item["demand"] = $mergedArray[$item["head_location"]];
    }
}

// // Print the merged array
// echo "<pre>";
// print_r($create_data_by_month_sale);
// echo "</pre>";
@endphp
<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
 
    </p>

    <table id="datatable">
        <thead>
            <tr>
                <th>Branch</th>
                <th>Demand</th>
                <th>Sale</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($create_data_by_month_sale as $get_data)
                    <tr>
                        <td>{{ isset($get_data["head_location"]) ? $get_data["head_location"] : 0 }}</td>
                        <td>{{ isset($get_data["demand"]) ? $get_data["demand"] : 0 }}</td>
                        <td>{{ isset($get_data["sale"]) ? $get_data["sale"] : "-"}}</td>
                    </tr>
            @endforeach
        </tbody>
    </table>
</figure>


<script>

Highcharts.chart('container', {
    data: {
        table: 'datatable'
    },
    chart: {
        type: 'column'
    },
    title: {
        text: 'Branch Report (Sale & Demand)'
    },
   
    xAxis: {
        type: 'category'
    },
    yAxis: {
        allowDecimals: false,
        title: {
            text: 'Amount'
        }
    }
});



</script>
