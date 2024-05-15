
    <div class="col-lg-12 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Employee/Others List</h6>
            </div>
            <div class="card-body">
                <div>
                    {{-- <form method="post" action="{{ url('get-pdf-report-of-easypaisa-amount') }}"> --}}
                    <div class="row p-2">
                        {{-- <div class="col">
            @csrf
            <input type="date" id="from_date" name="from_date" class="form-control" onchange="checkVal(this)">
        </div>
        <div class="col">
            <input type="date" id="to_date" name="to_date" class="form-control" onchange="checkVal(this)">
        </div> --}}

                        <div class="col">
                            <select class="form-control" onchange="whenChageVal(this)" name="type_of_employee"
                                id="type_of_employee">
                                <option value="">Select Type</option>
                                <option>Employee</option>
                                <option>Patty</option>
                                <option>Vendors</option>
                                <option>Others</option>
                            </select>
                        </div>

                        <div class="col">
                            <select class="form-control" onchange="whenChageVal(this)" name="branch_of_employee"
                                id="branch_of_employee">
                                <option value="">Select Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->location }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col">
                            <select name="employee_post" id="employee_post"  onchange="whenChageVal(this)" class="form-control">
                                <option value="">Select Post</option>
                                <option>Manager</option>
                                <option>Accountant</option>
                                <option>Software Engineer</option>
                                <option>Chef</option>
                                <option>Kitchen Helper</option>
                                <option>Kitchen Dishwasher</option>
                                <option>Store Keeper</option>
                                <option>Store Helper</option>
                                <option>Cook</option>
                                <option>Cashier</option>
                                <option>Waiter</option>
                                <option>Supervisor</option>
                                <option>Rider</option>
                            </select>
                        </div>



                        <div>
                            <input type="button" value="Reset" class="btn  btn-secondary" onclick="resetEmployeeRecord()">
                            <input type="button" value="PDF" class="btn btn-danger" id="get_all_record_pdf">
                            <input type="button" value="View" class="btn btn-primary" id="get_all_record_view">
                        </div>
                    </div>
                    {{-- </form> --}}



    <div class="table-responsive">
       
        <table class="table table-bordered table_employee_other" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Branch</th>
                    <th>CNIC</th>
                    <th>Phone#</th>
                    <th>Father_CNIC</th>
                    <th>Father#</th>
                    <th>D.O.J</th>
                    <th>D.O.L</th>
                    <th>Basic Salary</th>
                    {{-- <th>Action</th> --}}
                </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>
    
</div>

</div>
</div>
</div>



    <script>


// function countDays(startDate, endDate) {
//   // Convert the start and end dates to milliseconds
//   const start = new Date(startDate).getTime();
//   const end = new Date(endDate).getTime();

//   // Calculate the difference in milliseconds
//   const diff = end - start;

//   // Convert the difference to days
//   const days = Math.floor(diff / (1000 * 60 * 60 * 24));

//   return days;
// }

// // Usage example
// const startDate = '2023-06-25';
// const endDate = '2023-07-05';

// const daysCount = countDays(startDate, endDate);
// console.log(daysCount); // Output: 10




        
        var employee_table_record = $('.table_employee_other').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            // paging: false,
            // "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('get-data-of-employee') }}",
                data: function(d) {
                    d.type_of_employee = $("#type_of_employee").val()
                    d.branch_of_employee = $("#branch_of_employee").val()
                    d.employee_post = $("#employee_post").val()
                }
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'branch',
                    name: 'branch'
                },
                {
                    data: 'cnic',
                    name: 'cnic'
                },
                {
                    data: 'phone_no',
                    name: 'phone_no'
                },
                {
                    data: 'father_cnic',
                    name: 'father_cnic'
                },
                {
                    data: 'father_phone_no',
                    name: 'father_phone_no'
                },
                {
                    data: 'doj',
                    name: 'doj'
                },
                {
                    data: 'dol',
                    name: 'dol'
                },
               
                {
                    data: 'basic_salary',
                    name: 'basic_salary'
                },
                // {
                //     data: 'action',
                //     name: 'action',
                //     orderable: false,
                //     searchable: false
                // },
            ],

            success: function(data) {
                console.log(data);
            }
        });





        $(document).on("click", ".edit_employee_others", function() {

            var id = $(this).data("id");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-employee-others') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {


                    $("#employee_name").val(data["employee_name"]);
                    $("#employee_type").val(data["employee_type"]);
                    $("#employee_post").val(data["employee_post"]);
                    $("#cnic").val(data["cnic"]);
                    $("#basic_sallary").val(data["basic_sallary"]);
                    $("#employee_branch").val(data["employee_branch"])
                    $("#employee_status").val(data["employee_status"])
                    $("#employee_hidden_id").val(data["id"])

                    $("#close-view")[0].click();

                    // $("#date").val(data[0]["date"]);
                    // getHeads(data[0]["head"]);
                    // getHeadLocation(data[0]["location"]);
                    // $("#amount").val(data[0]["amount"]);
                    // $("#hidden_id").val(data[0]["id"]);
                }
            })

        })


        $(document).on("click", "#get_all_record_view", function() {

        var url = "{{ url('view-employee-report') }}";
        viewModal(url);

        })


        function whenChageVal(e) {
            // var branch_of_employee = $("#branch_of_employee")[0].value;
            // var type_of_employee = $("#type_of_employee")[0].value;
            // if (branch_of_employee !== "" && type_of_employee !== "") {
                employee_table_record.draw();
            // }
        }

        function resetEmployeeRecord() {
            $("#branch_of_employee").val("");
            $("#type_of_employee").val("");
            employee_table_record.draw();
        }



        $(document).on("click", "#get_all_record_pdf", function() {

            var branch_of_employee = $("#branch_of_employee")[0].value;
            var type_of_employee = $("#type_of_employee")[0].value;
            var employee_post = $("#employee_post")[0].value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('get-pdf-of-employee-others') }}",
                type: "GET",
                data: {
                    branch_of_employee: branch_of_employee,
                    type_of_employee: type_of_employee,
                    employee_post : employee_post
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
    </script>

