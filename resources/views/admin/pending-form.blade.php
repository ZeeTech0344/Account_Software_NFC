
    <div>
        <div class="col-12 d-flex justify-content-center">

            {{-- <div class="col-lg-6 col-sm-12"> --}}

            <div class="col-lg-4 col-sm-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Pending</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="add-employee-others-forms">Employee/Others</a> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="pending-form" class="data-form">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Date</label>
                                <input type="date" class="form-control" name="date" id="date"
                                    onclick="validate(this)">
                            </div>

                            {{-- <div class="form-group">
                                <label for="exampleFormControlSelect1">Branch</label>
                                <select class="form-control" id="location" style="width:100%;" name="location"
                                    onclick="validate(this)" onchange="selectLocation()">
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                    @if(Auth::User()->user_branch == $branch->location && Auth::User()->user_branch !== "Head Office" )
                                        <option value="{{ $branch->id }}">{{ $branch->location }}</option>
                                    @elseif( Auth::User()->user_branch == "Head Office"  )
                                        <option value="{{ $branch->id }}">{{ $branch->location }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div> --}}

                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Employees</label>
                                <select class="form-control toselect-tag" id="employee_id" 
                                name="employee_id">
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Amount</label>
                                <input type="input" class="form-control" id="amount" name="amount"
                                    onkeyup="checkAmt(this)">
                            </div>
                            <div class="form-group" id="convert_to_number">
                             
                            </div>

                            
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Remarks</label>
                                <input type="input" class="form-control" id="remarks" name="remarks"
                                    onclick="validate(this)">
                            </div>

                            {{-- <div class="form-group">
                            <label for="exampleFormControlSelect1">Account</label>
                            <select class="form-control toselect-tag" id="locations" style="width:100%;" name="location">
                                <option value="">Select Account</option>
                                <option>Easypaisa</option>
                                <option>HBL</option>
                                <option>Locker</option>
                            </select>
                        </div> --}}

                            <div class="form-group d-flex justify-content-end">
                                <input type="submit" value="Save" class="btn btn-primary">
                            </div>
                            <input type="hidden" name="hidden_id" id="hidden_id">
                        </form>

                    </div>

                </div>
            </div>

            <div class="col-lg-8 col-sm-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Pending List</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="get_pdf"><i
                        class="fas fa-download fa-sm text-white-50"></i> PDF</a> --}}
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="generate_pending_report"><i class="fas fa-download fa-sm text-white-50"></i> Generate
                                Full Report</a> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">

                            <div class="mb-3 d-flex">
                                <input type="date" class="form-control mr-3" id="from_date_pending" name="from_date_pending" onchange="checkVal(this)">
                                <input type="date" class="form-control" id="to_date_pending" name="to_date_pending" onchange="checkVal(this)">
                            </div>


                            <div class="mb-3">
                                <input type="text" class="form-control" id="search_value" name="search_value" placeholder="Type here to search........">
                            </div>
                            <table class="table table-bordered datatable_pending" id="dataTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Employee</th>
                                        <th>Branch</th>
                                        <th>Amount</th>
                                        <th>Status</th>
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




// $( document ).on( 'keydown', function ( e ) {

// console.log(e.keyCode);
// if ( e.keyCode === 17 ) {
//     $("#date").focus();
// }
// });



// $( document ).on( 'keydown', function ( e ) {

// console.log(e.keyCode);
// if ( e.keyCode === 16 ) {
// $("#from_date_pending").focus();
// }
// });




        // function selectLocation() {
        //     getEmployees();
        // }


        function getEmployees(employee_id) {

var parent = $("#employee_id")[0];
parent.innerHTML = "";

// var get_branch = $("#location")[0].value;

$.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: "{{ url('get-riders') }}",
    type: "POST",
    // data: {
    //     branch: get_branch
    // },
    success: function(data) {

        console.log(data);

        $.each(data[0], function(key, value) {
            var create_option = document.createElement("option");
            create_option.value = value["id"]+","+value["employee_branch"];
            create_option.innerText = value["employee_name"]+"-"+value["employee_post"] + "( " + value["get_employee_branch"]["location"] + " )";
            if(value["id"] == employee_id){
                create_option.selected=true;
            }
            parent.appendChild(create_option);
        });

        //  var amount_get = $("#amount_get").val(data[1]["remaining_amount"]);
        //this amount is store for calculation
        //  var amount_store = $("#easypaisa_amount_store").val(data[1]["remaining_amount"]);
    }
})
}

getEmployees();



        var pending_table = $('.datatable_pending').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            // paging: false,
            // "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('get-list-of-pending') }}",
                data: function(d) {
                    d.search_value = $("#search_value").val();
                    d.from_date_pending = $("#from_date_pending").val();
                    d.to_date_pending = $("#to_date_pending").val();
                }
            },
            columns: [

            {
                data: 'date',
                name: 'date'
            },
            {
                data: 'employee_id',
                name: 'employee_id'
            },
            {
                data: 'location',
                name: 'location'
            },
            {
                data: 'amount',
                name: 'amount'
            },
            {
                data: 'status',
                name: 'status'
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

            }
        });



        $("#search_value").on('keyup', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                pending_table.draw();
            }
        });


        function checkVal(e){
       var from_date = $("#from_date_pending")[0].value;
       var to_date = $("#to_date_pending")[0].value;

       if(from_date!=="" && to_date!==""){
        pending_table.draw();
       }
     }



        // $(document).on("click", "#create-closing", function() {

        //     table.draw();

        //     var value = $("#date")[0].value;

        //     $("#get_pdf").attr("data-date",value);


        // })



        $(document).on("click", ".edit-pending-amount", function() {

            var id = $(this).data("id");



            $.ajax({
                url: "{{ url('edit-pending-amount') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {
                    $("#date")[0].value = data["date"];
                    // $("#location").val(data["branch_id"]).trigger("change");
                    getEmployees(data["employee_id"]);
                    $("#employee_id")[0].value = data["employee_id"];
                    $("#amount")[0].value = data["amount"];
                    $("#remarks")[0].value = data["remarks"];
                    $("#hidden_id").val(data["id"]);

                }
            })


        })



        $(document).on("click", ".delete-pending-amount", function() {
            var id = $(this).data("id");

            var element = this;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('delete-pending') }}",
                type: "get",
                data: {
                    id: id
                },
                success: function(data) {
                    $(element).parent().parent().parent().parent().fadeOut();

                }
            })

        })




        // $(document).on("click", "#get_pdf", function() {

        //     var date = $(this).data("date");

        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //             url:"{{ url('get-closing-pdf') }}",
        //             type:"GET",
        //             data:{date:date},
        //             success:function(data){
        //                 const pdfData = data[0];
        //                 // Create a blob object from the base64-encoded data
        //                 const byteCharacters = atob(pdfData);
        //                 const byteNumbers = new Array(byteCharacters.length);
        //                 for (let i = 0; i < byteCharacters.length; i++) {
        //                     byteNumbers[i] = byteCharacters.charCodeAt(i);
        //                 }
        //                 const byteArray = new Uint8Array(byteNumbers);
        //                 const blob = new Blob([byteArray], {type: 'application/pdf'});


        //                 // Create a URL for the blob object
        //                 const url = URL.createObjectURL(blob);

        //                 // Create a link element with the URL and click on it to download the PDF file
        //                 const link = document.createElement('a');
        //                 link.href = url;
        //                 link.download = 'test.pdf';
        //                 document.body.appendChild(link);
        //                 link.click();
        //             }
        //     })




        // })


        // $(document).on("click", ".closing-head-edit", function() {

        //     var id = $(this).data("id");
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: "{{ url('edit-closing') }}",
        //         type: "GET",
        //         data: {
        //             id: id
        //         },
        //         success: function(data) {

        //             $("#date").val(data[0]["date"]);
        //             getHeads(data[0]["head"]);
        //             getHeadLocation(data[0]["location"]);
        //             $("#amount").val(data[0]["amount"]);
        //             $("#hidden_id").val(data[0]["id"]);
        //         }
        //     })

        // })





        // $(document).on("click", ".closing-head-delete", function() {

        // var id = $(this).data("id");

        // var element = this;

        // $.ajax({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     },
        //     url: "{{ url('delete-closing') }}",
        //     type: "GET",
        //     data: {
        //         id: id
        //     },
        //     success: function(data) {
        //        $(element).parent().parent().parent().parent().fadeOut();

        //     }
        // })

        // })






        $(".toselect-tag").select2();
        var heads = $("#heads");
        var locations = $("#locations");




        // function getHeadLocation(edit_location = null) {

        //     // heads[0].innerHTML = "";
        //     $.ajax({
        //         url: "{{ url('get-head-locations') }}",
        //         type: "GET",
        //         success: function(data) {
        //             $.each(data, function(key, value) {

        //                 var create_option = document.createElement("option");

        //                 if (edit_location !== null) {

        //                     if (edit_location == value["id"]) {
        //                         create_option.innerText = value["location"];
        //                         create_option.value = value["id"];
        //                         create_option.selected = true;
        //                         locations[0].appendChild(create_option);
        //                     } else {
        //                         create_option.innerText = value["location"];
        //                         create_option.value = value["id"];

        //                         locations[0].appendChild(create_option);
        //                     }

        //                 } else {
        //                     create_option.innerText = value["location"];
        //                     create_option.value = value["id"];
        //                     locations[0].appendChild(create_option);
        //                 }



        //             });
        //         }
        //     })
        // }

        // getHeadLocation();

        // function getHeads(head_id = null) {

        //     locations[0].innerHTML = "";
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: "{{ url('get-heads') }}",
        //         type: "GET",
        //         success: function(data) {

        //             $.each(data, function(key, value) {
        //                 var create_option = document.createElement("option");
        //                 if(head_id !== null){
        //                     create_option.innerText = value["head"];
        //                     create_option.value = value["id"];
        //                     if(head_id == value["id"]){
        //                         create_option.selected=true;
        //                         heads[0].appendChild(create_option);
        //                     }else{
        //                         heads[0].appendChild(create_option);
        //                     }
        //                 }

        //                 var create_option = document.createElement("option");
        //                 create_option.innerText = value["head"];
        //                 create_option.value = value["id"];
        //                 heads[0].appendChild(create_option);


        //             });
        //         }
        //     })
        // }

        // getHeads();






        $('#pending-form').validate({
            errorPlacement: function(error, element) {


            },
            rules: {
                date: "required",
                location: "required",
                head: "required",
                amount: "required",

            },

            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-pending') }}",
                    type: "POST",
                    data: formData,

                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {

                        pending_table.draw();

                        $('#pending-form')[0].reset();
                        $("#hidden_id").val("");

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

        
        function checkAmt(e) {
            
            $("#convert_to_number")[0].innerText=numberToWords(e.value);
        }

        // $(douc)

        // add-easypaisa-form

        $(document).on("click", "#add-easypaisa-form", function() {

            var url = "{{ url('add-easypaisa-form') }}";
            viewModal(url);

        })


        $(document).on("click", "#add-employee-others-forms", function() {

            var url = "{{ url('add-employee-others-form') }}";
            viewModal(url);

        })


        $(document).on("click", "#get-saqah-form", function() {

            var url = "{{ url('add-sadqah') }}";
            viewModal(url);

        })



        $(document).on("click", "#generate_pending_report", function() {

            var url = "{{ url('/generate-full-pending-report') }}";
            viewModal(url);

        })



        $(document).on("click", ".pay_now_pending", function() {

            var data = $(this).data("id").split(",");
            // data[0] and data[1] we split array through data-id
            var url = "{{ url('pay-now') }}" + "/" + data[0] + "/" + data[1] + "/" + data[2] +  "/" + data[3];
            payNowModalBody(url);
           
            
        })

        //         $(document).ready(function() {
        //   // Get the current date
        //   var today = new Date().toISOString().split('T')[0];

        //   console.log(new Date().toISOString());
        //   // Set the min and max attributes of the input field
        //   $('#date').attr('min', today);
        //   $('#date').attr('max', today);
        // });

        $(document).ready(function() {
            var currentDate = new Date();
            //   currentDate.setDate(currentDate.getDate() - 1);
            currentDate.setDate(currentDate.getDate());
            var pastDay = currentDate.toISOString().split('T')[0];
            $('#date').attr('min', pastDay);
            $('#date').attr('max', pastDay);

            console.log(currentDate);
        });
    </script>

