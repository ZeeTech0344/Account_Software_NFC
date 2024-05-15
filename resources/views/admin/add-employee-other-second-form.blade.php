
@extends('layout.structure')

@section('content')

    <div class="col-12 d-flex justify-content-center" id="main_div_for_controll">

        {{-- <div class="col-lg-6 col-sm-12"> --}}

        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Employee/Others Form</h6>
                </div>
                <div class="card-body">
                    <form id="employee-form">
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Name</label>
                            <input type="text" class="form-control" id="employee_name" name="employee_name"
                                onkeyup="validate(this)">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Type</label>
                            <select name="employee_type" id="employee_type" class="form-control" onkeyup="validate(this)"
                                onchange="blockInput(this)">
                                <option value="">Select Type</option>
                                <option>Employee</option>
                                <!-- <option>Patty</option> -->
                                <option>Vendors</option>
                                <option>Others</option>
                                <option>Fuel</option>
                                <!-- <option>Fuel Head</option> -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Post</label>
                            <select name="employee_post" id="employee_post" class="form-control">
                                <option value="">Select Post</option>
                                <option>Manager</option>
                                <option>Accountant</option>
                                <option>Software Engineer</option>
                                <option>Chef</option>
                                <option>Kitchen Helper</option>
                                <option>Kitchen Dishwasher</option>
                                <option>Store Keeper</option>
                                <option>Store Helper</option>
                                <option>Cook</option>
                                <option>Cashier</option>
                                <option>Waiter</option>
                                <option>Supervisor</option>
                                <option>Rider</option>
                                <option>Electrician</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Basic Salary</small></label>
                            <input type="number" class="form-control" id="basic_sallary"  name="basic_sallary"
                                onkeyup="validate(this)">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">CNIC <small>(if employee)</small></label>
                            <input type="text" class="form-control" id="cnic"  name="cnic"
                                onkeyup="validate(this)">
                        </div>


                        <div class="form-group">
                            <label for="exampleFormControlInput1">PhoneNo<small>(if employee)</small></label>
                            <input type="text" class="form-control" id="phone_no" name="phone_no"
                                onkeyup="validate(this)">
                        </div>


                        <div class="form-group">
                            <label for="exampleFormControlInput1">Father CNIC</label>
                            <input type="text" class="form-control" id="father_cnic"
                                name="father_cnic" onkeyup="validate(this)">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Father Phone#</label>
                            <input type="text" class="form-control" id="father_phone_no"
                                name="father_phone_no" onkeyup="validate(this)">
                        </div>


                        <div class="form-group">
                            <label for="exampleFormControlInput1">Branch</label>
                            <select name="employee_branch" id="employee_branch" onkeyup="validate(this)"
                                class="form-control">
                                <option value="">Select Branch</option>
                                @foreach ($branches as $branch)
                                @if(Auth::User()->user_branch == $branch->location && Auth::User()->user_branch !== "Head Office" )
                                    <option value="{{ $branch->id }}">{{ $branch->location }}</option>
                                @elseif( Auth::User()->user_branch == "Head Office"  )
                                    <option value="{{ $branch->id }}">{{ $branch->location }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                       

                       

                        <div class="form-group">
                            <label for="exampleFormControlInput1">D.O.J</label>
                            <input type="date" class="form-control" id="joining"
                                name="joining" onkeyup="validate(this)">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">D.O.L</label>
                            <input type="date" class="form-control" id="leaving"
                                name="leaving" onkeyup="validate(this)">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Status</label>
                            <select name="employee_status" id="employee_status" onkeyup="validate(this)"
                                class="form-control">
                                <option>On</option>
                                <option>Off</option>
                            </select>
                        </div>

                     

                        <div class="form-group d-flex justify-content-end">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="employee_hidden_id" id="employee_hidden_id">
                    </form>

                </div>

            </div>
        </div>

        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Employee/Others</h6>
                    <div>
                        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="generate_employee_other_report"><i
                        class="fas fa-download fa-sm text-white-50"></i>Generate Full Report</a> --}}

                        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                            id="employee_other_reports"><i class="fas fa-download fa-sm text-white-50"></i>Generate Full
                            Report</a> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="search_value" name="search_value" placeholder="Type here to search........">
                        </div>
                        <table class="table table-bordered employee_front_table" id="dataTable" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Branch</th>
                                    <th>CNIC</th>
                                    <th>Basic Salary</th>
                                    <th>Status</th>
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
@endsection



@section('script')
    <script>


        
// $( document ).on( 'keydown', function ( e ) {

// if ( e.keyCode === 17 ) {
//     $("#employee_name").focus();
// }
// });



// $( document ).on( 'keydown', function ( e ) {

// if ( e.keyCode === 16 ) {
// $("#search_value").focus();
// }
// });


        function blockInput(e) {

            if (e.value !== "Employee") {
                $("#employee_post")[0].disabled = true;
                $("#cnic")[0].disabled = true;
                $("#basic_sallary")[0].disabled = true;
                $("#phone_no")[0].disabled = true;
                $("#father_cnic")[0].disabled = true;
                $("#father_phone_no")[0].disabled = true;
                $("#joining")[0].disabled = true;
                $("#leaving")[0].disabled = true;
            } else {
                $("#employee_post")[0].disabled = false;
                $("#cnic")[0].disabled = false;
                $("#basic_sallary")[0].disabled = false;
                $("#phone_no")[0].disabled = false;
                $("#father_cnic")[0].disabled = false;
                $("#father_phone_no")[0].disabled = false;
                $("#joining")[0].disabled = false;
                $("#leaving")[0].disabled = false;
            }

        }



        var employee_front_table = $('.employee_front_table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            // paging: false,
            // "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('get-data-of-employee') }}",
                data: function(d) {
                    d.search_value = $("#search_value").val()
                }
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'branch',
                    name: 'branch'
                },
                {
                    data: 'cnic',
                    name: 'cnic'
                },
                {
                    data: 'basic_salary',
                    name: 'basic_salary'
                },
                {
                    data: 'status',
                    name: 'status'
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



        $("#search_value").on('keyup', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                employee_front_table.draw();
            }
        });

        

      



        $('#employee-form').validate({
            errorPlacement: function(error, element) {
                // element[0].style.border = "1px solid red";

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
                        employee_front_table.draw();
                        $("#employee_hidden_id").val("");
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



        // $(document).on("click", "#generate_employee_other_report", function() {

        // var url = "{{ url('choose-option-employee-other') }}";
        // payNowModalBody(url);

        // $("#close-view").click();

        //  })



        $(document).on("click", "#employee_other_reports", function() {

            var url = "{{ url('employee-report') }}";
            viewModal(url);

        })








        $(document).on("click", ".edit_employee_others", function() {

            var id = $(this).data("id");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-employee-others') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {

                    $("#employee_name").val(data["employee_name"]);
                    $("#employee_type").val(data["employee_type"]);
                    $("#employee_post").val(data["employee_post"]);
                    $("#cnic").val(data["cnic"]);
                    $("#phone_no").val(data["phone_no"]);
                    $("#father_phone_no").val(data["father_phone_no"]);
                    $("#father_cnic").val(data["father_cnic"]);
                    $("#basic_sallary").val(data["basic_sallary"]);
                    $("#employee_branch").val(data["employee_branch"]);
                    $("#employee_status").val(data["employee_status"]);
                    $("#joining").val(data["joining"]);
                    $("#leaving").val(data["leaving"]);
                    $("#employee_hidden_id").val(data["id"]);

                    if(data["employee_type"] == "Employee"){
                        $("#employee_post")[0].disabled = false;
                        $("#cnic")[0].disabled = false;
                        $("#basic_sallary")[0].disabled = false;
                        $("#phone_no")[0].disabled = false;
                        $("#father_cnic")[0].disabled = false;
                        $("#father_phone_no")[0].disabled = false;
                    }else{
                        $("#employee_post")[0].disabled = true;
                        $("#cnic")[0].disabled = true;
                        $("#basic_sallary")[0].disabled = true;
                        $("#phone_no")[0].disabled = true;
                        $("#father_cnic")[0].disabled = true;
                        $("#father_phone_no")[0].disabled = true;
                    }
                    

                    // $("#close-view")[0].click();

                    // $("#date").val(data[0]["date"]);
                    // getHeads(data[0]["head"]);
                    // getHeadLocation(data[0]["location"]);
                    // $("#amount").val(data[0]["amount"]);
                    // $("#hidden_id").val(data[0]["id"]);
                }
            })

        })
    </script>

    @endsection

