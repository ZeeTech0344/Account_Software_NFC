<div class="col-12 d-flex justify-content-center">

    {{-- <div class="col-lg-6 col-sm-12"> --}}

    <div class="col-lg-6 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Payment Form</h6>
            </div>
            <div class="card-body">
                <form id="easypaisa-form">

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Date</label>
                        <input type="date" class="form-control" name="date" id="date"
                            value="<?php echo date('Y-m-d'); ?>">
                    </div>


                    <div class="form-group">
                        <label for="exampleFormControlInput1">Branch</label>
                        <select name="branch" id="branch" class="form-control">
                        <option value="">Select Branch</option>
                          @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->location }}</option>
                          @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Account Type</label>
                        <select name="account" id="account" class="form-control">
                            <option value="">Select Accont</option>
                            <option>HBL</option>
                            <option>Easypaisa</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Amount</label>
                        <input type="input" class="form-control" id="current_amount" name="amount" onkeyup="validate(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Remarks</label>
                        <input type="input" class="form-control" id="remarks" name="remarks" onkeyup="validate(this)">
                    </div>

                    <div class="form-group d-flex justify-content-end">
                        <input type="submit" value="Add" class="btn btn-primary">
                    </div>
                    <input type="hidden" name="hidden_id" id="hidden_id">
                </form>

            </div>

        </div>
    </div>

    <div class="col-lg-6 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Payment Form</h6>
                <div>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="get_pdf"><i
                    class="fas fa-download fa-sm text-white-50"></i> PDF</a>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="generate_full_report"><i
                        class="fas fa-download fa-sm text-white-50"></i> Generate Full Report</a>
                    </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered datatable_easypaisa_amount" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Current</th>
                                {{-- <th>Deducted</th> --}}
                                {{-- <th>Add</th> --}}
                                <th>Remaining</th>
                                <th>Branch</th>
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
            columns: [
                {
                    data: 'current_amount',
                    name: 'current_amount'
                },
                // {
                //     data: 'deducted_amount',
                //     name: 'deducted_amount'
                // },
                // {
                //     data: 'add_amount',
                //     name: 'add_amount'
                // },
                
                {
                    data: 'remaining_amount',
                    name: 'remaining_amount'
                },
                {
                    data: 'branch',
                    name: 'branch'
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





$('#easypaisa-form').validate({
            errorPlacement: function(error, element) {
                    console.log(element)
                
            },
            rules: {
                date: "required",
                branch: "required",
                account: "required",
                amount: "required",
                remarks: "required",
            },

            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-easypaisa-amount') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        $('#easypaisa-form')[0].reset();
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


    


        

</script>