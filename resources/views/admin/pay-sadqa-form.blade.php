
    <div>
        <div class="col-12 d-flex justify-content-center">

            {{-- <div class="col-lg-6 col-sm-12"> --}}

            <div class="col-lg-4 col-sm-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Sadqa</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="add-employee-others-forms">Employee/Others</a> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="sadqa-form" class="data-form">
                           
                        
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Total Sadqa Amount</label>
                                <input type="text" class="form-control" id="sadqa_amount" value="{{   $calculate_total + 161670 }}" disabled name="sadqa_amount">
                                <input type="hidden" class="form-control" id="sadqa_amount_hidden" value="{{ $calculate_total + 161670 }}"  name="sadqa_amount_hidden">
                                
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Sadqa Pay Amount</label>
                                <input type="number" class="form-control" id="pay_sadqa_amount" name="pay_sadqa_amount" onkeyup="calculate(this)">
                            </div>
                            <div class="form-group" id="convert_to_number">
                             
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Pay To</label>
                                <input type="input" class="form-control"  id="pay_to" name="pay_to">
                            </div>

                           

                            

                            <div class="form-group d-flex justify-content-end">
                                <input type="submit" value="Pay" class="btn btn-primary">
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
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="get_pdf"><i
                        class="fas fa-download fa-sm text-white-50"></i> PDF</a> --}}
                             <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="view_sadqa_report" >View Sadqa Pay Report
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
                                        {{-- <th>Date</th> --}}
                                        <th>Date</th>
                                        <th>Paid Amount</th>
                                        <th>Pay To</th>
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

                    console.log(data);

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
                url: "{{ url('get-sadqa-list') }}",
                data: function(d) {
                    d.to_date = $("#to_date").val()
                    d.from_date = $("#from_date").val()
                    d.search_value = $("#search_value").val()
                }
            },
            columns: [

                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'pay_sadqa_amount',
                    name: 'pay_sadqa_amount'
                },
                {
                    data: 'pay_to',
                    name: 'pay_to'
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



        $(document).on("click", "#view_sadqa_report", function() {

            var url = "{{ url('view-sadqa-report') }}";
            viewModal(url);

        })



        $(document).on("click", ".edit-sadqa-amount", function() {

            var id = $(this).data("id");

            console.log(id);

            $.ajax({
                url: "{{ url('edit-pay-sadqa') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {

                    $("#pay_sadqa_amount")[0].value = data["pay_sadqa_amount"];
                    $("#pay_to")[0].value = data["pay_to"];
                    $("#hidden_id")[0].value = data["id"];
                
                }
            })


        })



        // $(document).on("click", ".delete-pending-amount", function() {
        //     var id = $(this).data("id");

        //     var element = this;
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: "{{ url('delete-pending') }}",
        //         type: "get",
        //         data: {
        //             id: id
        //         },
        //         success: function(data) {
        //             $(element).parent().parent().parent().parent().fadeOut();

        //         }
        //     })

        // })

        

        $('#sadqa-form').validate({
            errorPlacement: function(error, element) {
                // element[0].style.border = "1px solid red";
            },
            rules: {
                pay_sadqa_amount: "required",
                pay_to: "required",
            },

            submitHandler: function(form) {

                if (confirm('Paid Sadqa! Are you sure')) {

                

                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('pay-sadqa-insert') }}",
                    type: "POST",
                    data: formData,

                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {

                        pending_table.draw();

                        $('#sadqa-form')[0].reset();

                        $("#hidden_id").val("");
                     
                      
                    },
                    error: function(data) {
                       
                    }

                })
            }
        }
        });

        function validate(e) {
            e.style.border = "";

        }

       


      


        $(document).on("click", ".pay_now_pending", function() {

            var data = $(this).data("id").split(",");
            // data[0] and data[1] we split array through data-id
            var url = "{{ url('pay-now') }}" + "/" + data[0] + "/" + data[1] + "/" + data[2] +  "/" + data[3];
            payNowModalBody(url);
           
            
        })

    

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

