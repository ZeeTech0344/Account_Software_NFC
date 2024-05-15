<style>
    td, th, caption{
     padding:3px;
     text-align: center;
     border: 1px solid rgb(206, 202, 202);
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
             <th>Pay Amount</th>
             <th>Purpose</th>
         </tr>
     </thead>
     @php
         $sr = 1;
         $total = 0;
     @endphp
     <tbody>
        @foreach ($pay_installment as $get_data)
                <tr>
                    <td>{{ $sr++ }}</td>
                    <td>{{ date_format(date_create($get_data->created_at),"d-m-Y")  }}</td>
                    <td>{{ $get_data->pay_installment }}</td>
                    <td>{{ $get_data->purpose  }}</td>
                    @php
                        $total = $total + $get_data->pay_installment;
                    @endphp
                </tr>
                
        @endforeach
       <tr>
        <td style="text-align: left;" colspan="4">Grand Total Installment: {{  ($installment[0]->sum + 850000)}}</td>
       </tr>
       <tr>
        <td   style="text-align: left;" colspan="4">Total Pay: {{    $total  }}</td>
       </tr>
       <tr>
        <td style="text-align: left;" colspan="4">Remaining: {{  (($installment[0]->sum  + 850000 ) - $total) - $pay_installment_old }} </td>
       </tr>
     </tbody>
 </table>