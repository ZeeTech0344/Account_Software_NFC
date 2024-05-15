@extends('layout.structure')

@section('content')




@endsection


@section('script')

    <script>


// function chooseOption(e){

//    $("#employee_type")[0].style.border="";
//    if(e.value == "Employee"){
//         $("#advance")[0].selected=true;
//         $("#advance_payment_month")[0].disabled=false;
//     }else{
//         $("#others")[0].selected=true;
//         $("#advance_payment_month")[0].disabled=true;
//     }

//     getEmployees();

//     var check_value = $("#purpose_type")[0].value;
    
//     $("#purpose").val(check_value);

// }

// function checkAmount(e){
//     e.style.border="";
//     var easypaisa_amount_store = $("#easypaisa_amount_store")[0].value;
//     var amount = e.value;
//     var deducted = easypaisa_amount_store - amount;
//     var amount_after_deducation = $("#amount_get").val(deducted);
    
//     if($("#amount_get")[0].value<=0){
//         $("#amount_get")[0].style.border="1px solid red";
//     }else{
//         $("#amount_get")[0].style.border="";
//     }
   
// }

// function getCurrentAmount(){

//     $.ajax({
//         url:"{{ url('get-current_amount') }}",
//         type:"POST",
//         success:function(data){
         
//         }
//     })

// }



// //paid easy paisa amount form

// function getEmployees(){
//     // $("#employee_type")[0].style.border="";
//     $("#easypaisa_detail_locations")[0].style.border="";
//     var parent = $("#employee_others")[0];
//     parent.innerHTML="";
//     $("#amount_get").val("");
//     $("#easypaisa_amount_store").val("");

//     var get_employee_value = $("#employee_type")[0].value;
//     var get_branch = $("#easypaisa_detail_locations")[0].value;
    

//     $.ajax({
//         headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 },
//         url:"{{ url('get-employees') }}",
//         type:"POST",
//         data:{branch:get_branch, employee_type:get_employee_value},
//         success:function(data){

//             console.log(data);
          

//             $.each(data[0], function(key, value) {
//                         var create_option = document.createElement("option");
//                         create_option.value =  value["id"];
//                         create_option.innerText =  value["employee_name"];
//                         parent.appendChild(create_option);
//             });

//             var amount_get = $("#amount_get").val(data[1]["remaining_amount"]);
//             //this amount is store for calculation
//             var amount_store = $("#easypaisa_amount_store").val(data[1]["remaining_amount"]);
//         }
//     })
// }



//    //easy pasia employee others 

//    var easypaisa_paid_table = $('.datatable_paid_list').DataTable({
//             processing: true,
//             serverSide: true,
//             searching: false,
//             paging: false,
//             "info": false,
//             "language": {
//                 "infoFiltered": ""
//             },

//             ajax: {
//                 url: "{{ url('get-easypaisa-amount-detail') }}",
//                 data: function(d) {
//                     d.date = $("#date").val()
//                 }
//             },
//             columns: [

//                 {
//                     data: 'branches',
//                     name: 'branches'
//                 },
//                 {
//                     data: 'paid_to',
//                     name: 'paid_to'
//                 },
//                 {
//                     data: 'purpose',
//                     name: 'purpose'
//                 },
//                 {
//                     data: 'amount',
//                     name: 'amount',
//                 },
//                 {
//                     data: 'action',
//                     name: 'action',
//                     orderable: false,
//                     searchable: false
//                 },


//             ],

//             success: function(data) {
              
//             }
//         });



//         // var maxDate = year + '-' + month + '-' + day;
       
//         // $('#advance_payment_month').attr('min', maxDate); 

    

      
// $('#paid_amount_form').validate({
//             errorPlacement: function(error, element) {
//                     element[0].style.border = "1px solid red";
//             },
//             rules: {
//                 easypasia_amount_detail_date: "required",
//                 employee_type: "required",
//                 easypaisa_detail_locations: "required",
//                 employee_others: "required",
//                 // advance_payment_month:"required",
//                 purpose: "required",
//                 paid_amount: "required",
//                 remarks: "required"

//             },

//             submitHandler: function(form) {

//                 if($("#amount_get")[0].value<=0){
//                     alert("Your balance is too low!");
//                     return false;
//                 }
//                 var amount = $("#paid_amount")[0].value;
//                 var name = $("#employee_others")[0].innerText;
//                 if (confirm('Are you sure! you paid amount of Rs.'+amount+' to '+name+' this amount will not return if it paid')) {
//                 var formData = new FormData(form);
//                 $.ajax({
//                     headers: {
//                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                     },
//                     url: "{{ url('insert-paid_amount') }}",
//                     type: "POST",
//                     data: formData,
//                     contentType: false,
//                     cache: false,
//                     processData: false,
//                     success: function(data) {

//                         easypaisa_paid_table.draw();

//                         $('#paid_amount_form')[0].reset();

//                         getEmployees();

//                         // console.log(data);
//                         // if (!$(".alert-danger").hasClass("d-none")) {
//                         //     $(".alert-danger")[0].classList.add("d-none");
//                         // }
//                         // if(data=="saved"){
//                         //     form.reset();
//                         //     $(".file-upload-content")[0].style.display="none";
//                         //     $(".image-upload-wrap")[0].style.display="";
//                         // }
//                         // $(".alert-success")[0].classList.remove("d-none");



//                     },
//                     error: function(data) {
//                         console.log(data);
//                         // if (data.responseJSON.error.length >= 1) {

//                         // $(".alert-success")[0].classList.add("d-none");
//                         // $(".alert-danger")[0].innerText = "Invalid fields";
//                         // $(".alert-danger")[0].classList.remove("d-none");

//                         //this code is for select2 fields for backend validation error
//                         // for (var a = 0; a < $(".select2").length; a++) {
//                         //     if ($(".select2")[a].previousSibling.value == "") {
//                         //         $(".select2-selection")[a].style.border = "1px solid red";
//                         //     }
//                         // }

//                         //this code is for without select2 fields for backend validation error
//                         // var count_errors = data.responseJSON.error.length;
//                         // for (var a = 0; a < count_errors; a++) {
//                         //     var error_text = data.responseJSON.error[a];
//                         //     var find_last_word = error_text.indexOf("field");
//                         //     var name = error_text.substr(4, find_last_word - 5);
//                         //     var create_name = "." + name.replace(" ", "_");
//                         //     var check = $(create_name);
//                         //     check[0].style.cssText = "border:1px solid red";
//                         // }


//                         // }

//                     }

//                 })
//             }
//         }
//         });

   



     












//         //closing table

//         var table = $('.datatable_closing').DataTable({
//             processing: true,
//             serverSide: true,
//             searching: false,
//             paging: false,
//             "info": false,
//             "language": {
//                 "infoFiltered": ""
//             },

//             ajax: {
//                 url: "{{ url('get-closing-list') }}",
//                 data: function(d) {
//                     d.date = $("#date").val()
//                 }
//             },
//             columns: [

//                 {
//                     data: 'head',
//                     name: 'head'
//                 },
//                 {
//                     data: 'location',
//                     name: 'location'
//                 },
//                 {
//                     data: 'amount',
//                     name: 'amount'
//                 },
//                 {
//                     data: 'action',
//                     name: 'action',
//                     orderable: false,
//                     searchable: false
//                 },
//             ],

//             success: function(data) {
              
//             }
//         });


//         $(document).on("click", "#create-closing", function() {

//             table.draw();

//             var value = $("#date")[0].value;
            
//             $("#get_pdf").attr("data-date",value);
           
    
//         })



//         $(document).on("click", "#get_pdf", function() {

//             var date = $(this).data("date");

//             $.ajax({
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 },
//                     url:"{{ url('get-closing-pdf') }}",
//                     type:"GET",
//                     data:{date:date},
//                     success:function(data){
//                         const pdfData = data[0];
//                         // Create a blob object from the base64-encoded data
//                         const byteCharacters = atob(pdfData);
//                         const byteNumbers = new Array(byteCharacters.length);
//                         for (let i = 0; i < byteCharacters.length; i++) {
//                             byteNumbers[i] = byteCharacters.charCodeAt(i);
//                         }
//                         const byteArray = new Uint8Array(byteNumbers);
//                         const blob = new Blob([byteArray], {type: 'application/pdf'});


//                         // Create a URL for the blob object
//                         const url = URL.createObjectURL(blob);

//                         // Create a link element with the URL and click on it to download the PDF file
//                         const link = document.createElement('a');
//                         link.href = url;
//                         link.download = 'test.pdf';
//                         document.body.appendChild(link);
//                         link.click();
//                     }
//             })

            
        

//         })


//         $(document).on("click", ".closing-head-edit", function() {

//             var id = $(this).data("id");
//             $.ajax({
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 },
//                 url: "{{ url('edit-closing') }}",
//                 type: "GET",
//                 data: {
//                     id: id
//                 },
//                 success: function(data) {
                 
//                     $("#date").val(data[0]["date"]);
//                     getHeads(data[0]["head"]);
//                     getHeadLocation(data[0]["location"]);
//                     $("#amount").val(data[0]["amount"]);
//                     $("#hidden_id").val(data[0]["id"]);
//                 }
//             })

//         })


        


//         $(document).on("click", ".closing-head-delete", function() {

//         var id = $(this).data("id");

//         var element = this;

//         $.ajax({
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//             url: "{{ url('delete-closing') }}",
//             type: "GET",
//             data: {
//                 id: id
//             },
//             success: function(data) {
//                $(element).parent().parent().parent().parent().fadeOut();
        
//             }
//         })

//         })






//         $(".toselect-tag").select2();


//         var heads = $("#heads");
//         var locations = $("#locations");




//         function getHeadLocation(edit_location = null) {

//             // heads[0].innerHTML = "";
//             $.ajax({
//                 url: "{{ url('get-head-locations') }}",
//                 type: "GET",
//                 success: function(data) {
//                     $.each(data, function(key, value) {

//                         var create_option = document.createElement("option");

//                         if (edit_location !== null) {

//                             if (edit_location == value["id"]) {
//                                 create_option.innerText = value["location"];
//                                 create_option.value = value["id"];
//                                 create_option.selected = true;
//                                 locations[0].appendChild(create_option);
//                             } else {
//                                 create_option.innerText = value["location"];
//                                 create_option.value = value["id"];

//                                 locations[0].appendChild(create_option);
//                             }

//                         } else {
//                             create_option.innerText = value["location"];
//                             create_option.value = value["id"];
//                             locations[0].appendChild(create_option);
//                         }



//                     });
//                 }
//             })


//         }

//         getHeadLocation();

//         function getHeads(head_id = null) {

//             locations[0].innerHTML = "";
//             $.ajax({
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 },
//                 url: "{{ url('get-heads') }}",
//                 type: "GET",
//                 success: function(data) {

//                     $.each(data, function(key, value) {
//                         var create_option = document.createElement("option");
//                         if(head_id !== null){
//                             create_option.innerText = value["head"];
//                             create_option.value = value["id"];
//                             if(head_id == value["id"]){
//                                 create_option.selected=true;
//                                 heads[0].appendChild(create_option);
//                             }else{
//                                 heads[0].appendChild(create_option);
//                             }
//                         }

//                         var create_option = document.createElement("option");
//                         create_option.innerText = value["head"];
//                         create_option.value = value["id"];
//                         heads[0].appendChild(create_option);


//                     });
//                 }
//             })
//         }

//         getHeads();






//         $('#closing').validate({
//             errorPlacement: function(error, element) {

//                 if (element.attr("name") == "location" || element.attr(
//                         "name") == "head") {

//                     if (element.attr("name") == "location") {
//                         $(".select2-selection")[1].style.border = "1px solid red";
//                     }
//                     if (element.attr("name") == "head") {
//                         $(".select2-selection")[2].style.border = "1px solid red";
//                     }
//                 } else {
//                     element[0].style.border = "1px solid red";
//                 }
//             },
//             rules: {
//                 date: "required",
//                 location: "required",
//                 head: "required",
//                 amount: "required",

//             },

//             submitHandler: function(form) {
//                 var formData = new FormData(form);
//                 $.ajax({
//                     headers: {
//                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                     },
//                     url: "{{ url('insert-closing') }}",
//                     type: "POST",
//                     data: formData,

//                     contentType: false,
//                     cache: false,
//                     processData: false,
//                     success: function(data) {

//                         table.draw();

//                         $("#amount").val("");

//                         // console.log(data);
//                         // if (!$(".alert-danger").hasClass("d-none")) {
//                         //     $(".alert-danger")[0].classList.add("d-none");
//                         // }
//                         // if(data=="saved"){
//                         //     form.reset();
//                         //     $(".file-upload-content")[0].style.display="none";
//                         //     $(".image-upload-wrap")[0].style.display="";
//                         // }
//                         // $(".alert-success")[0].classList.remove("d-none");



//                     },
//                     error: function(data) {
//                         console.log(data);
//                         // if (data.responseJSON.error.length >= 1) {

//                         // $(".alert-success")[0].classList.add("d-none");
//                         // $(".alert-danger")[0].innerText = "Invalid fields";
//                         // $(".alert-danger")[0].classList.remove("d-none");

//                         //this code is for select2 fields for backend validation error
//                         // for (var a = 0; a < $(".select2").length; a++) {
//                         //     if ($(".select2")[a].previousSibling.value == "") {
//                         //         $(".select2-selection")[a].style.border = "1px solid red";
//                         //     }
//                         // }

//                         //this code is for without select2 fields for backend validation error
//                         // var count_errors = data.responseJSON.error.length;
//                         // for (var a = 0; a < count_errors; a++) {
//                         //     var error_text = data.responseJSON.error[a];
//                         //     var find_last_word = error_text.indexOf("field");
//                         //     var name = error_text.substr(4, find_last_word - 5);
//                         //     var create_name = "." + name.replace(" ", "_");
//                         //     var check = $(create_name);
//                         //     check[0].style.cssText = "border:1px solid red";
//                         // }


//                         // }

//                     }

//                 })
//             }
//         });

//         function validate(e){
//           e.style.border="";
           
//         }

//         function validateError(e){
//          e.style.border="";
           
//         }


//     // $(douc)

//     // add-easypaisa-form

//     $(document).on("click", "#add-easypaisa-form", function() {

//         var url = "{{ url('add-easypaisa-form') }}";
//         viewModal(url);

//     })


//     $(document).on("click", "#add-employee-others-forms", function() {

//     var url = "{{ url('add-employee-others-form') }}";
//     viewModal(url);

//     })


//     $(document).on("click", ".return-easypaisa-amount", function() {

//         var id = $(this).data("id");
//         var url = "{{ url('return-amount-form') }}"+"/"+id;
//         viewModal(url);

// })


    </script>
@endsection
