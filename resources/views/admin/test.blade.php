@section("content")

<div>
    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}

        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Paid</h6>
                    <div>
                        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="add-easypaisa-form">Add HBL Amt</a> --}}
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="add-easypaisa-form">Add Easypaisa Amt</a>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="add-employee-others-forms">Employee/Others</a>
                        </div>
                </div>
                <div class="card-body">
                    <form id="paid_amount_form" class="data-form">

                        <div class="form-group">
                            <input type="text" class="form-control" id="amount_get" disabled>
                            <input type="hidden" id="easypaisa_amount_store">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Date</label>
                            <input type="date" class="form-control" name="easypasia_amount_detail_date" id="date" onkeyup="validateError(this)"
                                value="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Type</label>
                              <select name="employee_type" id="employee_type"  class="form-control" onchange="chooseOption(this)" onkeyup="validateError(this)">
                                <option value="">Select Type</option>
                                <option>Employee</option>
                                <option>Others</option>
                              </select>
                        </div>


                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Location</label>
                            <select class="form-control" id="easypaisa_detail_locations" style="width:100%;" name="easypaisa_detail_locations" onchange="getEmployees()">
                                <option value="">Select Branch</option>
                                  @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->location }}</option>
                                  @endforeach
                              
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="exampleFormControlInput1">Select Employee/Others</label>
                              <select name="employee_others" id="employee_others"  class="form-control" onchange="validateError(this)">
                                
                              </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1" >Purpose</label>
                              <select class="form-control" disabled id="purpose_type">
                                    <option id="advance">Advance</option>
                                    <option id="others">Other</option>
                              </select>
                        </div>

                        <input type="hidden" name="purpose" id="purpose">

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Advance Payment Month</label>
                            <input type="month" class="form-control" id="advance_payment_month" name="advance_payment_month" onchange="validateError(this)" onkeyup="validateError(this)">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Amount</label>
                            <input type="input" class="form-control" id="paid_amount" name="paid_amount" onkeyup="checkAmount(this)">
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

        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Paid List</h6>
                    <div>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="generate_full_report"><i
                            class="fas fa-download fa-sm text-white-50"></i> Generate Full Report</a>
                        </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered datatable_paid_list" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th>Paid_To</th>
                                    <th>Purpose</th>
                                    <th>Amount</th>
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

@endsection

@section("script")

<script>

function chooseOption(e){

$("#employee_type")[0].style.border="";
if(e.value == "Employee"){
     $("#advance")[0].selected=true;
     $("#advance_payment_month")[0].disabled=false;
 }else{
     $("#others")[0].selected=true;
     $("#advance_payment_month")[0].disabled=true;
 }

 getEmployees();

 var check_value = $("#purpose_type")[0].value;
 
 $("#purpose").val(check_value);

}

function checkAmount(e){
 e.style.border="";
 var easypaisa_amount_store = $("#easypaisa_amount_store")[0].value;
 var amount = e.value;
 var deducted = easypaisa_amount_store - amount;
 var amount_after_deducation = $("#amount_get").val(deducted);
 
 if($("#amount_get")[0].value<=0){
     $("#amount_get")[0].style.border="1px solid red";
 }else{
     $("#amount_get")[0].style.border="";
 }

}

function getCurrentAmount(){

 $.ajax({
     url:"{{ url('get-current_amount') }}",
     type:"POST",
     success:function(data){
      
     }
 })

}



//paid easy paisa amount form

function getEmployees(){
 // $("#employee_type")[0].style.border="";
 $("#easypaisa_detail_locations")[0].style.border="";
 var parent = $("#employee_others")[0];
 parent.innerHTML="";
 $("#amount_get").val("");
 $("#easypaisa_amount_store").val("");

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

         console.log(data);
       

         $.each(data[0], function(key, value) {
                     var create_option = document.createElement("option");
                     create_option.value =  value["id"];
                     create_option.innerText =  value["employee_name"];
                     parent.appendChild(create_option);
         });

         var amount_get = $("#amount_get").val(data[1]["remaining_amount"]);
         //this amount is store for calculation
         var amount_store = $("#easypaisa_amount_store").val(data[1]["remaining_amount"]);
     }
 })
}



//easy pasia employee others 

var easypaisa_paid_table = $('.datatable_paid_list').DataTable({
         processing: true,
         serverSide: true,
         searching: false,
         paging: false,
         "info": false,
         "language": {
             "infoFiltered": ""
         },

         ajax: {
             url: "{{ url('get-easypaisa-amount-detail') }}",
             data: function(d) {
                 d.date = $("#date").val()
             }
         },
         columns: [

             {
                 data: 'branches',
                 name: 'branches'
             },
             {
                 data: 'paid_to',
                 name: 'paid_to'
             },
             {
                 data: 'purpose',
                 name: 'purpose'
             },
             {
                 data: 'amount',
                 name: 'amount',
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



     // var maxDate = year + '-' + month + '-' + day;
    
     // $('#advance_payment_month').attr('min', maxDate); 

 

   
$('#paid_amount_form').validate({
         errorPlacement: function(error, element) {
                 element[0].style.border = "1px solid red";
         },
         rules: {
             easypasia_amount_detail_date: "required",
             employee_type: "required",
             easypaisa_detail_locations: "required",
             employee_others: "required",
             // advance_payment_month:"required",
             purpose: "required",
             paid_amount: "required",
             remarks: "required"

         },

         submitHandler: function(form) {

             if($("#amount_get")[0].value<=0){
                 alert("Your balance is too low!");
                 return false;
             }
             var amount = $("#paid_amount")[0].value;
             var name = $("#employee_others")[0].innerText;
             if (confirm('Are you sure! you paid amount of Rs.'+amount+' to '+name+' this amount will not return if it paid')) {
             var formData = new FormData(form);
             $.ajax({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 url: "{{ url('insert-paid_amount') }}",
                 type: "POST",
                 data: formData,
                 contentType: false,
                 cache: false,
                 processData: false,
                 success: function(data) {

                     easypaisa_paid_table.draw();

                     $('#paid_amount_form')[0].reset();

                     getEmployees();

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

</script>


@endsection