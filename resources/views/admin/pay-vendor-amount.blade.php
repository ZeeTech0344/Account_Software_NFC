
    <div>
        <div class="col-12 d-flex justify-content-center">

            {{-- <div class="col-lg-6 col-sm-12"> --}}

            <div class="col-lg-4 col-sm-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Pay Vendor Amount</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="add-employee-others-forms">Employee/Others</a> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="vendor-form" class="data-form">
                            {{-- <div class="form-group">
                                <label for="exampleFormControlInput1">Date</label>
                                <input type="date" class="form-control" name="date" id="date"
                                    onclick="validate(this)">
                            </div> --}}

                            {{-- <div class="form-group">
                                <label for="exampleFormControlSelect1">Branch</label>
                                <select class="form-control" id="location" style="width:100%;" name="location"
                                    onclick="validate(this)" onchange="selectLocation()">
                                    <option value="">Select Branch</option> --}}
                                    {{-- @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->location }}</option>
                                    @endforeach --}}
                                {{-- </select>
                            </div> --}}

                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Vendors</label>
                                <select class="form-control toselect-tag" id="employee_id" 
                                    name="employee_id" onclick="validate(this)" onchange="getVendorAmount()">
                                    <option value="">Select Vendor</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->employee_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Total Amount</label>
                                <input type="number" class="form-control" readonly id="total_amount" name="total_amount"
                                >
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Amount Paid</label>
                                <input type="number" class="form-control"  id="paid_amount" name="paid_amount"
                                onkeyup="calculateAmount(this)">
                            </div>
                            <div class="form-group" id="convert_to_number">
                             
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Pay Through</label>
                                <select name="pay_through" id="pay_through" class="form-control">
                                    <option value="">Pay Through</option>
                                    <option>Easypaisa</option>
                                    <option>HBL</option>
                                    <option>Locker</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Remaining Amount</label>
                                <input type="number" class="form-control" readonly id="remaining_amount" name="remaining_amount"
                                    onclick="validate(this)">
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Remarks</label>
                                <input type="text" class="form-control"  id="remarks" name="remarks"
                                    onclick="validate(this)">
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
                        <h6 class="m-0 font-weight-bold text-primary">Vendor Payment List</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="get_pdf"><i
                        class="fas fa-download fa-sm text-white-50"></i> PDF</a> --}}
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="generate_vendor_report"><i class="fas fa-download fa-sm text-white-50"></i> Generate
                                Full Report</a> --}}
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
                            <table class="table table-bordered datatable_vendor" id="dataTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        {{-- <th>Date</th> --}}
                                        <th>Date</th>
                                        <th>Employee</th>
                                        <th>Paid Amount</th>
                                        <th>Account</th>
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
//     $("#location").focus();
// }
// });



// $( document ).on( 'keydown', function ( e ) {

// console.log(e.keyCode);
// if ( e.keyCode === 16 ) {
// $("#from_date").focus();
// }
// });



function getVendorAmount(){

    // var vendor_id = e.value;

    var vendor_id = $('#employee_id')[0].value;

    $.ajax({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        url:"{{ url('get-vendor-total-amount') }}",
        type:"POST",
        data:{vendor_id:vendor_id},
        success:function(data){


           var vendor_amount = (data[0][0] !== undefined ? data[0][0]["vendor_amount"] : 0 );
           var vendor_paid_amount = (data[1][0] !== undefined ? data[1][0]["amount"] : 0 );

           var remaining_amount = vendor_amount - vendor_paid_amount;
           $("#total_amount").val(remaining_amount);

        }

    })
  

}


function calculateAmount(e){
    var total_amount = $("#total_amount")[0].value;

    var paid_amount = $("#paid_amount")[0].value;

    $("#remaining_amount").val(total_amount - paid_amount);

    $("#convert_to_number")[0].innerText=numberToWords(e.value);
    

}


function validate (){


}


$(document).on("click", ".pay_now_vendor", function() {

var data = $(this).data("id").split(",");
// data[0] and data[1] we split array through data-id
var url = "{{ url('pay-now-vendor') }}" + "/" + data[0] + "/" + data[1] + "/" + data[2] +  "/" + data[3];
payNowModalBody(url);


})



$(document).on("click", ".edit-vendor-pay-amount", function() {

var id = $(this).data("id");

$.ajax({
    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
    url:"{{ url('edit-pay-vendor-amount') }}",
    type:"POST",
    data:{id:id},
    success:function(data){

        console.log(data);

        $("#employee_id").val(data[0]["employee_id"]);
        $("#select2-employee_id-container")[0].innerText=data[1]["employee_name"];
        getVendorAmount();
        $("#paid_amount").val(data[0]["paid_amount"]);
        $("#pay_through").val(data[0]["account_name"]);
        $("#remarks").val(data[0]["remarks"]);
        $("#hidden_id").val(data[0]["id"]);
        
    }

})


})


        // function calculateAmount(){

        // var amount = $("#amount").val( $("#weight")[0].value * $("#rate")[0].value);

         
        // }



        function selectLocation(get_product_for_edit) {
            getEmployees(get_product_for_edit);
        }


        function getEmployees(get_producut_for_edit) {

            var parent = $("#product_id")[0];
            parent.innerHTML = "";

            var get_branch = $("#location")[0].value;

            console.log(get_producut_for_edit);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('get-vendors') }}",
                type: "POST",
                data: {
                    branch: get_branch
                },
                success: function(data) {

                    $.each(data[0], function(key, value) {
                        var create_option = document.createElement("option");
                        create_option.value = value["id"];
                        create_option.innerText = value["employee_name"];
                        if(value["id"] == get_producut_for_edit){
                            create_option.selected = true;
                        }
                        parent.appendChild(create_option);
                    });
                }
            })
        }




$(document).on("click",".edit-vendor-amount", function(){


var id = $(this).data("id");

$.ajax({
    headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
    url:"{{ url('edit-vendor-detail') }}",
    type:"POST",
    data:{id:id},
    success:function(data){
        $("#location").val(data["branch_id"]);
        $("#employee_id").val(data["employee_id"]);
        $("#product_id").val(data["product_id"]);
        $("#weight").val(data["weight"]);
        $("#measurement").val(data["measurement"]);
        $("#rate").val(data["rate"]);
        $("#amount").val(data["total_amount"]);
        $("#hidden_id").val(data["id"]);
        selectLocation(data["product_id"]);
        // getEmployees();


    }
})


})



$(document).on("click",".delete-vendor-amount", function(){


var id = $(this).data("id");
var element = this;
$.ajax({
    headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
    url:"{{ url('delete-vendor-detail') }}",
    type:"POST",
    data:{id:id},
    success:function(data){
        $(element).parent().parent().parent().parent().fadeOut();


    }
})


})




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
                url: "{{ url('get-vendor-paid-amount-list') }}",
                data: function(d) {
                    d.search_value = $("#search_value").val()
                    d.from_date = $("#from_date").val()
                    d.to_date = $("#to_date").val()
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
                    data: 'paid_amount',
                    name: 'paid_amount'
                },
                {
                    data: 'account_name',
                    name: 'account_name'
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
       var from_date = $("#from_date")[0].value;
       var to_date = $("#to_date")[0].value;

       if(from_date!=="" && to_date!==""){
        pending_table.draw();
       }
     }

        $(".toselect-tag").select2();
        var heads = $("#heads");
        var locations = $("#locations");


        $('#vendor-form').validate({
            errorPlacement: function(error, element) {


                // element[0].style.border = "1px solid red";

            },
            rules: {
                employee_id: "required",
                total_amount: "required",
                paid_amount: "required",
                pay_through: "required",
                remaining_amount: "required",
            },

            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-vendor-paid-amount') }}",
                    type: "POST",
                    data: formData,

                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {

                        pending_table.draw();
                        $('#vendor-form')[0].reset();
                        $("#hidden_id").val("");

                    },
                    error: function(data) {
                        console.log(data);

                    }

                })
            }
        });

        function validate(e) {
            e.style.border = "";

        }

        

        $(document).on("click", "#generate_vendor_report", function() {

            var url = "{{ url('/get-vendor-full-list') }}";
            viewModal(url);

        })



    </script>

