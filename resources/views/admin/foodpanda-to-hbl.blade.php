

    <div>
        <div class="col-12 d-flex justify-content-center">

            {{-- <div class="col-lg-6 col-sm-12"> --}}

            <div class="col-lg-4 col-sm-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Food Panda</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="add-employee-others-forms">Employee/Others</a> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="foodpanda-amount-form" class="data-form">
                            
                            {{-- <div class="form-group">
                                <label for="exampleFormControlInput1">Food Panda Date</label>
                                <input type="date" class="form-control" id="date" name="date" onchange="getFoodpandaAmount()">
                            </div> --}}
                        
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Total Amount (Food Panda)</label>
                                <input type="text" class="form-control" id="total_amount" disabled   name="total_amount">
                
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Account</label>
                                <select name="account" class="form-control"    id="account">
                                    <option value="">Select Account</option>
                                    <option>HBL</option>
                                    <option>Easypaisa</option>
                                    <option>Locker</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" onkeyup="checkAmt(this)">
                            </div>
                            <div class="form-group" id="convert_to_number">
                             
                            </div>



                            <div class="form-group">
                                <label for="exampleFormControlInput1">Remarks</label>
                                <input type="input" class="form-control"  id="remarks" name="remarks">
                            </div>

                        
                            <div class="form-group d-flex justify-content-end">
                                <input type="submit" value="Send" class="btn btn-primary">
                            </div>
                            <input type="hidden" name="hidden_id" id="hidden_id">
                        </form>

                    </div>

                </div>
            </div>

            <div class="col-lg-8 col-sm-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Foodpanda List</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="get_pdf"><i
                        class="fas fa-download fa-sm text-white-50"></i> PDF</a> --}}
                             <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="view_foodpanda_report" >View Foodpanda Report
                                </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">

                            <div class="mb-3 d-flex">
                                <input type="date" class="form-control mr-3" id="from_date" name="from_date" onchange="checkVal(this)">
                                <input type="date" class="form-control" id="to_date" name="to_date" onchange="checkVal(this)">
                            </div>
    
                            {{-- <div class="mb-3">
                                <input type="text" class="form-control" id="search_value" name="search_value" placeholder="Type here to search........">
                            </div> --}}


                            <table class="table table-bordered datatable_vendor" id="dataTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Foodpanda_Date</th>
                                        <th>Send Date</th>
                                        <th>Send_Amount_Acc</th>
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

function checkAmt(e) {
            e.style.border = "";
            $("#convert_to_number")[0].innerText=numberToWords(e.value);
        }


function getFoodpandaAmount(get_data){
    
   
    // if(get_data == undefined){
    //     var date = $("#date")[0].value;
    // }else{
    //     var date = get_data;
    // }

    // console.log(get_data);

   
   

    $.ajax({
        headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
        url:"{{ url('get-foodpanda-amount-using-date') }}",
        type:"POST",
        // data:{date:date},
        success:function(data){
           
            var total_amount = $("#total_amount").val(data);
        }
    })
}

getFoodpandaAmount();







$(document).on("click", ".pay_now_rides", function() {

var data = $(this).data("id").split(",");
// data[0] and data[1] we split array through data-id
var url = "{{ url('pay-now-rides') }}" + "/" + data[0] + "/" + data[1] + "/" + data[2] +  "/" + data[3];
payNowModalBody(url);


})



       





        function selectLocation() {
            getEmployees();
        }


        function getEmployees(employee_id) {

            var parent = $("#employee_id")[0];
            parent.innerHTML = "";

            var get_branch = $("#location")[0].value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('get-riders') }}",
                type: "POST",
                data: {
                    branch: get_branch
                },
                success: function(data) {

                    $.each(data[0], function(key, value) {
                        var create_option = document.createElement("option");
                        create_option.value = value["id"];
                        create_option.innerText = value["employee_name"];
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
                url: "{{ url('insert-foodpanda-to-hbl-list') }}",
                data: function(d) {
                    d.to_date = $("#to_date").val()
                    d.from_date = $("#from_date").val()
                  
                }
            },
            columns: [
                {
                    data: 'foodpanda_date',
                    name: 'foodpanda_date'
                },
                {
                    data: 'hbl_date',
                    name: 'hbl_date'
                },
                {
                    data: 'account',
                    name: 'account'
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




        $(document).on("click", "#view_foodpanda_report", function() {

            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;

            if(from_date !== "" && to_date !== "" ){
                var url = "{{ url('view-foodpanda-amounts') }}" + "/" + from_date + "/" + to_date ;
                viewModal(url);
            }
        
        })



        $(document).on("click", ".edit-foodpanda-amount", function() {

            var id = $(this).data("id");



            $.ajax({
                url: "{{ url('edit-foodpanda-amount') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {

                    getFoodpandaAmount(data["date"]);

                    // $("#date").val(data["date"]);
                    $("#account").val(data["account"]);
                    $("#amount").val(data["amount"]);
                    $("#remarks").val(data["remarks"]);
                     $("#hidden_id").val(data["id"]);

                }
            })


        })



        $(document).on("click", ".delete-foodpanda-amount", function() {

            
            var id = $(this).data("id");

            var element = this;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('delete-foodpanda-amount') }}",
                type: "get",
                data: {
                    id: id
                },
                success: function(data) {
                    $(element).parent().parent().parent().parent().fadeOut();

                }
            })




        })

        

        $('#foodpanda-amount-form').validate({
           
            rules: {
                total_amount: "required",
                account: "required",
                amount: "required",
            },

            submitHandler: function(form) {

                if (confirm('Send to HBL! Are you sure')) {

                

                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-foodpanda-to-hbl') }}",
                    type: "POST",
                    data: formData,

                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {

                  
                        pending_table.draw();
                        $('#foodpanda-amount-form')[0].reset();


                    
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
