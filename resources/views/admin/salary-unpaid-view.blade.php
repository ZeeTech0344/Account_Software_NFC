<style>
    table{
        border:1px solid #e3e6f0;
        border-collapse: collapse;
        width: 100%;
    }
    td, th{
        border:1px solid #e3e6f0;
        padding: 3px;
        text-align: left;
      
    }
</style>

@php
    $sr = 1;
    $total_salaries = 0;
    $total_advance_deducted = 0;
    $total_day_of_work_deducted = 0;
    $basic_salaries = 0;

@endphp
<div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_employee" name="search" placeholder="Search Employee......." onchange="checkValues(this)" class="form-control w-25" >

</div>

<table id="paid_salary_table">
  
    <thead>
        <th  style="text-align: center">Sr No</th>
        <th>Paid_Date</th>
        <th>Name</th>
        <th>Post</th>
        <th>Branch</th>
        <th>Basic_Salary</th>
        <th>Advance</th>
        <th>Pending</th>
        <th>Total Salary</th>
    </thead>
    <tbody>
    
        @php
            $grand_advance = 0;
            $grand_pending = 0; 
            $grand_salary = 0 ;
        @endphp
        @foreach ($salary_detail as $salary)
                @php
                    $total_advance_of_employee = 0;

                    $total_pending_amount = 0;

                @endphp        

                <tr>
                    <td style="text-align: center">{{ $sr++ }}</td>
                    <td>{{ date_format(date_create($salary->created_at),"d-m-Y") }}</td>
                    <td>{{ $salary->employee_name   }}</td>
                    <td>{{ $salary->employee_post  }}</td>
                    <td>{{ $salary->getEmployeeBranch->location }}</td>
                    <td>{{ $salary->basic_sallary }}</td>
                    <td>
                            @php
                                foreach ($salary->easypaisa as $easypaisa)  {
                                    $total_advance_of_employee = $total_advance_of_employee + (isset($easypaisa->amount) ? $easypaisa->amount : 0) ;
                                }

                                foreach ($salary->locker as $locker)   {
                                    $total_advance_of_employee = $total_advance_of_employee +  (isset($locker->amount) ? $locker->amount : 0) ;
                                } 

                                foreach ($salary->hbl as $hbl)   {
                                    $total_advance_of_employee = $total_advance_of_employee + (isset($hbl->amount) ? $locker->amount : 0) ;
                                } 
                            @endphp
                            {{ $total_advance_of_employee }}
                            
                            {{-- calculate grand advance --}}
                            @php
                                   $grand_advance = $grand_advance +  $total_advance_of_employee;
                            @endphp

                    </td>

                    <td>
                        @php
                        foreach ($salary->pendings as $pending)  {
                            $total_pending_amount = $total_pending_amount + (isset($pending->amount) ? $pending->amount : 0) ;
                        }
                    @endphp
                    {{  ($total_pending_amount - $total_pending_amount/100 * 10)  }}

                      {{-- calculate grand Pending --}}
                      @php
                       $grand_pending =  $grand_pending +  ($total_pending_amount - $total_pending_amount/100 * 10);
                      @endphp


                    </td>
                    <td>
                            {{ $salary->basic_sallary - ($total_advance_of_employee + ($total_pending_amount - $total_pending_amount/100 * 10) ) }}
                    </td>

                    @php
                         $grand_salary  =  $grand_salary  + ( $salary->basic_sallary - (($total_advance_of_employee + ($total_pending_amount - $total_pending_amount/100 * 10) )) );
                    @endphp
                  
                   
                </tr>

        @endforeach
        <tr>
            <td colspan="9" style="color:#4e73df; font-weight:bolder">
                Total Pending: {{ number_format($grand_pending) }}
            </td>
        </tr>
        <tr>
            <td colspan="9" style="color:#4e73df; font-weight:bolder">
                Total Advance: {{ number_format($grand_advance) }}
            </td>
        </tr>
        <tr>
            <td colspan="9" style="color:#4e73df;  font-weight:bolder">
                Total Salary (Remaining): {{ number_format($grand_salary) }}
            </td>
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




$(".unpaid-salary").click(function(){

    var data = $(this).data("id").split(",");
    var element = this;

    var alert_delete = confirm("Are you sure you! Unpaid Salary");

    if(alert_delete){
        
        $.ajax({
        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
        url:"{{ url('delete-salary-record') }}",
        type:"POST",
        data:{data:data},
        success:function(data){
            $(element).parent().parent().fadeOut();
            employee_salary_table.draw();
        }
    })

}
            
})













</script>