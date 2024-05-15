
<div>
    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}

        <div class="col-lg-4 col-sm-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">HBL Account</h6>
                    <div>
                        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="add-easypaisa-form">Add HBL Amt</a> --}}
                    {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="add-easypaisa-form">Add Easypaisa Amt</a> --}}
                        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="add-employee-others-forms">Employee/Others</a> --}}
                        </div>
                </div>
                <div class="card-body">
                    <form id="paid_amount_form" class="data-form">

                    
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Type</label>
                              <select name="employee_type" id="employee_type"  class="form-control" onchange="chooseOption(this)" onkeyup="validateError(this)">
                                <option value="">Select Type</option>
                                <option>Employee</option>
                                {{-- <option>Patty</option> --}}
                                <option>Others</option>
                                <!-- <option>Fuel</option> -->
                              </select>
                        </div>


                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Branch</label>
                            <select class="form-control" id="easypaisa_detail_locations" style="width:100%;" name="easypaisa_detail_locations" onchange="getEmployees()">
                                <option value="">Select Branch</option>
                                  @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->location }}</option>
                                  @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Select Employee/Others</label>
                              <select name="employee_id" id="employee_id"  class="form-control toselect-tag-employee" onchange="validateError(this)">
                                
                              </select>
                        </div>

                        

                        <div class="form-group">
                            <label for="exampleFormControlInput1" >Purpose</label>
                              <select class="form-control" disabled id="purpose_type">
                                    <option id="advance">Advance</option>
                                    {{-- <option id="patty_cash">Patty</option> --}}
                                    <option id="others">Others</option>
                                    <option id="fuel">Fuel</option>
                              </select>
                        </div>
                        <input type="hidden" name="purpose" id="purpose">

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Advance Payment Month</label>
                            <input type="month" class="form-control" id="advance_payment_month" name="advance_payment_month" onchange="validateError(this)" onkeyup="validateError(this)">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Amount</label>
                            {{-- <input type="input" class="form-control" id="paid_amount" name="paid_amount" onkeyup="checkAmount(this)"> --}}
                            <input type="input" class="form-control" id="amount" name="amount" onkeyup="checkAmt(this)">
                        </div>

                        <div class="form-group" id="convert_to_number">
                             
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Remarks</label>
                            <input type="input" class="form-control" id="remarks" name="remarks" onkeyup="validateError(this)">
                        </div>

                        {{-- <div class="form-group">
                            <label for="exampleFormControlInput1">Amount</label>
                            <input type="input" class="form-control" id="amount" name="amount" onkeyup="validate(this)">
                        </div> --}}

                        <div class="form-group d-flex justify-content-end">
                            <input type="submit" value="Add" class="btn btn-primary" >
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
                            <input type="date" class="form-control mr-3" id="from_date" name="from_date" onchange="checkVal(this)">
                            <input type="date" class="form-control" id="to_date" name="to_date" onchange="checkVal(this)">
                        </div>
                        
                        <div class="mb-3">
                            <input type="text" class="form-control" id="search_value" name="search_value" placeholder="Type here to search........">
                        </div>
                        <table class="table table-bordered datatable_paid_list" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Paid_Date</th>
                                    <th>Paid_To</th>
                                    <th>Purpose</th>
                                    <th>status</th>
                                    <th>amount</th>
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
            
            $("#convert_to_number")[0].innerText=numberToWords(e.value);
        }

// $( document ).on( 'keydown', function ( e ) {

// console.log(e.keyCode);
// if ( e.keyCode === 17 ) {
//     $("#employee_type").focus();
// }
// });



// $( document ).on( 'keydown', function ( e ) {

// console.log(e.keyCode);
// if ( e.keyCode === 16 ) {
// $("#from_date").focus();
// }
// });



$("#employee_type").change(function(){
    if($("#employee_type")[0].value == "Employee"){
        console.log("yes");
        $("#advance_payment_month")[0].required=true;
    }else{
        $("#advance_payment_month")[0].required=true;
    }
    

})



function chooseOption(e){

$("#employee_type")[0].style.border="";
if(e.value == "Employee"){
     $("#advance")[0].selected=true;
     $("#advance_payment_month")[0].disabled=false;
 }else if(e.value == "Patty"){
     $("#patty_cash")[0].selected=true;
     $("#advance_payment_month")[0].disabled=true;
 }else if(e.value == "Others"){
    $("#others")[0].selected=true;
     $("#advance_payment_month")[0].disabled=true;
 }else if(e.value == "Fuel"){
    $("#fuel")[0].selected=true;
     $("#advance_payment_month")[0].disabled=true;
 }
 

 getEmployees();

 var check_value = $("#purpose_type")[0].value;
 
 $("#purpose").val(check_value);

}

// function checkAmount(e){
//  e.style.border="";
//  var easypaisa_amount_store = $("#easypaisa_amount_store")[0].value;
//  var amount = e.value;
//  var deducted = easypaisa_amount_store - amount;
//  var amount_after_deducation = $("#amount_get").val(deducted);
 
//  if($("#amount_get")[0].value<=0){
//      $("#amount_get")[0].style.border="1px solid red";
//  }else{
//      $("#amount_get")[0].style.border="";
//  }

// }

function getCurrentAmount(){

 $.ajax({
     url:"{{ url('get-current_amount') }}",
     type:"POST",
     success:function(data){
      
     }
 })

}



//paid easy paisa amount form
function getEmployees(get_employee){
 // $("#employee_type")[0].style.border="";
 $("#easypaisa_detail_locations")[0].style.border="";
 var parent = $("#employee_id")[0];
 parent.innerHTML="";
//  $("#amount_get").val("");
//  $("#easypaisa_amount_store").val("");

 var get_employee_value = $("#employee_type")[0].value;
 var get_branch = $("#easypaisa_detail_locations")[0].value;
 

 $.ajax({
     headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
     url:"{{ url('get-employees') }}",
     type:"POST",
     data:{branch:get_branch, employee_type:get_employee_value},
     success:function(data){

        console.log(get_employee)

         $.each(data[0], function(key, value) {
                     var create_option = document.createElement("option");
                     create_option.value =  value["id"];
                     create_option.innerText =  value["employee_name"]+(value["employee_post"] ? "-"+value["employee_post"] : "");

                     if(value["id"] == get_employee){
                        create_option.selected = true;
                     }

                     parent.appendChild(create_option);
         });

        //  var amount_get = $("#amount_get").val(data[1]["remaining_amount"]);
         //this amount is store for calculation
        //  var amount_store = $("#easypaisa_amount_store").val(data[1]["remaining_amount"]);
     }
 })
}



//easy pasia employee others 

var easypaisa_paid_table = $('.datatable_paid_list').DataTable({
         processing: true,
         serverSide: true,
         searching: false,
        //  paging: false,
        //  "info": false,
         "language": {
             "infoFiltered": ""
         },

         ajax: {
             url: "{{ url('get-report-of-hbl-amount') }}",
             data: function(d) {
                 d.search_value = $("#search_value").val()
                 d.from_date = $("#from_date").val()
                 d.to_date = $("#to_date").val()
             }
         },
         columns: [

             {
                 data: 'paid_date',
                 name: 'paid_date'
             },
             {
                 data: 'employee',
                 name: 'employee'
             },
             {
                 data: 'purpose',
                 name: 'purpose'
             },

             {
                 data: 'status',
                 name: 'status',
             },
             {
                 data: 'amount',
                 name: 'amount',
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


     $("#search_value").on('keyup', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                easypaisa_paid_table.draw();
            }
        });


        function checkVal(e){

if($("#from_date")[0].value !=="" && $("#to_date")[0].value ){
    easypaisa_paid_table.draw();

}

}



   
$('#paid_amount_form').validate({
         errorPlacement: function(error, element) {
                //  element[0].style.border = "1px solid red";
         },
         rules: {
           
            // paid_date:"required",
            employee_id : "required",
            purpose : "required",
            amount:  'required'

         },

         submitHandler: function(form) {

            //  if($("#amount_get")[0].value<=0){
            //      alert("Your balance is too low!");
            //      return false;
            //  }
             var amount = $("#amount")[0].value;
            //  var name = $("#employee_id")[0].innerText;
            
              var name = $("#employee_id")[0].firstChild.innerText;
             if (confirm('Are you sure! you paid amount of Rs.'+amount+' to '+name+' this amount will not return if it paid')) {
             var formData = new FormData(form);
             $.ajax({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 url: "{{ url('insert-hbl-amount') }}",
                 type: "POST",
                 data: formData,
                 contentType: false,
                 cache: false,
                 processData: false,
                 success: function(data) {
                     easypaisa_paid_table.draw();
                     $('#paid_amount_form')[0].reset();
                     getEmployees();
                     getHBLClosing();
                     $("#hidden_id").val("");
                 }
                

             })
         }
     }
     });




        
     $(document).on("click", ".edit-hbl-amount", function() {

        var id = $(this).data("id");
        $.ajax({
            headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
            url:"{{ url('edit-hbl-amount') }}",
            type:"POST",
            data:{id:id},
            success:function(data){
                
                $("#employee_type").val(data[0]["employee_type"]);
                $("#easypaisa_detail_locations").val(data[0]["employee_branch"]);
                getEmployees(data[1]["employee_id"]);
                $("#purpose_type").val(data[1]["purpose"]);
                $("#purpose").val(data[1]["purpose"]);

                
                //this code is for month input tag
                const date = new Date(data[1]["paid_for_month_date"]); // Current date
                const month = date.toLocaleString('default', { month: 'numeric' }); // 'long' gives the full month name
                const year = date.toLocaleString('default', { year: 'numeric' });

                var create_month = (month < 10 ? "0"+month :  month);
                var create_month_date = year + "-" + create_month;
                if(data[0]["employee_type"] == "Advance"){
                    var advance_payment_month = $("#advance_payment_month").val(create_month_date);
                }else{
                    $("#advance_payment_month")[0].disabled=true;
                }
               

                var amount = $("#amount").val(data[1]["amount"]);
                var remarks = $("#remarks").val(data[1]["remarks"]);
                $("#hidden_id").val(data[1]["id"]);


            }
        })

        })




        $("#add-employee-others-forms").click(function(){

            var url = "{{ url('add-employee-others-form') }}";
            viewModal(url);
                
        })




    $(".toselect-tag-employee").select2();


    function validateError(e){
         e.style.border="";
           
        }

    function checkAmount(){

    }


    
    $("#generate_full_report").click(function(){

        var url = "{{ url('get-full-report-of-hbl-amount') }}";
        viewModal(url);
                
    })



$(document).ready(function() {
  // Get the current date
  var today = new Date().toISOString().split('T')[0];

  console.log(new Date().toISOString());
  // Set the min and max attributes of the input field
  $('#date').attr('min', today);
  $('#date').attr('max', today);



// Get the current date
var currentDate = new Date();

// Calculate the current year and month as a string in the format "YYYY-MM"
var currentYearMonth = currentDate.toISOString().slice(0, 7);

// Get the <input> element
var monthInput = document.getElementById('advance_payment_month');

// Set the minimum value of the input to the next month
var nextMonth = new Date(currentDate.getFullYear(), currentDate.getMonth());
monthInput.min = nextMonth.toISOString().slice(0, 7);




});


















</script>


