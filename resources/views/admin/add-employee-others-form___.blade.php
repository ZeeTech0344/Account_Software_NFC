

<div class="col-12 d-flex justify-content-center">

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
                        <input type="text" class="form-control" id="employee_name" name="employee_name" onkeyup="validate(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Type</label>
                        <select name="employee_type" id="employee_type" class="form-control" onkeyup="validate(this)" onchange="blockInput(this)">
                            <option value="">Select Type</option>
                            <option>Employee</option>
                            <option>Patty Cash</option>
                            <option>Vendors</option>
                            <option>Others</option>
                           
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Post</label>
                        <select name="employee_post" id="employee_post" class="form-control">
                            <option value="">Select Post</option>
                            <option>Manager</option>
                            <option>Accountant</option>
                            <option>Chef</option>
                            <option>Waiter</option>
                            <option>Rider</option>
                            </select>
                    </div>

                   

                    <div class="form-group">
                        <label for="exampleFormControlInput1">CNIC <small>(if employee)</small></label>
                        <input type="text" class="form-control" id="cnic" class="form-control" name="cnic" onkeyup="validate(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Basic Sallary</label>
                        <input type="text" class="form-control" id="basic_sallary" class="form-control" name="basic_sallary" onkeyup="validate(this)">
                    </div>

                    

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Branch</label>
                        <select name="employee_branch" id="employee_branch"  onkeyup="validate(this)" class="form-control">
                            <option value="">Select Branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->location }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Status</label>
                        <select name="employee_status" id="employee_status" onkeyup="validate(this)" class="form-control">
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
                    </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered list_employee_others" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Post</th>
                                <th>Type</th>
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


function blockInput(e){

    if(e.value !== "Employee"){
        $("#employee_post")[0].disabled=true;
        $("#cnic")[0].disabled=true;
        $("#basic_sallary")[0].disabled=true;
    }else{
        $("#employee_post")[0].disabled=false;
        $("#cnic")[0].disabled=false;
        $("#basic_sallary")[0].disabled=false;
    }

}


var table = $('.list_employee_others').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            paging: false,
            "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('list-employee-others') }}",
                data: function(d) {
                    // d.date = $("#date").val()
                }
            },
            columns: [
                {
                    data: 'name',
                    name: 'name'
                },
                // {
                //     data: 'deducted_amount',
                //     name: 'deducted_amount'
                // },
                {
                    data: 'post',
                    name: 'post'
                },
                {
                    data: 'type',
                    name: 'type'
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
                // employee_branch: "required",
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
                        table.draw();

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

        function validate(e){
           e.style.border="";
        }


    
        // $(document).on("click", "#generate_employee_other_report", function() {

        // var url = "{{ url('choose-option-employee-other') }}";
        // payNowModalBody(url);

        // $("#close-view").click();

        //  })

        

</script>

