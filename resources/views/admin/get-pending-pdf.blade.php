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
    
    $amount_pending = 0;
    $amount_paid = 0;

@endphp


<table class="table table-bordered datatable_pending_full_list" id="dataTable" width="100%" cellspacing="0">
    <caption>Pending Report</caption>
    <thead>
        <tr>
            <th>Date</th>
            <th>Employee</th>
            <th>Branch</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Paid_Date</th>
            <th>Acc_Name</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $collected_data)
                <tr>
                    <td>{{ date_format(date_create($collected_data->created_at),"d-m-Y") }}</td>
                    <td>{{ $collected_data->getEmployee->employee_name }}</td>
                    <td>{{ $collected_data->getBranch->location }}</td>
                    <td>{{ $collected_data->amount }}</td>
                    <td>{{ $collected_data->status }}</td>
                    <td>{{ $collected_data->paid_date }}</td>
                    <td>{{ $collected_data->account_name }}</td>
                </tr>

                @php
                   if($collected_data->status == "Pending"){
                    $amount_pending = $amount_pending + $collected_data->amount;
                   }elseif($collected_data->status == "Paid"){
                    $amount_paid =  $amount_paid + $collected_data->amount;
                   }
                @endphp
        @endforeach
        <tr>
            <td colspan="7">
                Pending Amount: Rs. {{ $amount_pending }}
            </td>
        </tr>
        <tr>
            <td colspan="7">
                Paid Amount: Rs. {{ $amount_paid }}
            </td>
        </tr>

        {{-- <tr>
            <td colspan="7">
                Total Pending Amount Was: Rs. {{ $pending_sum[0]->sum }}
            </td>
        </tr> --}}


    </tbody>
</table>