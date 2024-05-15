<style>
    table{
        border:1px solid rgb(175, 174, 174);
        width:100%;
    }
    td,tr{
        border:1px solid rgb(175, 174, 174);
        text-align: center;
    }
</style>


@php
    $sr=1;
    $grand_total = 0;
@endphp
<table>
   <thead>
    <th>ID</th>
    <th>Date</th>
    <th>Installment</th>
   </thead>
   <tbody>
    <!-- <tr>
        <td>#</td><td>27-07-2023</td><td>850000</td>
    </tr> -->
    @foreach ($installment as $get_data)
    <tr>
    <td>{{ $sr++ }}</td>
    <td>{{  date_format(date_create($get_data->created_at),"d-m-Y") }}</td>
    <td>{{ $get_data->amount }}</td>
    </tr>      
    
    @php
        $grand_total = $grand_total + $get_data->amount;
    @endphp
    @endforeach
    <tr>
        <td colspan="3" style="text-align: left; color:#4e73df">
            <!-- <b>Grand Total (Installment) : {{  $grand_total + 850000 }}</b> -->
            <b style="color:#4e73df"> Grand Total (Installment) : {{ $grand_total }}
        </td>
      
    </tr>
    <tr>
        <td colspan="3" style="text-align: left;">
            <b style="color:#4e73df">Installment (Pay To)  : {{  $pay_installment }}</b>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: left;">
            <b style="color:#4e73df">Remaining Amount : {{ $calculate_old_install + ($grand_total + 850000 ) - $pay_installment }}</b>
        </td>
    </tr>
    
   </tbody>
</table>