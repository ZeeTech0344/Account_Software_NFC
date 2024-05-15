<style>
   td, th, caption{
    padding:3px;
    text-align: center;
    border: 1px solid rgb(184, 184, 184);
   }
   table{
    border-collapse: collapse;
    width: 100%;
   }
</style>

<table>
    <thead>
        <tr>
            <th>Sr#</th>
            <th>Date</th>
            <th>Sadqa</th>
            <th>Percent(%)</th>
        </tr>
    </thead>
    @php
        $sr = 1;
        $total = 0;
    @endphp
    <tbody>
       @foreach ($demand as $get_data)
            <tr>
                <td>{{ $sr++ }}</td>
                <td>{{ date_format(date_create($get_data->date),"d-m-Y") }}</td>
                <td>{{ ceil(( $get_data->sum /100 * 2)/10) * 10 }}</td>
                <td> ({{number_format((ceil(($get_data->sum /100 * 2)/10) * 10) / $get_data->sum*100,4)}})(%)</td>
            </tr>
            @php
                $total = $total + (ceil(( $get_data->sum /100 * 2)/10) * 10);
            @endphp
       @endforeach
       <tr>
        <th colspan="4" style="text-align: left;">
            Total Amount Sadqa: Rs.{{ $total + 161670 }}  (Add remaining_amount of Rs. 161670 (27-07-2023) for balancing of sadqa excel sheet)
        </th>
    </tr>
    </tbody>
</table>