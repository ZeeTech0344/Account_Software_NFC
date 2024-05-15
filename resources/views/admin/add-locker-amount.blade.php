<div class="col-12 d-flex justify-content-center">

    {{-- <div class="col-lg-6 col-sm-12"> --}}

    <div class="col-lg-6 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Locker Amount Form</h6>
            </div>
            <div class="card-body">
                <form id="locker-amount-form">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Amount</label>
                        <input type="text" class="form-control" id="locker_amount" name="locker_amount">
                    </div>
                    
                    {{-- <div class="form-group">
                        <label for="exampleFormControlInput1">Branch</label>
                        <select name="employee_branch" id="employee_branch" readonly onkeyup="validate(this)" class="form-control">
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->location }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Remarks</label>
                        <input type="text" class="form-control" id="locker_remarks" name="locker_remarks">
                    </div>

                    <div class="form-group d-flex justify-content-end">
                        <input type="submit" value="Add" class="btn btn-primary">
                    </div>
                    <input type="hidden" name="locker_hidden_id" id="locker_hidden_id">
                </form>

            </div>

        </div>
    </div>

    <div class="col-lg-6 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Employee/Others</h6>
                <div>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="get_report_locker"><i
                        class="fas fa-download fa-sm text-white-50"></i>Generate Full Report</a>
                    </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered locker_amount_table" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Operator</th>
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


<script>


// function blockInput(e){

//     if(e.value !== "Employee"){
//         $("#employee_post")[0].disabled=true;
//         $("#cnic")[0].disabled=true;
//         $("#basic_sallary")[0].disabled=true;
//     }else{
//         $("#employee_post")[0].disabled=false;
//         $("#cnic")[0].disabled=false;
//         $("#basic_sallary")[0].disabled=false;
//     }

// }


var locker_amount_table = $('.locker_amount_table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            paging: false,
            "info": false,
            "language": {
                "infoFiltered": ""
            },
            ajax: {
                url: "{{ url('locker-amount-list') }}",
                data: function(d) {
                    // d.date = $("#date").val()
                }
            },
            columns: [
                {
                    data: 'amount',
                    name: 'amount'
                },
                // {
                //     data: 'deducted_amount',
                //     name: 'deducted_amount'
                // },
                {
                    data: 'operator',
                    name: 'operator'
                },
                {
                    data: 'remarks',
                    name: 'remarks'
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





$('#locker-amount-form').validate({
            errorPlacement: function(error, element) {
                    element[0].style.border = "1px solid red";
                
            },
            rules: {
                locker_amount: "required",
                // locker_remarks: "required",
            },

            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-locker-amount') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        $('#locker-amount-form')[0].reset();
                        locker_amount_table.draw();

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

        // function validate(e){
        //    e.style.border="";
        // }


    
        $(document).on("click", ".edit_locker_amount", function() {
            
            // $("#close-view")[0].click();

            var id = $(this).data("id");

            $.ajax({
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                url:"{{ url('edit-locker-amount') }}",
                type:"POST",
                data:{id:id},
                success:function(data){
                   $("#locker_amount").val(data["amount"]);
                   $("#locker_remarks").val(data["remarks"]);
                   $("#locker_hidden_id").val(data["id"]);
                }


            })

         })


         $(document).on("click", "#get_report_locker", function() {

            var url = "{{ url('get-report-locker-amount') }}";

            forListModalView(url);

         })

</script>