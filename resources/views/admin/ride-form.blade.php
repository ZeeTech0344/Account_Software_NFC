
    <div>
        <div class="col-12 d-flex justify-content-center">

            {{-- <div class="col-lg-6 col-sm-12"> --}}

            <div class="col-lg-4 col-sm-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Rides</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="add-employee-others-forms">Employee/Others</a> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="rides-form" class="data-form">
                           
                            {{-- <div class="form-group">
                                <label for="exampleFormControlSelect1">Branch</label>
                                <select class="form-control" id="location" style="width:100%;" name="location"
                                    onclick="validate(this)" onchange="selectLocation()">
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->location }}</option>
                                    @endforeach
                                </select>
                            </div> --}}

                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Riders</label>
                                <select class="form-control toselect-tag" id="employee_id" 
                                    name="employee_id">
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Shift</label>
                                <select class="form-control" id="shift" 
                                    name="shift">
                                    <option value="">Select Shift</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->location }}</option>
                                    @endforeach
                                </select>
                            </div> 

                          
                          
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Rides</label>
                                <input type="input" class="form-control" id="rides" name="rides">
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Amount</label>
                                <input type="input" class="form-control"  id="amount" name="amount" onkeyup="checkAmt(this)">
                            </div>


                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Fuel Type</label>
                                <select class="form-control" id="fuel_type" 
                                    name="fuel_type">
                                    <option value="">Select Fuel Type</option>
                                    @foreach ($fuel_type as $fuel)
                                        <option value="{{ $fuel->id }}">{{ $fuel->employee_name }}</option>
                                    @endforeach
                                </select>
                            </div> 

                            {{-- <div class="form-group">
                                <label for="exampleFormControlSelect1">Pay Through</label>
                                <select class="form-control" id="account_name" 
                                    name="account_name">
                                    <option value="">Select Account</option>
                                    <option>Locker</option>
                                    <option>Easypaisa</option>
                                    <option>HBL</option>
                                </select>
                            </div>  --}}
    
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
                        <h6 class="m-0 font-weight-bold text-primary">Rides List</h6>
                        <div>
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm" onclick="reset()">Reset</a>
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="bulkPayNow()">Check Total Amount</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">

                            <div class="mb-3 d-flex">
                                <input type="date" class="form-control" id="from_date" name="from_date" onchange="checkVal(this)">
                                
                            </div>

    
                            <div class="mb-3 d-flex">
                                {{-- <input type="text" class="form-control mr-3" id="search_value" name="search_value" placeholder="Type here to search........"> --}}

                        
                                    <select class="form-control" id="shift_list" onchange="refreshList()">
                                        <option value="">Select Shift</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->location }}</option>
                                        @endforeach
                                    </select>
                                

                            </div>

                            <div class="mb-3 d-flex justify-content-end">
                                <div>
                                    <a href="#" id="all_checked"  class="d-none  btn btn-sm btn-danger shadow-sm" onclick="checkAll(this)">Check All</a>
                                </div>
                            </div>


                            <table class="table table-bordered datatable_vendor" id="dataTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Check</th>
                                        <th>Date</th>
                                        <th>Employee</th>
                                        <th>Branch</th>
                                        <th>Shift</th>
                                        <th>Rides</th>
                                        <th>Amount</th>
                                        {{-- <th>Status</th> --}}
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

        

function checkAmt(e){
   
          
            $("#convert_to_number")[0].innerText=numberToWords(e.value);
        
}

$( document ).on( 'keydown', function ( e ) {


if ( e.keyCode === 17 ) {
    $("#location").focus();
    
}
});



// $( document ).on( 'keydown', function ( e ) {

// if ( e.keyCode === 16 ) {
// $("#from_date").focus();
// }
// });







$(document).on("click", ".pay_now_rides", function() {

var data = $(this).data("id").split(",");
// data[0] and data[1] we split array through data-id
var url = "{{ url('pay-now-rides') }}" + "/" + data[0] + "/" + data[1] + "/" + data[2] +  "/" + data[3];
payNowModalBody(url);


})

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
                url: "{{ url('get-list-riders') }}",
                data: function(d) {
                    d.from_date = $("#from_date").val()
                    d.search_value = $("#search_value").val()
                    d.shift = $("#shift_list").val()
                    d.all_checked = $("#all_checked").data("id");
                }
            },
            columns: [

                {
                    data: 'checkbox',
                    name: 'checkbox'
                },

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
                    data: 'shift',
                    name: 'shift'
                },
                {
                    data: 'rides',
                    name: 'rides'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                // {
                //     data: 'status',
                //     name: 'status'
                // },
               
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


        $('#rides-form').validate({
            errorPlacement: function(error, element) {


                // element[0].style.border = "1px solid red";

            },
            rules: {
                location: "required",
                employee_id: "required",
                rides: "required",
                amount: "required",
                shift: "required",
                fuel_type: "required"
            },

            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-rides') }}",
                    type: "POST",
                    data: formData,

                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {

                        $('#all_checked').attr('data-id' , '');
                        pending_table.draw();

                        $('#rides-form')[0].reset();

                        $("#from_date").val("");
                        $("#shift_list").val("");

                        $("#hidden_id").val("");
                     
                    },
                    error: function(data) {
                        
                    }

                })
            }
        });



        function reset(){
            $("#from_date").val("");
            $("#shift_list").val("");
            $('#checked_all').attr('data-id' , '');
            pending_table.draw();
        }


        function refreshList(){
             var from_date = $("#from_date")[0].value;
           
             var shift_list = $("#shift_list")[0].value;

            if(shift_list !==""  && from_date !== ""){
                $('#all_checked').attr('data-id' , 'checked_all');
                pending_table.draw();
              
            }


            // var from_date = new Date(from_date);
            // var to_date = new Date(to_date);

            // var Difference_In_Time = to_date.getTime() - from_date.getTime();
      
            // var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

            // if(Difference_In_Days == 0){
            //     pending_table.draw();
            // }

        }


        function checkAll(e){

            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;

            var from_date = new Date(from_date);
            // var to_date = new Date(to_date);

            // var Difference_In_Time = to_date.getTime() - from_date.getTime();
      
            // var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
            
           

            // if(parseInt(Difference_In_Days) == 0 ){
            //     $('#all_checked').attr('data-id' , 'checked_all');
            //     pending_table.draw();

            // }


        }


        function checkVal(e){

            var from_date = $("#from_date")[0].value;
        
            var shift_list = $("#shift_list")[0].value;

            if(from_date !=="" && shift_list !== ""){
                $('#all_checked').attr('data-id' , 'checked_all');
                pending_table.draw();
            }

        }



        $("#search_value").on('keyup', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                pending_table.draw();
            }
        });




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
                    $("#location").val(data["branch_id"]).trigger("change");
                    $("#employee_id")[0].value = data["employee_id"];
                    $("#amount")[0].value = data["amount"];
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



        $(".toselect-tag").select2();
        var heads = $("#heads");
        var locations = $("#locations");

     
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

                    console.log(data);

                    $("#location").val(data["branch_id"]);
                    getEmployees(data["employee_id"]);
                    $("#shift").val(data["shift"]);
                    $("#rides").val(data["rides"]);
                    $("#amount").val(data["amount"]);
                    $("#fuel_type").val(data["fuel_type"]);
                    $("#account_name").val(data["account_name"]);
                    $("#hidden_id").val(data["id"]);

                }
           })

        })



      


        $(document).on("click", ".pay-rider-amount", function() {

            var data = $(this).data("id").split(",");
            // data[0] and data[1] we split array through data-id
            var url = "{{ url('pay-now-rides') }}" + "/" + data[0] + "/" + data[1] + "/" + data[2] +  "/" + data[3];
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


    
        function bulkPayNow(){

        var checkboxes = document.getElementsByName('rider_id');
        var checkedValues = [];

        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
            checkedValues.push(checkboxes[i].value);
            }
        }

     if(checkedValues.length>0){

        var ride_info = [];

        for(var a=0; a<checkedValues.length; a++){

           // console.log(checkedValues[a].split(","));
            ride_info.push(checkedValues[a].split(","));
        }

        // console.log(ride_info);

    
            var url = "{{ url('pay-now-bulk-rides') }}" + "/" + ride_info;
            payNowModalBody(url);

     }

     console.log(checkedValues);


    }

      
    </script>

