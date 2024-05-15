{{-- {{ $easypaisa_amount }} --}}



<div class="col-12 d-flex justify-content-center">

    {{-- <div class="col-lg-6 col-sm-12"> --}}

    <div class="col-lg-6 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Return Amount Form</h6>
            </div>
            <div class="card-body">
                <form id="easypaisa-return-form">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Name</label>
                        <input type="text" class="form-control" id="return_employee_name" name="return_employee_name"
                            value="{{ $easypaisa_amount[0]->Employees->employee_name . '-' . $easypaisa_amount[0]->employees->employee_post }}"
                            disabled>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Location</label>
                        <input type="text" class="form-control" id="return_employee_location"
                            value="{{ $easypaisa_amount[0]->branches->location }}" name="return_employee_location"
                            disabled>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Amount</label>
                        <input type="number" class="form-control" id="return_amount_calculate"
                            value="{{ $easypaisa_amount[0]->paid_amount }}" disabled>
                    </div>
                    <input type="hidden" class="form-control" id="remaining_after_return" name="remaining_after_return"
                        value="{{ $easypaisa_amount[0]->paid_amount }}">


                    <div class="form-group">
                        <label for="exampleFormControlInput1">Return Amount</label>
                        <input type="number" class="form-control" id="return_amount" name="return_amount">
                    </div>


                    <div class="form-group d-flex justify-content-end">
                        <input type="submit" value="Add" class="btn btn-primary"
                            {{ $easypaisa_amount[0]->paid_amount == 0 ? 'disabled' : '' }}>
                    </div>
                    <input type="hidden" name="return_hidden_id" id="return_hidden_id"  value="{{ $easypaisa_amount[0]->id }}">
                    <input type="hidden" name="return_employees_id" id="return_employees_id"
                        value="{{ $easypaisa_amount[0]->Employees->id }}">
                    <input type="hidden" name="return_easypaisa_account_id" id="return_easypaisa_account_id"
                        value="{{ $easypaisa_amount[0]->EasypaisaDetail->id }}">
                </form>

            </div>

        </div>
    </div>

    <div class="col-lg-6 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Closing List</h6>
                <div>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                        id="get_pdf"><i class="fas fa-download fa-sm text-white-50"></i> PDF</a>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                        id="generate_full_report"><i class="fas fa-download fa-sm text-white-50"></i> Generate Full
                        Report</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered datatable_easypaisa_amount" id="dataTable" width="100%"
                        cellspacing="0">
                        <thead>
                            <tr>
                                <th>Current</th>
                                {{-- <th>Deducted</th> --}}
                                {{-- <th>Add</th> --}}
                                <th>Remaining</th>
                                <th>location</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- </div> --}}
</div>


<script>
    $("#return_amount").keyup(function() {

        var actual_amount_store = $("#remaining_after_return")[0].value;

        var return_amount = $("#return_amount")[0].value;

        $("#return_amount_calculate").val(actual_amount_store - return_amount);

        if ($("#return_amount_calculate")[0].value < 0) {
            $("#return_amount_calculate")[0].style.border = "1px solid red";
        } else {
            $("#return_amount_calculate")[0].style.border = "";
        }
    });




    $(document).on("submit", "#easypaisa-return-form", function(e) {

        e.preventDefault();

        if ($("#return_amount_calculate")[0].value < 0) {
            alert("Please enter valid amount");
            return false;
        }

        var return_amount_value = $("#return_amount")[0].value;

        var confirm_return_amount = confirm("Are you sure! Return amount to easypaisa of Rs." +
            return_amount_value);

        if (confirm_return_amount) {
        var return_amount_after_deduction = $("#return_amount")[0].value;
        var previous_amount = $("#remaining_after_return")[0].value;
        var return_employee_id = $("#return_employees_id")[0].value;
        var return_easypaisa_amount_id = $("#return_easypaisa_account_id")[0].value;
        var easypaisa_detail_id = $("#return_hidden_id")[0].value;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('insert-return-amount-easypaisa') }}",
                type: "POST",
                data: {
                    return_amount: return_amount_after_deduction,
                    previous_amount: previous_amount,
                    employee_id: return_employee_id,
                    easypaisa_id: return_easypaisa_amount_id,
                    easypaisa_detail_id:easypaisa_detail_id
                },
                success: function(data) {
                    $("#easypaisa-return-form")[0].reset();
                }

            })

        }

       




    })










    var table = $('.datatable_easypaisa_amount').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        // paging: false,
        // "info": false,
        "language": {
            "infoFiltered": ""
        },

        ajax: {
            url: "{{ url('easypaisa-amount-list') }}",
            data: function(d) {
                // d.date = $("#date").val()
            }
        },
        columns: [{
                data: 'current_amount',
                name: 'current_amount'
            },
            // {
            //     data: 'deducted_amount',
            //     name: 'deducted_amount'
            // },
            {
                data: 'add_amount',
                name: 'add_amount'
            },
            {
                data: 'remaining_amount',
                name: 'remaining_amount'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ],

        success: function(data) {
            console.log(data);
        }
    });





    $('#employee-form').validate({
        errorPlacement: function(error, element) {
            element[0].style.border = "1px solid red";

        },
        rules: {
            employee_name: "required",
            // employee_post: "required",
            employee_type: "required",
            // basic_sallary: "required",
            // employee_type: "required",
            employee_branch: "required",
            employee_status: "required",
            // cnic: "required",
        },

        submitHandler: function(form) {
            var formData = new FormData(form);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('insert-employee-others') }}",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    $('#employee-form')[0].reset();
                    // table.draw();

                    // $("#amount").val("");

                    // console.log(data);
                    // if (!$(".alert-danger").hasClass("d-none")) {
                    //     $(".alert-danger")[0].classList.add("d-none");
                    // }
                    // if(data=="saved"){
                    //     form.reset();
                    //     $(".file-upload-content")[0].style.display="none";
                    //     $(".image-upload-wrap")[0].style.display="";
                    // }
                    // $(".alert-success")[0].classList.remove("d-none");



                },
                error: function(data) {
                    console.log(data);
                    // if (data.responseJSON.error.length >= 1) {

                    // $(".alert-success")[0].classList.add("d-none");
                    // $(".alert-danger")[0].innerText = "Invalid fields";
                    // $(".alert-danger")[0].classList.remove("d-none");

                    //this code is for select2 fields for backend validation error
                    // for (var a = 0; a < $(".select2").length; a++) {
                    //     if ($(".select2")[a].previousSibling.value == "") {
                    //         $(".select2-selection")[a].style.border = "1px solid red";
                    //     }
                    // }

                    //this code is for without select2 fields for backend validation error
                    // var count_errors = data.responseJSON.error.length;
                    // for (var a = 0; a < count_errors; a++) {
                    //     var error_text = data.responseJSON.error[a];
                    //     var find_last_word = error_text.indexOf("field");
                    //     var name = error_text.substr(4, find_last_word - 5);
                    //     var create_name = "." + name.replace(" ", "_");
                    //     var check = $(create_name);
                    //     check[0].style.cssText = "border:1px solid red";
                    // }


                    // }

                }

            })
        }
    });

    function validate(e) {
        e.style.border = "";

    }

    // function returnAmount(){

    // e.style.border="";
    // var actual_amount_store = $("#remaining_after_return")[0].value;

    // var return = $("#return_amount")[0].value;

    // $("#return_amount_calculate")[0].val(actual_amount_store-return);


    // // if($("#amount_get")[0].value<=0){
    // //     $("#amount_get")[0].style.border="1px solid red";
    // // }else{
    // //     $("#amount_get")[0].style.border="";
    // // }

    // }
</script>
