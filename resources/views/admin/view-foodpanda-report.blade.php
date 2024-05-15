
<style>
    table{
        border:1px solid #e3e6f0;
    }
    th,td{
        border:1px solid #e3e6f0;
        padding:3px;
    }
    
</style>

@php
    $sr = 1;
    $total_amount = 0;
@endphp

<div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_employee" name="search" placeholder="Search......."  class="form-control w-25" >

</div>
<table width="100%" id="paid_salary_table"
cellspacing="0">
<thead>
    <tr>
        <th>Sr#</th>
        <th>Foodpanda Date</th>
        <th>HBL Date</th>
        <th>Account</th>
        <th>Amount</th>
        <th>Remarks</th>
    </tr>
</thead>
<tbody>

    @foreach ($foodpanda as $get_data)
            <tr>
                <td>{{ $sr++ }}</td>
                <td>{{ date_format(date_create($get_data->created_at),"d-m-Y") }}</td>
                <td>{{ date_format(date_create($get_data->date),"d-m-Y")  }}</td>
                <td>{{ $get_data->account }}</td>
                <td>{{ $get_data->amount }}</td>
                <td>{{ $get_data->remarks }}</td>
            </tr>
            @php
                $total_amount = $total_amount + $get_data->amount;
            @endphp
    @endforeach


<tr>
    <td colspan="6">Grand Total Amount: {{  $foodpanda_grand_total }}</td>
</tr>
<tr>
    {{-- between dates --}}
    <td colspan="6">Total Amount: {{  $foodpanda_closing }}</td>
</tr>
<tr>
    <td colspan="6">Transfer Amount: {{  $total_amount }}</td>
</tr>

</tbody>
</table>


<script>


$("#search_employee").keyup(function () {

    var value = this.value.toLowerCase().trim();
    
    $("#paid_salary_table tr").each(function (index) {
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

    
