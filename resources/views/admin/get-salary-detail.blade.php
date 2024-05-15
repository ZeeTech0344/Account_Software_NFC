<style>
    table {
        width: 100%;
        border: 1px solid rgb(214, 212, 212);
        border-collapse: collapse;
    }

    td,
    th {
        border: 1px solid rgb(214, 212, 212);
        padding: 5px;
    }
</style>


@php
    
    $grand_advance_total = 0;
    $total_salary = 0;
    $total_deduction_day_of_work = 0; 

     $total_addition_in_salary = 0; 

     $total_paid_salary = 0;
    
@endphp
<div class=" p-2 d-flex justify-content-end">

    <input type="text" id="search_salary" name="search_salary" placeholder="Search Employee......."
        class="form-control w-25">

</div>
<table class="salary_grand_table">
    <tr>
        <th>Employee#</th>
        <th>Name</th>
        <th>Post</th>
        <th>Branch</th>
        <th>Basic Salary</th>
        <th>Advance</th>
        <th>Joining</th>
        <th>Paid_Salary</th>
    </tr>

    @foreach ($salary_detail as $salary)
        @php
            $total_advance = 0;
        @endphp

        <tr>
            <td>{{ $salary->employee_no }}</td>
            <td>{{ $salary->employee_name }}</td>
            <td>{{ $salary->employee_post }}</td>
            <td>{{ $salary->getEmployeeBranch->location }}</td>
            <td>{{ $salary->basic_sallary }}</td>

            @php
                foreach ($salary->easypaisa as $get_amount_easypaisa) {
                    $total_advance = $total_advance + $get_amount_easypaisa->amount;
                }
                
                foreach ($salary->hbl as $get_amount_hbl) {
                    $total_advance = $total_advance + $get_amount_hbl->amount;
                }
                
                foreach ($salary->locker as $get_amount_locker) {
                    $total_advance = $total_advance + $get_amount_locker->amount;
                }
                
               
                
            @endphp

            <td>
                {{ $total_advance }}
                @php
                     $grand_advance_total = $grand_advance_total + $total_advance;
                @endphp
            </td>
            <td>{{ date_format(date_create($salary->joining),"d-m-Y") }}</td>

            @php
              
                $total_salary = $total_salary + $salary->basic_sallary;
            @endphp
            <td>
                @foreach ($salary->salary as $get_salary_detail)
                    <ul>
                         
                        <li>
                            {{ 'Basic Salary : ' . $get_salary_detail->basic_salary }}
                        </li>
                        <li>
                            {{ 'Advance : ' . $get_salary_detail->advance }}
                        </li>
                        <li>
                            {{ 'Pending : ' . $get_salary_detail->pendings }}
                        </li>
                        <li>
                            {{ 'Deduction : ' . $get_salary_detail->day_of_work_deduction}}

                            @php

                            $total_deduction_day_of_work = $total_deduction_day_of_work + $get_salary_detail->day_of_work_deduction;

                            @endphp

                        </li>
                        <li>
                            {{ 'Addition : ' . $get_salary_detail->addition }}

                            @php

                            $total_addition_in_salary = $total_addition_in_salary + $get_salary_detail->addition;

                            @endphp

                        </li>
                        <li>
                            {{ 'DOW : ' . $get_salary_detail->day_of_work }}
                        </li>
                        <li>
                            {{ 'Remarks : ' . $get_salary_detail->remarks }}
                        </li>
                        <li>
                            {{ 'Amount : ' . $get_salary_detail->amount }}
                            @php
                                $total_paid_salary = $total_paid_salary + $get_salary_detail->amount;
                            @endphp
                        </li>
                        <li class="pt-2">
                            <a href="#" class="btn btn-sm btn-danger unpaid-salary-from-detail"
                                data-id="{{ $get_salary_detail->account_id . ',' . $get_salary_detail->account_name }}">Unpaid</a>
                        </li>
                    </ul>
                @endforeach
            </td>
            
           

        </tr>
    @endforeach

   
    <tr>
        <td colspan="8">
            <b>Total Salary (Basic Salary) : {{ number_format($total_salary) }}</b>
        </td>
    </tr>
    <tr>
        <td colspan="8">
            <b>Total Salary (Paid) : {{ number_format($total_paid_salary) }}</b>
        </td>
    </tr>
    <tr>
        <td colspan="8">
            <b>Total Salary (Unpaid) : {{ number_format($total_salary -  $total_paid_salary) }}</b>
        </td>
    </tr>
    
    

</table>

<script>
    $("#search_salary").keyup(function() {

        console.log("yes");
        var value = this.value.toLowerCase().trim();

        $(".salary_grand_table tr").each(function(index) {
            if (!index) return;
            $(this).find("td").each(function() {
                var id = $(this).text().toLowerCase().trim();
                var not_found = (id.indexOf(value) == -1);
                $(this).closest('tr').toggle(!not_found);
                return not_found;
            });
        });
    });



    $(".unpaid-salary-from-detail").click(function() {

        var data = $(this).data("id").split(",");
        var element = this;

        var alert_delete = confirm("Are you sure you! Unpaid Salary");

        if (alert_delete) {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('delete-salary-record') }}",
                type: "POST",
                data: {
                    data: data
                },
                success: function(data) {
                    $(element).parent().parent().fadeOut();
                    employee_salary_table.draw();
                }
            })

        }

    })

    // $(document).on("click", ".unpaid-salary", function() {

    // var data = $(this).data("id").split(",");
    // var element = this;

    // var alert_delete = confirm("Are you sure you! Unpaid Salary");

    // if(alert_delete){

    //     $.ajax({
    //     headers: {
    //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //                 },
    //     url:"{{ url('delete-salary-record') }}",
    //     type:"POST",
    //     data:{data:data},
    //     success:function(data){
    //         $(element).parent().parent().fadeOut();
    //         employee_salary_table.draw();
    //     }
    // })

    // }

    // })
</script>
