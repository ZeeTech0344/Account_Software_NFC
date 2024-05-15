{{-- {{ $closing }} --}}

{{-- {{ $heads_name }} --}}

<style>

    table{
        width:100%;
        border-collapse: collapse;
        
    }

    td,th {
        border: 1px solid black;
        text-align: left;
        padding: 5px;
    }
    h4{
        text-align: center;
    }
    
</style>

@php
    
    

    $head_location_array = [];
    
    foreach ($head_locations as $head_location) {
        array_push($head_location_array, $head_location->location);
    }
    
    $create_head_array = [];
    
    foreach ($heads_name as $head_name_value) {
        array_push($create_head_array, $head_name_value->head);
    }
    
    // $create_head_array[] = "Different Account";
    // $create_head_array[] = "Total";
    // $create_head_array[] = "Extra";
    // $create_head_array[] = "Cash In Hand";
    
    // head_locations
    
    $final_array = [];
    
    foreach ($create_head_array as $key => $head_name) {
        $create_internal_array = [];
        foreach ($head_location_array as $key => $head_location) {
            array_push($create_internal_array, $head_location);
        }
        $final_array[$head_name] = array_flip($create_internal_array);
    }
    
    foreach ($create_head_array as $inner_key => $inner_value) {
        foreach ($head_location_array as $outer_key => $outer_value) {
            $final_array[$inner_value][$outer_value] = 0;
        }
    }
    
    foreach ($closing as $close) {
        $final_array[$close->heads->head][$close->locations->location] = $close->amount;
    }


//create array for get difference in account
//get slice of array
$new_array = array_slice( $final_array,2,6);

// echo "<pre>";
// print_r($new_array);
// echo "</pre>";
//get sum of array
//these array will get all difference of branches
$New = array_sum(array_column($new_array, 'New City'));


//count grand total
$grand_total = 0;


@endphp




<h4>Closing Slip {{ isset($id) ? date_format(date_create($id),"d-m-Y")  : date_format(date_create($date),"d-m-Y")  }} (NFC)</h4>
<table>
    <tr>
        <th>Head</th>
        @foreach ($head_location_array as $location)
        {{-- //head location --}}
            {{-- <th>{{ $location !== "Pindi" ? $location : "" }}</th> --}}
            @php
                if($location !== "Pindi"){
                    echo "<th>".$location."</th>";
                }
            @endphp
        @endforeach
        <th>Total</th>
    </tr>
    @foreach ($final_array as $key_outer => $value)
        <tr>
            @php
                $inner_total = 0;
            @endphp
            {{-- head name --}}
            <th>{{ $key_outer }}</th>
            @foreach ($value as $key_inner => $inner_value)
            {{-- head values --}}
            {{-- <td> {{ $key_inner !== "Pindi" ? $inner_value : ""}} </td> --}}

            @php
            if($key_inner !== "Pindi"){
                echo "<td>".$inner_value."</td>";
            }
        @endphp

            @php         

                  $grand_total = $grand_total + $inner_value;

                  if($key_outer == "Cash In Hand"){
                    if($key_inner == "New City" ||  $key_inner == "Basti" ||$key_inner == "Taxila" ){
                        $inner_total =  $inner_total + $inner_value;
                    }
                
                  
                  }elseif($key_outer == "Easypaisa"){

                    if($key_inner == "New City" ||  $key_inner == "Basti" || $key_inner == "Taxila" ||  $key_inner == "Attock" ){
                     $inner_total =  $inner_total + $inner_value;
                    }

                  }elseif($key_outer == "HBL"){

                    if($key_inner == "New City" ||  $key_inner == "Basti" || $key_inner == "Taxila" ||  $key_inner == "Attock" ){
                     $inner_total =  $inner_total + $inner_value;
                    }

                  } else{
                    $inner_total =  $inner_total + $inner_value;
                  }



                  //old code
                //   if($key_outer == "Cash In Hand"){
                //     if($key_inner == "New City" ||  $key_inner == "Basti" ||$key_inner == "Taxila" ){
                //         $inner_total =  $inner_total + $inner_value;
                //     }
                
                //   }else{
                //     $inner_total =  $inner_total + $inner_value;
                //   }

            @endphp
            @endforeach
            <th>{{ $inner_total }}</th>
        </tr>
        
    @endforeach
   <tr>
    <th>Difference</th>
    <td>{{array_sum(array_column($new_array, 'New City'))}}</td>
    <td>{{array_sum(array_column($new_array, 'Basti'))}}</td>
    <td>{{array_sum(array_column($new_array, 'Taxila'))}}</td>
    <td>{{array_sum(array_column($new_array, 'Attock'))}}</td>
    <td>
        {{array_sum(array_column($new_array, 'Chakwal'))}}
    </td>
    <th>
        {{-- grand total of difference --}}
        {{ 
          array_sum(array_column($new_array, 'New City')) +
            array_sum(array_column($new_array, 'Basti')) +
            array_sum(array_column($new_array, 'Taxila')) +
           array_sum(array_column($new_array, 'Attock')) +
           array_sum(array_column($new_array, 'Pindi')) +
           array_sum(array_column($new_array, 'Chakwal')) 
            
        }}

    </th>
   </tr>
   <tr>

    <th>Total</th>
    <td>{{ $final_array["Sale"]["New City"] - array_sum(array_column($new_array, 'New City')) }}</td>
    <td>{{ $final_array["Sale"]["Basti"] - array_sum(array_column($new_array, 'Basti')) }}</td>
    <td>{{ $final_array["Sale"]["Taxila"] - array_sum(array_column($new_array, 'Taxila')) }}</td>
    <td>{{ $final_array["Sale"]["Attock"] - array_sum(array_column($new_array, 'Attock'))}}</td>
    <td>{{ $final_array["Sale"]["Chakwal"] - array_sum(array_column($new_array, 'Chakwal')) }}</td>
    <th>{{ 
    
    //calculate total

    //deduct sadqah from this value
    $grand_total = $final_array["Sale"]["New City"] - array_sum(array_column($new_array, 'New City')) +
    $final_array["Sale"]["Basti"] - array_sum(array_column($new_array, 'Basti')) +
    $final_array["Sale"]["Taxila"] - array_sum(array_column($new_array, 'Taxila')) +
    $final_array["Sale"]["Attock"] - array_sum(array_column($new_array, 'Attock')) +
    $final_array["Sale"]["Pindi"] - array_sum(array_column($new_array, 'Pindi')) +
    $final_array["Sale"]["Chakwal"] - array_sum(array_column($new_array, 'Chakwal'))
    
    }}</th>
   </tr>
   <tr>
    <th>Extra</th>
    <td>{{ $final_array["Cash In Hand"]["New City"] - ($final_array["Sale"]["New City"] - array_sum(array_column($new_array, 'New City'))) }}</td>
    <td>{{ $final_array["Cash In Hand"]["Basti"] -  ($final_array["Sale"]["Basti"] - array_sum(array_column($new_array, 'Basti'))) }}</td>
    <td>{{ $final_array["Cash In Hand"]["Taxila"] - ($final_array["Sale"]["Taxila"] - array_sum(array_column($new_array, 'Taxila'))) }}</td>
    <td>{{ $final_array["Cash In Hand"]["Attock"] - ($final_array["Sale"]["Attock"] - array_sum(array_column($new_array, 'Attock'))) }}</td>
    <td>{{ $final_array["Cash In Hand"]["Chakwal"] - ($final_array["Sale"]["Chakwal"] - array_sum(array_column($new_array, 'Chakwal'))) }}</td>
    <td> </td>
   </tr>


    <tr>
        <th colspan="7">Extra Total: Rs. {{  
        $final_array["Cash In Hand"]["New City"] - ($final_array["Sale"]["New City"] - array_sum(array_column($new_array, 'New City')))
        + $final_array["Cash In Hand"]["Basti"] - ($final_array["Sale"]["Basti"] - array_sum(array_column($new_array, 'Basti')))
        + $final_array["Cash In Hand"]["Taxila"] - ($final_array["Sale"]["Taxila"] - array_sum(array_column($new_array, 'Taxila'))) 
    }}</th>
    </tr>


   {{-- when you deduct sadqah from total amount the it return grand total amount  --}}


   @php

        $sale = $final_array["Sale"]["New City"] + $final_array["Sale"]["Basti"] +$final_array["Sale"]["Taxila"] + $final_array["Sale"]["Attock"] + + $final_array["Sale"]["Pindi"];

   @endphp



   <tr><th colspan="7">Sadqah: Rs. {{  $sale > 0 ? ceil(($sale /100 * 2)/10) * 10 : 0 }} ({{ $sale > 0 ? number_format((ceil(($sale /100 * 2)/10) * 10)/$sale*100,4) :"" }})(%)</th></tr>
   <tr><th colspan="7">Grand Total: Rs. {{ $sale > 0 ?  $grand_total - (ceil(($sale /100 * 2)/10) * 10) : 0 }}</th></tr>
   <tr><th colspan="7">Cash in hand (After sadqa deduction): {{  $sale > 0 ? $inner_total - (ceil(($sale /100 * 2)/10) * 10) : 0}}</th></tr>
   
   
   
   {{-- ceil(($sale /100 * 2)/10) * 10  --}}
{{--    
   @php

   echo "<pre>";
   print_r($final_array);
    echo "<pre>";
   @endphp

    --}}
    
    
