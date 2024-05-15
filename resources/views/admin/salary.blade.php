
    <div>
        <div class="col-12 d-flex justify-content-center">

            {{-- <div class="col-lg-6 col-sm-12"> --}}

            <div class="col-lg-12 col-sm-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Generate Salary</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="generate-salary-report"> Generate Report </a> --}}
                             <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"
                                id="get-salary-detail">Grand Salary Detail</a>

                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"
                                id="get-paid-report">Salary Paid</a>
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"
                                id="get-unpaid-report">Salary Unpaid</a>
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="get-paid-pdf"> Get PDF</a>

                        </div>
                    </div>
                    <div class="card-body">
                        <div>
                            {{-- <form method="post" action="{{ url('get-pdf-report-of-easypaisa-amount') }}"> --}}
                            <div class="row p-2 d-flex justify-content-center">
                                <div class="col col-4">
                                    <input type="month" id="month" name="month" class="form-control">
                                </div>


                                <div>
                                    <input type="button" value="Generate" class="btn btn-primary" id="generate-salary">
                                    <input type="button" value="Reset" class="btn  btn-secondary" onclick="reset()">
                                </div>
                            </div>
                            {{-- </form> --}}

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    {{-- <div class="card shadow mb-4"> --}}
    {{-- <div class="card-header py-3 d-flex justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Employee Salary</h6> --}}
    {{-- <div>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="get-saqah-form"> Add Sadqah </a>
            
    </div> --}}
    {{-- </div> --}}
    {{-- <div class="card-body"> --}}
    <div class=" p-2 d-flex justify-content-end">

        <input type="text" id="search" name="search" placeholder="Search Employee......."
            onchange="checkValues(this)" class="form-control w-25">

    </div>
    {{-- <div> --}}

    <div class="table-responsive">
        <table class="table table-bordered table_employee_other" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Employee#</th>
                    <th>Name</th>
                    <th>Post</th>
                    <th>Branch</th>
                    <th>Basic_Salary</th>
                    
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>

    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </div> --}}

    <script>

       

        var employee_salary_table = $('.table_employee_other').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            paging: false,
            order: false,
            // "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('get-data-of-employee-salary') }}",
                data: function(d) {
                    d.month = $("#month").val()
                }
            },
            columns: [{
                    data: 'employee_no',
                    name: 'employee_no'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'post',
                    name: 'post'
                },
                {
                    data: 'branch',
                    name: 'branch'
                },
                {
                    data: 'salary',
                    name: 'salary'
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




        $("#generate-salary").click(function(){

            employee_salary_table.draw();
            
        })

        function reset(){
            $("#month").val('');
            employee_salary_table.draw();
        }


        $("#get-paid-report").click(function(){

            var month = $("#month")[0].value;
            if (month !== "") {
                console.log("yes");
                var url = "{{ url('get-paid-salary') }}" + "/" + month;
                viewModal(url);
            }
            
        })


        $("#get-unpaid-report").click(function(){

        var month = $("#month")[0].value;
        if (month !== "") {
            var url = "{{ url('get-salary-upaid-detail') }}" + "/" + month;
            viewModal(url);
        }

        })



          $("#get-salary-detail").click(function(){
            var month = $("#month")[0].value;
            if (month !== "") {
                console.log("yes");
                var url = "{{ url('get-salary-detail') }}" + "/" + month;
                viewModal(url);
            }
          })

            


      





        

            $("#get-paid-pdf").click(function(){

                
            var salary_month = $("#month")[0].value;
            
            if (salary_month !== "") {

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('get-salary-pdf') }}",
                    type: "POST",
                    data: {
                        month: salary_month,
                    },
                    success: function(data) {
                        const pdfData = data[0];
                        // Create a blob object from the base64-encoded data
                        const byteCharacters = atob(pdfData);
                        const byteNumbers = new Array(byteCharacters.length);
                        for (let i = 0; i < byteCharacters.length; i++) {
                            byteNumbers[i] = byteCharacters.charCodeAt(i);
                        }
                        const byteArray = new Uint8Array(byteNumbers);
                        const blob = new Blob([byteArray], {
                            type: 'application/pdf'
                        });


                        // Create a URL for the blob object
                        const url = URL.createObjectURL(blob);

                        // Create a link element with the URL and click on it to download the PDF file
                        const link = document.createElement('a');
                        link.href = url;
                        link.download = 'easypaisa_paid_detail_list.pdf';
                        document.body.appendChild(link);
                        link.click();
                    }
                })

            }

            
            })

        

        
       

        $(document).on("click", ".pay_now_salary", function() {
            
            var get_data = $(this).data("id").split(",");
            if(get_data[6] !== "" ){
            var url = "{{ url('pay-now-salary') }}" + "/" + get_data[0] + "/" + get_data[1] + "/" + get_data[2] + "/" + get_data[3] + "/" + get_data[4] + "/" + get_data[5] + "/" + get_data[6] + "/" + get_data[7];
            payNowModalBody(url);
            }else{
                alert("Please updated joining date of this employee!");
            }
            

        })



        // $(document).ready(function(){
        // $("#search").on("keyup", function() {
        //     var value = $(this).val().toLowerCase();
        //     $(".table_employee_other tr").filter(function() {
        //     $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        //     });
        // });
        // });


        $("#search").keyup(function() {

            var value = this.value.toLowerCase().trim();

            $(".table_employee_other tr").each(function(index) {
                if (!index) return;
                $(this).find("td").each(function() {
                    var id = $(this).text().toLowerCase().trim();
                    var not_found = (id.indexOf(value) == -1);
                    $(this).closest('tr').toggle(!not_found);
                    return not_found;
                });
            });
        });











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






        // $(".toselect-tag").select2();
        // var heads = $("#heads");
        // var locations = $("#locations");




        // function getHeadLocation(edit_location = null) {

        //     // locations[0].innerHTML = "";
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

        //     heads[0].innerHTML = "";
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






        // $('#closing').validate({
        //     errorPlacement: function(error, element) {

        //         // if (element.attr("name") == "location" || element.attr(
        //         //         "name") == "head") {

        //         //     if (element.attr("name") == "location") {
        //         //         $(".select2-selection")[1].style.border = "1px solid red";
        //         //     }
        //         //     if (element.attr("name") == "head") {
        //         //         $(".select2-selection")[2].style.border = "1px solid red";
        //         //     }
        //         // } else {
        //         //     element[0].style.border = "1px solid red";
        //         // }
        //     },
        //     rules: {
        //         date: "required",
        //         location: "required",
        //         head: "required",
        //         amount: "required",

        //     },

        //     submitHandler: function(form) {
        //         var formData = new FormData(form);
        //         $.ajax({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             url: "{{ url('insert-closing') }}",
        //             type: "POST",
        //             data: formData,

        //             contentType: false,
        //             cache: false,
        //             processData: false,
        //             success: function(data) {

        //                 table.draw();

        //                 $("#amount").val("");

        //                 // console.log(data);
        //                 // if (!$(".alert-danger").hasClass("d-none")) {
        //                 //     $(".alert-danger")[0].classList.add("d-none");
        //                 // }
        //                 // if(data=="saved"){
        //                 //     form.reset();
        //                 //     $(".file-upload-content")[0].style.display="none";
        //                 //     $(".image-upload-wrap")[0].style.display="";
        //                 // }
        //                 // $(".alert-success")[0].classList.remove("d-none");



        //             },
        //             error: function(data) {
        //                 console.log(data);
        //                 // if (data.responseJSON.error.length >= 1) {

        //                 // $(".alert-success")[0].classList.add("d-none");
        //                 // $(".alert-danger")[0].innerText = "Invalid fields";
        //                 // $(".alert-danger")[0].classList.remove("d-none");

        //                 //this code is for select2 fields for backend validation error
        //                 // for (var a = 0; a < $(".select2").length; a++) {
        //                 //     if ($(".select2")[a].previousSibling.value == "") {
        //                 //         $(".select2-selection")[a].style.border = "1px solid red";
        //                 //     }
        //                 // }

        //                 //this code is for without select2 fields for backend validation error
        //                 // var count_errors = data.responseJSON.error.length;
        //                 // for (var a = 0; a < count_errors; a++) {
        //                 //     var error_text = data.responseJSON.error[a];
        //                 //     var find_last_word = error_text.indexOf("field");
        //                 //     var name = error_text.substr(4, find_last_word - 5);
        //                 //     var create_name = "." + name.replace(" ", "_");
        //                 //     var check = $(create_name);
        //                 //     check[0].style.cssText = "border:1px solid red";
        //                 // }


        //                 // }

        //             }

        //         })
        //     }
        // });

        // function validate(e){
        //   e.style.border="";

        // }

        // function validateError(e){
        //  e.style.border="";

        // }


        // $(douc)

        // add-easypaisa-form


        // $(document).on("click", "#generate-salary-report", function() {

        //     var url = "{{ url('get-salary-report-view') }}";
        //     viewModal(url);

        // })


        // $(document).on("click", "#add-employee-others-forms", function() {

        // var url = "{{ url('add-employee-others-form') }}";
        // viewModal(url);

        // })


        // $(document).on("click", "#get-saqah-form", function() {

        // var url = "{{ url('add-sadqah') }}";
        // viewModal(url);

        // })



        // $(document).on("click", "#generate_closing_report", function() {

        //     console.log("yes");

        //     var url = "{{ url('get-full-report-of-closing-view') }}";
        //     viewModal(url);

        //     })


        //     // $(document).ready(function() {
        //     var currentDate = new Date();
        // //   currentDate.setDate(currentDate.getDate() - 1);
        // currentDate.setDate(currentDate.getDate());
        //  var pastDay = currentDate.toISOString().split('T')[0];
        //   $('#date').attr('min', pastDay);
        //   $('#date').attr('max', pastDay);

        //   console.log(currentDate);
        // });
    </script>

