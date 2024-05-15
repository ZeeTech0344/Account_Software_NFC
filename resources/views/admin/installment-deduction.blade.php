
    <div>
        <div class="col-12 d-flex justify-content-center">

            {{-- <div class="col-lg-6 col-sm-12"> --}}

            <div class="col-lg-4 col-sm-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Installment (Pay To)</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="add-employee-others-forms">Employee/Others</a> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="sadqa-form" class="data-form">
                           
                        
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Total Installment Amount</label>
                                <input type="text" class="form-control" id="installment_amount" value="{{  ($installment[0]->sum - $pay_installment[0]->sum) + 850000 }}" disabled name="installment_amount">
                                <input type="hidden" class="form-control" id="installment_amount_hidden" value="{{ ($installment[0]->sum - $pay_installment[0]->sum) + 850000 }}"  name="installment_amount_hidden">
                                
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Installment Pay Amount</label>
                                <input type="number" class="form-control" id="pay_installment_amount" name="pay_installment_amount" onkeyup="calculate()">
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Purpose</label>
                                <input type="input" class="form-control"  id="purpose" name="purpose">
                            </div>

                           

                            

                            <div class="form-group d-flex justify-content-end">
                                <input type="submit" value="Pay" class="btn btn-primary">
                            </div>
                            <input type="hidden" name="hidden_id" id="hidden_id">
                        </form>

                    </div>

                </div>
            </div>

            <div class="col-lg-8 col-sm-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Installment List (Pay To)</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="get_pdf"><i
                        class="fas fa-download fa-sm text-white-50"></i> PDF</a> --}}

                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"
                                id="view_install_grand_report" >View Grand Report
                                </a>

                             <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="view_install_report" >View Installment Report
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
                                        <th>Purpose</th>
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



function calculate(){

    var sadqa_amount_hidden = $("#installment_amount_hidden")[0].value;

    var pay_sadqa_amount = $("#pay_installment_amount")[0].value;

    var sadqa_amount = $("#installment_amount").val(sadqa_amount_hidden - pay_sadqa_amount);

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
                url: "{{ url('get-installment-list') }}",
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
                    data: 'pay_installment',
                    name: 'pay_installment'
                },
                {
                    data: 'purpose',
                    name: 'purpose'
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


        $("#view_install_report").click(function(){

            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;

            if(from_date !== '' && to_date !== '' ){
                var url = "{{ url('get-installment-report') }}" + "/" + from_date + "/" + to_date;
                viewModal(url);
            }

          

        })


        $("#view_install_grand_report").click(function(){

            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;

            if(from_date !== '' && to_date !== '' ){
               
                var url = "{{ url('view-install-grand-report') }}" + "/" + from_date + "/" + to_date;
                viewModal(url);
            }

        })

    



        

        

        $('#sadqa-form').validate({
            errorPlacement: function(error, element) {
                // element[0].style.border = "1px solid red";
            },
            rules: {
                pay_installment_amount: "required",
                purpose: "required",
            },

            submitHandler: function(form) {

                if (confirm('Pay Intallment Amount! Are you sure')) {

                

                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('pay-installment-insert') }}",
                    type: "POST",
                    data: formData,

                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {

                        $('#sadqa-form')[0].reset();

                        // window.location.reload();

                        $("#hidden_id").val("");
                        
                        jQuery('#installment').click();


                    },
                    error: function(data) {
                        console.log(data);
                       

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

      

        $(document).ready(function() {
            var currentDate = new Date();
            //   currentDate.setDate(currentDate.getDate() - 1);
            currentDate.setDate(currentDate.getDate());
            var pastDay = currentDate.toISOString().split('T')[0];
            $('#date').attr('min', pastDay);
            $('#date').attr('max', pastDay);

            console.log(currentDate);
        });


        $(document).on("click", ".edit_installment_amount", function() {
        
        var id = $(this).data("id");
        $.ajax({
            url:"{{ url('edit-installment') }}",
            type:"GET",
            data:{id, id},
            success:function(data){
               

                var installment_amount = $("#installment_amount")[0].value;
                var installment_amount_hidden =  $("#installment_amount_hidden")[0].value;

                var installment_amount_get = "<?php echo  ($installment[0]->sum - $pay_installment[0]->sum) + 850000  ?>";

                console.log(installment_amount_get);

                $("#installment_amount").val( parseInt(installment_amount_get) + parseInt(data["pay_installment"]) );
                $("#installment_amount_hidden").val( parseInt(installment_amount_get) + parseInt(data["pay_installment"]) );

                $("#pay_installment_amount").val(data["pay_installment"]);
                $("#purpose").val(data["purpose"]);
                $("#hidden_id").val(data["id"]);

            }
        })

        })




        $(document).on("click", ".delete_installment_amount", function() {
            var id = $(this).data("id");

            var element = this;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('delete-installment') }}",
                type: "get",
                data: {
                    id: id
                },
                success: function(data) {
                    
                    $(element).parent().parent().parent().parent().fadeOut();
                    jQuery('#installment').click();

                }
            })

        })



        

    </script>
