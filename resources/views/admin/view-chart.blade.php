

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


<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
        
    </p>
</figure>

@php



    $months = array_column($create_data_by_month_sale, 'month');
    
    $sale = array_column($create_data_by_month_sale, 'sum');


    $months_demand = array_column($create_data_by_month_demand, 'month');
    
    $demand = array_column($create_data_by_month_demand, 'sum');

  


   

@endphp

<script>

    //sale
    var convert_to_jons_month = '<?php echo json_encode($months); ?>';
    months = JSON.parse(convert_to_jons_month);

    var convert_to_jons_sale = '<?php echo json_encode($sale); ?>';
    sale = JSON.parse(convert_to_jons_sale);

    
    final_sale = sale.map(Number);
   


     //demand
    var convert_to_jons_month_demand = '<?php echo json_encode($months_demand); ?>';
    months_demand = JSON.parse(convert_to_jons_month);

    var convert_to_jons_sale_demand = '<?php echo json_encode($demand); ?>';
    demand = JSON.parse(convert_to_jons_sale_demand);

    
    final_demand = demand.map(Number);

   

// Data retrieved https://en.wikipedia.org/wiki/List_of_cities_by_average_temperature
Highcharts.chart('container', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Monthly Average Report Demand & Sale'
    },
    subtitle: {
        text: 'NFC'
            
    },
    xAxis: {
        
    categories: months,
    labels: {
            enabled: false
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
        
        name: 'Sale',
        data: final_sale
    }, {
       
        name: 'Demand',
        data: final_demand
    }]
});

</script>
