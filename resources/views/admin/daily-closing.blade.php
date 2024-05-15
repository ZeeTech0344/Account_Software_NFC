
    <div>
        <div class="col-12 d-flex justify-content-center" id="main_div_for_controll">

            {{-- <div class="col-lg-6 col-sm-12"> --}}

            <div class="col-lg-6 col-sm-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Closing Form</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-download fa-sm text-white-50"></i> Grand Report
                            </a> --}}


                        </div>
                    </div>
                    <div class="card-body">
                        <form id="closing" class="data-form">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Date</label>
                                <input type="date" class="form-control" name="date" id="date">
                            </div>

                            <div class="form-group d-flex justify-content-end">
                                <a class="btn btn-sm btn-success" id="create-closing" name="create_closing">Create</a>
                            </div>


                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Head</label>
                                <select class="form-control toselect-tag" style="width:100%;" id="heads" name="head">
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Branch</label>
                                <select class="form-control toselect-tag" id="locations" style="width:100%;"
                                    name="location">

                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                    onkeyup="validate(this)">
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Remarks</label>
                                <input type="text" class="form-control" id="remarks" name="remarks" 
                                    onkeyup="validate(this)">
                            </div>


                            <div class="form-group" id="convert_to_number">
                             
                            </div>

                        

                            <div class="form-group d-flex justify-content-end">
                                <input type="submit" value="Add" class="btn btn-primary">
                            </div>
                            <input type="hidden" name="hidden_id" id="hidden_id">
                        </form>

                    </div>

                </div>
            </div>

            <div class="col-lg-6 col-sm-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Closing List</h6>
                        <div>
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="get_sadqa_full_report"><i class="fas fa-download fa-sm text-white-50"></i> Sadqa_R
                            </a>

                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="get_pdf" ><i class="fas fa-download fa-sm text-white-50"></i> Closing PDF</a> --}}

                             <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="get_pdf" ><i class="fas fa-download fa-sm text-white-50"></i> Closing PDF</a>

                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="generate_closing_report"><i class="fas fa-download fa-sm text-white-50"></i> Closing_R</a>
                                {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="generate_closing_report"><i class="fas fa-download fa-sm text-white-50"></i> Grand_R</a> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="p-2">
                            <input type="text" id="search_closing" placeholder="Search........" onchange="checkValues(this)" class="form-control" >
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered datatable_closing" id="dataTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Head</th>
                                        <th>Locaton</th>
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

    <script>


$( document ).on( 'keydown', function ( e ) {


if ( e.keyCode === 17 ) {
    $("#date").focus();
}
});



$( document ).on( 'keydown', function ( e ) {


if ( e.keyCode === 16 ) {
$("#search_closing").focus();
}
});


        //closing table

        var table = $('.datatable_closing').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            paging: false,
            "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('get-closing-list') }}",
                data: function(d) {
                    d.date = $("#date").val()
                }
            },
            columns: [

                {
                    data: 'head',
                    name: 'head'
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
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],

            success: function(data) {

            }
        });


        $(document).on("click", "#create-closing", function() {

            table.draw();

            var value = $("#date")[0].value;

            $("#get_pdf").attr("data-date", value);


        })



        $(document).on("click", "#get_pdf", function() {

            var date = $("#date")[0].value;

            console.log(date);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('get-closing-pdf') }}",
                type: "GET",
                data: {
                    date: date
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
                    link.download = 'test.pdf';
                    document.body.appendChild(link);
                    link.click();
                }
            })




        })


        $(document).on("click", ".closing-head-edit", function() {

            var id = $(this).data("id");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-closing') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {

                    $("#date").val(data[0]["date"]);
                    getHeads(data[0]["head"]);
                    getHeadLocation(data[0]["location"]);
                    $("#amount").val(data[0]["amount"]);
                    $("#hidden_id").val(data[0]["id"]);
                }
            })

        })





        $(document).on("click", ".closing-head-delete", function() {

            var id = $(this).data("id");

            var element = this;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('delete-closing') }}",
                type: "GET",
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


        var user_location = "<?php  echo Auth::User()->user_branch ?>";
        var user_type = "<?php  echo Auth::User()->user_type ?>";

        console.log(user_location);

        function getHeadLocation(edit_location = null) {

            locations[0].innerHTML = "";
            $.ajax({
                url: "{{ url('get-head-locations') }}",
                type: "GET",
                success: function(data) {
                    $.each(data, function(key, value) {

                        var create_option = document.createElement("option");

                        if (edit_location !== null) {

                            if (edit_location == value["id"]) {
                                create_option.innerText = value["location"];
                                create_option.value = value["id"];
                                create_option.selected = true;
                                locations[0].appendChild(create_option);
                            } else {
                                create_option.innerText = value["location"];
                                create_option.value = value["id"];

                                locations[0].appendChild(create_option);
                            }

                        } else {
                            create_option.innerText = value["location"];
                            create_option.value = value["id"];
                            if(value["location"] == user_location && user_location !== "Head Office"){
                                // create_option.selected = true;
                                locations[0].appendChild(create_option);
                            }else if(user_location == "Head Office"){
                                locations[0].appendChild(create_option);
                            }
                           
                        }



                    });
                }
            })


        }

        getHeadLocation();



        function getHeads(head_id = null) {

            heads[0].innerHTML = "";


            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('get-heads') }}",
                type: "GET",
                success: function(data) {

                    $.each(data, function(key, value) {
                        var create_option = document.createElement("option");
                        create_option.innerText = value["head"];
                        create_option.value = value["id"];
                        if (head_id == value["id"]) {
                            create_option.selected = true;
                        } else {
                            create_option.selected = false;
                        }
                        heads[0].appendChild(create_option);


                        // if(head_id !== null){


                        // if(head_id == value["id"]){
                        //     create_option.selected=true;
                        //     // heads[0].appendChild(create_option);
                        // }else{

                        // }
                        // }

                        // var create_option = document.createElement("option");
                        // create_option.innerText = value["head"];
                        // create_option.value = value["id"];
                        // heads[0].appendChild(create_option);


                    });
                }
            })
        }

        getHeads();






        $('#closing').validate({
            errorPlacement: function(error, element) {

                // if (element.attr("name") == "location" || element.attr(
                //         "name") == "head") {

                //     if (element.attr("name") == "location") {
                //         $(".select2-selection")[1].style.border = "1px solid red";
                //     }
                //     if (element.attr("name") == "head") {
                //         $(".select2-selection")[2].style.border = "1px solid red";
                //     }
                // } else {
                //     element[0].style.border = "1px solid red";
                // }
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
                    url: "{{ url('insert-closing') }}",
                    type: "POST",
                    data: formData,

                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {

                        table.draw();
                        $("#amount").val("");
                        $("#remarks").val("");
                        $("#hidden_id").val("");

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

        function validate(e) {
            e.style.border = "";
            $("#convert_to_number")[0].innerText=numberToWords(e.value);
        }

        function validateError(e) {
            e.style.border = "";

        }


        // $(douc)

        // add-easypaisa-form

      

      


        $(document).on("click", "#get-saqah-form", function() {

            var url = "{{ url('add-sadqah') }}";
            viewModal(url);

        })


        $(document).on("click", "#generate_closing_report", function() {
            var url = "{{ url('get-full-report-of-closing-view') }}";
            viewModal(url);

        })

        $(document).on("click", "#get-saqah-form", function() {

            var url = "{{ url('get-sadqa-report') }}";
            viewModal(url);

        })







        $(document).on("click", "#get_sadqa_full_report", function() {

            var url = "{{ url('get-sadqa-report') }}";
            viewModal(url);

            // $.ajax({
            //     url: "{{ url('get-sadqa-report') }}",
            //     type: "GET",
            
            //     success: function(data) {
            //         const pdfData = data[0];
            //         // Create a blob object from the base64-encoded data
            //         const byteCharacters = atob(pdfData);
            //         const byteNumbers = new Array(byteCharacters.length);
            //         for (let i = 0; i < byteCharacters.length; i++) {
            //             byteNumbers[i] = byteCharacters.charCodeAt(i);
            //         }
            //         const byteArray = new Uint8Array(byteNumbers);
            //         const blob = new Blob([byteArray], {
            //             type: 'application/pdf'
            //         });


            //         // Create a URL for the blob object
            //         const url = URL.createObjectURL(blob);

            //         // Create a link element with the URL and click on it to download the PDF file
            //         const link = document.createElement('a');
            //         link.href = url;
            //         link.download = 'test.pdf';
            //         document.body.appendChild(link);
            //         link.click();
            //     }
            // })

        })



        $("#search_closing").keyup(function () {

var value = this.value.toLowerCase().trim();

$(".datatable_closing tr").each(function (index) {
    if (!index) return;
    $(this).find("td").each(function () {
        var id = $(this).text().toLowerCase().trim();
        var not_found = (id.indexOf(value) == -1);
        $(this).closest('tr').toggle(!not_found);
        return not_found;
    });
});
});



        //     $(document).ready(function() {
        //     var currentDate = new Date();
        // //   currentDate.setDate(currentDate.getDate() - 1);
        // currentDate.setDate(currentDate.getDate());
        //  var pastDay = currentDate.toISOString().split('T')[0];
        //   $('#date').attr('min', pastDay);
        //   $('#date').attr('max', pastDay);

        //   console.log(currentDate);
        // });


        function isMobileView() {
  // Set your desired mobile breakpoint here (e.g., 768 pixels)
  const mobileBreakpoint = 768;
  return window.innerWidth <= mobileBreakpoint;
}

function removeClassForMobile() {
  const element = document.querySelector('#main_div_for_controll');
  
  if (isMobileView()) {
    element.classList.remove('d-flex');
  }
}

// Call the function when the page loads or whenever you need to check for the class removal.
removeClassForMobile();
    </script>
