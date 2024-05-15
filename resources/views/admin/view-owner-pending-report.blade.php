<style>
    th{
        text-align: left;
        border:1px solid #d0cdcd;
        padding:3px;
        text-align: center;
    }
    td{
        border:1px solid #d0cdcd;
        padding:3px;
        text-align: center;
    }
    table{
        border-collapse: collapse;
        width: 100%;
    }
    caption{
        padding:5px;
        border:1px solid #d0cdcd;
    }

</style>



@php
    
    $total = 0;
@endphp


<table>
    <thead>
        <tr>
            <th>
               Date
            </th>
            <th>
                Amount
            </th>
            <th>
                Remarks
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pendings as $get_data)
            <tr>
                <td>
                    {{ date_format(date_create($get_data->date),"d-m-Y")  }}
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
            <td colspan="3" style="text-align: left;">Total Amount :  {{  $total }}</td>
        </tr>
    </tbody>
</table>