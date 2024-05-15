
    <div>
        <div class="col-12 d-flex justify-content-center">

            {{-- <div class="col-lg-6 col-sm-12"> --}}

            <div class="col-lg-4 col-sm-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Locker Amount Form</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="add-easypaisa-form">Add HBL Amt</a> --}}
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="add-easypaisa-form">Add Easypaisa Amt</a> --}}
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="add-employee-others-forms">Employee/Others</a> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="paid_amount_form" class="data-form">

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Amount</label>
                                <input type="amount" class="form-control" id="amount" name="amount"
                                    onkeyup="validateError(this)">
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Remakrs</label>
                                {{-- <input type="input" class="form-control" id="paid_amount" name="paid_amount" onkeyup="checkAmount(this)"> --}}
                                <input type="input" class="form-control" id="remarks" name="remarks">
                            </div>



                            <div class="form-group d-flex justify-content-end">
                                <input type="submit" value="Add" class="btn btn-primary">
                            </div>
                            <input type="hidden" name="hidden_id" id="hidden_id">
                        </form>

                    </div>

                </div>
            </div>

            <div class="col-lg-8 col-sm-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Paid List</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="generate_full_report"><i
                            class="fas fa-download fa-sm text-white-50"></i> Generate Full Report</a> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">

                            <div class="mb-3 d-flex">
                                <input type="date" class="form-control mr-3" id="from_date" name="from_date"
                                    onchange="checkVal(this)">
                                <input type="date" class="form-control" id="to_date" name="to_date"
                                    onchange="checkVal(this)">
                            </div>

                            <table class="table table-bordered datatable_paid_list" id="dataTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Remarks</th>
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
    </div>

    <script>
      

        function chooseOption(e) {

            $("#employee_type")[0].style.border = "";
            if (e.value == "Employee") {
                $("#advance")[0].selected = true;
                $("#advance_payment_month")[0].disabled = false;
            } else if (e.value == "Patty") {
                $("#patty_cash")[0].selected = true;
                $("#advance_payment_month")[0].disabled = true;
            } else if (e.value == "Others") {
                $("#others")[0].selected = true;
                $("#advance_payment_month")[0].disabled = true;
            }


            getEmployees();

            var check_value = $("#purpose_type")[0].value;

            $("#purpose").val(check_value);

        }

       

        function getCurrentAmount() {

            $.ajax({
                url: "{{ url('get-current_amount') }}",
                type: "POST",
                success: function(data) {

                }
            })

        }



        //paid easy paisa amount form

        function getEmployees() {
            // $("#employee_type")[0].style.border="";
            $("#easypaisa_detail_locations")[0].style.border = "";
            var parent = $("#employee_id")[0];
            parent.innerHTML = "";
            //  $("#amount_get").val("");
            //  $("#easypaisa_amount_store").val("");

            var get_employee_value = $("#employee_type")[0].value;
            var get_branch = $("#easypaisa_detail_locations")[0].value;


            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('get-employees') }}",
                type: "POST",
                data: {
                    branch: get_branch,
                    employee_type: get_employee_value
                },
                success: function(data) {

                    console.log(data);


                    $.each(data[0], function(key, value) {
                        var create_option = document.createElement("option");
                        create_option.value = value["id"];
                        create_option.innerText = value["employee_name"] + (value["employee_post"] ?
                            "-" + value["employee_post"] : "");
                        parent.appendChild(create_option);
                    });

                    //  var amount_get = $("#amount_get").val(data[1]["remaining_amount"]);
                    //this amount is store for calculation
                    //  var amount_store = $("#easypaisa_amount_store").val(data[1]["remaining_amount"]);
                }
            })
        }



        //easy pasia employee others 

        var locker_amount = $('.datatable_paid_list').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            //  paging: false,
            //  "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('get-locker-add-amount') }}",
                data: function(d) {
                    d.from_date = $("#from_date").val()
                    d.to_date = $("#to_date").val()
                }
            },
            columns: [

                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'remarks',
                    name: 'remarks',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },

            ],

            success: function(data) {

            }
        });




        function checkVal(e) {

            if ($("#from_date")[0].value !== "" && $("#to_date")[0].value) {
                locker_amount.draw();

            }

        }




        $('#paid_amount_form').validate({
            errorPlacement: function(error, element) {
                //  element[0].style.border = "1px solid red";
            },
            rules: {
                amount: "required",
                remarks: "required",
            },

            submitHandler: function(form) {

                if (confirm('Are you sure! add amount to locker')) {
                    var formData = new FormData(form);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('insert-lock-amount') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {

                            $('#paid_amount_form')[0].reset();
                            $('#hidden_id').val("");
                            locker_amount.draw();
                        },
                        error: function(data) {


                        }

                    })
                }
            }
        });


    
        $(".toselect-tag-employee").select2();


        function validateError(e) {
            e.style.border = "";

        }

        function checkAmount() {

        }



        $(document).on("click", ".edit_locker_amount", function() {

            var id = $(this).data("id");

            $.ajax({
                headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                url:"{{ url('edit-locker-amount-new') }}",
                type:"POST",
                data:{id:id},
                success:function(data){
                    console.log(data);
                    $("#amount").val(data[0]["amount"]);
                    $("#remarks").val(data[0]["remarks"]);
                    $("#hidden_id").val(data[0]["id"]);
                }
            })

        })


        // $(document).on("click", "#generate_full_report", function() {

        //     var url = "{{ url('get-full-report-of-hbl-amount') }}";
        //     viewModal(url);

        // })





      
    </script>
