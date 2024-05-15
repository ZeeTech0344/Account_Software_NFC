
    <div>
        <div class="col-12 d-flex justify-content-center">

            {{-- <div class="col-lg-6 col-sm-12"> --}}

            <div class="col-lg-4 col-sm-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Owner/Other Pending Form</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="add-employee-others-forms">Employee/Others</a> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="sadqa-form" class="data-form">
                           
                        
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Date</label>
                                <input type="date" class="form-control" id="date"  name="date">
                                
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Pending (Type)</label>
                                <select name="type" class="form-control" id="type">
                                    <option value="">Select Pending Type</option>
                                    <option>Owner</option>
                                    <option>Others</option>
                                    <option>Police Pending</option>
                                    <option>Phase-II Pending</option>
                                    <option>Basti Pending</option>
                                    <option>Taxila Pending</option>
                                    <option>Attock Pending</option>
                                    <option>Tajamal Pending</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="exampleFormControlInput1">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" onkeyup="calculate(this)">
                            </div>
                            <div class="form-group" id="convert_to_number">
                             
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Remarks</label>
                                <input type="input" class="form-control"  id="remarks" name="remarks">
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
                        <h6 class="m-0 font-weight-bold text-primary">Owner/Other Pending</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="get_pdf"><i
                        class="fas fa-download fa-sm text-white-50"></i> PDF</a> --}}
                             <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="view_owner_pending_report" >View Owner/Other Pending Report
                                </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">

                            <div class="mb-3 d-flex">
                                <input type="date" class="form-control mr-3" id="from_date" name="from_date" onchange="checkVal(this)">
                                <input type="date" class="form-control mr-3" id="to_date" name="to_date" onchange="checkVal(this)">
                                
                                <select name="pending_type" class="form-control" id="pending_type" onchange="checkVal(this)">
                                    <option>Owner</option>
                                    <option>Others</option>
                                    <option>Police Pending</option>
                                    <option>Phase-II Pending</option>
                                    <option>Basti Pending</option>
                                    <option>Taxila Pending</option>
                                    <option>Attock Pending</option>
                                    <option>Tajamal Pending</option>
                                </select>
                            </div>
    
                            {{-- <div class="mb-3">
                                <input type="text" class="form-control" id="search_value" name="search_value" placeholder="Type here to search........">
                            </div> --}}


                            <table class="table table-bordered datatable_vendor" id="dataTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        {{-- <th>Date</th> --}}
                                        <th>Date</th>
                                        <th>Pending</th>
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



// $( document ).on( 'keydown', function ( e ) {


// if ( e.keyCode === 17 ) {
//     $("#location").focus();
    
// }
// });



function calculate(e){

    var sadqa_amount_hidden = $("#sadqa_amount_hidden")[0].value;

    var pay_sadqa_amount = $("#pay_sadqa_amount")[0].value;

    var sadqa_amount = $("#sadqa_amount").val(sadqa_amount_hidden - pay_sadqa_amount);

  
     $("#convert_to_number")[0].innerText=numberToWords(e.value);
        

}


// $( document ).on( 'keydown', function ( e ) {

// if ( e.keyCode === 16 ) {
// $("#from_date").focus();
// }
// });







$(document).on("click", "#view_owner_pending_report", function() {

var from = $("#from_date")[0].value;
var to = $("#to_date")[0].value;
var pending_type = $("#pending_type")[0].value;
var url = "{{ url('view-owner-pending-report') }}" + "/" + from + "/" + to + "/" + pending_type;
viewModal(url);


})


// view-owner-pending-report/{from}/{to}

        // function selectLocation() {
        //     getEmployees();
        // }


        // function getEmployees(employee_id) {

        //     var parent = $("#employee_id")[0];
        //     parent.innerHTML = "";

        //     var get_branch = $("#location")[0].value;

        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: "{{ url('get-riders') }}",
        //         type: "POST",
        //         data: {
        //             branch: get_branch
        //         },
        //         success: function(data) {

        //             console.log(data);

        //             $.each(data[0], function(key, value) {
        //                 var create_option = document.createElement("option");
        //                 create_option.value = value["id"];
        //                 create_option.innerText = value["employee_name"];
        //                 if(value["id"] == employee_id){
        //                     create_option.selected=true;
        //                 }
        //                 parent.appendChild(create_option);
        //             });

        //             //  var amount_get = $("#amount_get").val(data[1]["remaining_amount"]);
        //             //this amount is store for calculation
        //             //  var amount_store = $("#easypaisa_amount_store").val(data[1]["remaining_amount"]);
        //         }
        //     })
        // }




        var pending_table = $('.datatable_vendor').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            // paging: false,
            // "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('get-owner-pending-list') }}",
                data: function(d) {
                    d.to_date = $("#to_date").val()
                    d.from_date = $("#from_date").val()
                    d.pending_type =  $("#pending_type").val()
                }
            },
            columns: [

                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'pending',
                    name: 'pending'
                },
                {
                    data: 'amount',
                    name: 'amount'
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


        function checkVal(e){

            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;

            if(from_date !=="" && to_date !== "" ){
                pending_table.draw();
            }

        }



        // $("#search_value").on('keyup', function (e) {
        //     if (e.key === 'Enter' || e.keyCode === 13) {
        //         pending_table.draw();
        //     }
        // });




        $(document).on("click", "#view_sadqa_report", function() {

            var url = "{{ url('view-sadqa-report') }}";
            viewModal(url);


        })



        $(document).on("click", ".edit-owner-pending", function() {

            var id = $(this).data("id");

            console.log(id);

            $.ajax({
                url: "{{ url('edit-owner-pending') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {
                    $("#date")[0].value = data["date"];
                    $("#amount").val(data["amount"]);
                    $("#remarks")[0].value = data["remarks"];
                    $("#type")[0].value = data["type"];
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

        

        $('#sadqa-form').validate({
            errorPlacement: function(error, element) {
                // element[0].style.border = "1px solid red";
            },
            rules: {
                date: "required",
                amount: "required",
                remarks: "required",
            },

            submitHandler: function(form) {

                if (confirm('Add Pending! Are you sure')) {

                

                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-owner-pending') }}",
                    type: "POST",
                    data: formData,

                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {

                        pending_table.draw();

                        $('#sadqa-form')[0].reset();

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
        }
        });

        function validate(e) {
            e.style.border = "";

        }

        // function validateError(e){
        //  e.style.border="";

        // }


        // $(douc)

        // add-easypaisa-form

        $(document).on("click", ".edit-rider-amount", function() {

           var id = $(this).data("id");
           $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:"{{ url('edit-rider-detail') }}",
                type:"GET",
                data:{id:id},
                success:function(data){
                    $("#location").val(data["branch_id"]);
                    getEmployees(data["employee_id"]);
                    $("#shift").val(data["shift"]);
                    $("#rides").val(data["rides"]);
                    $("#amount").val(data["amount"]);
                    $("#hidden_id").val(data["id"]);

                }
           })

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

        // $(document).ready(function() {
        //     var currentDate = new Date();
        //     //   currentDate.setDate(currentDate.getDate() - 1);
        //     currentDate.setDate(currentDate.getDate());
        //     var pastDay = currentDate.toISOString().split('T')[0];
        //     $('#date').attr('min', pastDay);
        //     $('#date').attr('max', pastDay);

        //     console.log(currentDate);
        // });
    </script>

