

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
    
    $total = 0;

@endphp

<div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_employee" name="search" placeholder="Search Employee......."  class="form-control w-25" >

</div>
<table id="locker_view_get_second">
    <thead>
        <tr>
            <th>Date</th>
            <th>Head</th>
            <th>Purpose</th>
            <th>Amount</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $get_data)

        <tr>
            <td>
                {{ date_format(date_create($get_data->created_at),"d-m-Y") }}
            </td>
            <td>
                {{ $get_data->getEmployee->employee_name." ".$get_data->getEmployee->employee_post }}
            </td>
            <td>
                {{ $get_data->purpose }}
            </td>
            <td>
                {{ $get_data->amount }}
            </td>
            <td>
                {{ $get_data->remarks }}
            </td>
        </tr>

        @php
            
            $total = $total + $get_data->amount;

        @endphp

        @endforeach
<tr>
    <td colspan="5">
       <b> Grand Total: {{  $total }} </b>
    </td>
</tr>
        
    
    </tbody>

    

</table>
<script>



    $("#search_employee").keyup(function () {
    
    var value = this.value.toLowerCase().trim();
    
    $("#locker_view_get_second tr").each(function (index) {
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