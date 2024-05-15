



<div>
    <div class="col-12 d-flex justify-content-center">

        <div class="col-lg-12 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">HBL Account</h6>
                    {{-- <div>
            
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="add-employee-others-forms">Employee/Others</a>
                        </div> --}}
                </div>
                <div class="card-body">

<div>
    {{-- <form method="post" action="{{ url('get-pdf-report-of-easypaisa-amount') }}"> --}}
    <div class="row p-2">
        <div class="col">
           
            <input type="date" id="from_date" name="from_date" class="form-control" onchange="checkVal(this)">
        </div>
        <div class="col">
            <input type="date" id="to_date" name="to_date" class="form-control" onchange="checkVal(this)">
        </div>
       
        <div class="col">
            <select class="form-control" onchange="checkVal(this)" name="type" id="type">
                <option value="">Select Type</option>
                <option>Advance</option>
                <!-- <option>Patty</option> -->
                <option>Salary</option>
                <option>Pending</option>
                <option>Vendor</option>
                <option>Rides</option>
                <option>Others</option>
                <option>Fuel</option>
            </select>
        </div>

        <div class="col">
            <select class="form-control toselect-tag-employee" onchange="checkVal(this)" name="employee_others" id="employee_others">
                <option value="">Select Employees/Other</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->employee_name }}</option>
                @endforeach
            </select>
        </div>

      



        <div>
            <input type="button"  value="Reset" class="btn  btn-secondary" onclick="reset()">
            <input type="button"  value="PDF" class="btn btn-danger" id="get_hbl_pdf">
            <input type="button"  value="View" class="btn btn-primary" id="get_hbl_view">
        </div>
</div>
{{-- </form> --}}
    
</div>
 <div class="table-responsive">
    <div class="mb-2 mt-2 d-flex justify-content-end">
        <input type="text" class="form-control w-25" id="search_value" name="search_value" placeholder="Type here to search........">
    </div>
                <table class="table table-bordered datatable_paid_full_list" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Paid_Date</th>
                            <th>Paid_To</th>
                            <th>Purpose</th>
                            <th>status</th>
                            <th>amount</th>
                            <th>Remarks</th>
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
    </div>


<script>

$(".toselect-tag-employee").select2();


    var easypaisa_full_report_table = $('.datatable_paid_full_list').DataTable({
         processing: true,
         serverSide: true,
         searching: false,
         "language": {
             "infoFiltered": ""
         },

         ajax: {
             url: "{{ url('get-report-of-hbl-amount') }}",
             data: function(d) {
                 d.from_date = $("#from_date").val()
                 d.to_date = $("#to_date").val()
                 d.type = $("#type").val()
                 d.search_value = $("#search_value").val()
                 d.employee_others = $("#employee_others").val()
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
            //  {
            //      data: 'action',
            //      name: 'action',
            //      orderable: false,
            //      searchable: false
            //  },

         ],

         success: function(data) {
           
         }
     });




     $("#search_value").on('keyup', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                easypaisa_full_report_table.draw();
            }
        });


     function checkVal(e){
       var from_date = $("#from_date")[0].value;
       var to_date = $("#to_date")[0].value;
       var type = $("#type")[0].value;
       var search_value = $("#search_value")[0].value;
       var employee_others = $("#employee_others")[0].value;

       if(from_date!=="" && to_date!==""){
        easypaisa_full_report_table.draw();
       }
     }


     function reset(){
        var from_date = $("#from_date").val("");
       var to_date = $("#to_date").val("");
       var type = $("#type").val("");
       var search_value = $("#search_value").val("");
       var employee_others = $("#employee_others").val("")
       easypaisa_full_report_table.draw();
     }



     $("#get_hbl_pdf").click(function(){

        
            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;
            var type = $("#type")[0].value;
            var search_value = $("#search_value")[0].value;
            var employee_others = $("#employee_others")[0].value;
            if(from_date!=="" && to_date!=="")

            {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
                url:"{{ url('hbl-full-report-second-view-pdf') }}",
                type:"get",
                data:{from_date:from_date,to_date:to_date,type:type,employee_others:employee_others },
                success:function(data){
                    const pdfData = data[0];
                    // Create a blob object from the base64-encoded data
                    const byteCharacters = atob(pdfData);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const blob = new Blob([byteArray], {type: 'application/pdf'});


                    // Create a URL for the blob object
                    const url = URL.createObjectURL(blob);

                    // Create a link element with the URL and click on it to download the PDF file
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = 'hbl_paid_detail_list.pdf';
                    document.body.appendChild(link);
                    link.click();
                }
        })

        }

     })






// $(document).on("click", "#get_hbl_view", function() {

// var from_date = $("#from_date")[0].value;
// var to_date = $("#to_date")[0].value;

// if(from_date!=="" && to_date!=="")
// {
        
// var url = "{{ url('view-report-hbl-amount') }}" + "/" + from_date + "/" + to_date;
// viewModal(url);
// }

// })



$("#get_hbl_view").click(function(){

var from_date = $("#from_date")[0].value;
var to_date = $("#to_date")[0].value;
var type = $("#type")[0].value;
var employee_others = $("#employee_others")[0].value;


if(from_date!=="" && to_date!=="" && type !== "" && employee_others!==""){

var url = "{{ url('get-view-hbl-amount-new-created-second') }}" + "/" + from_date + "/" + to_date + "/" + type + "/" + employee_others;

viewModal(url);

}else if(from_date!=="" && to_date!=="" && type !== ""){

    var url = "{{ url('get-view-hbl-amount-new-created-second') }}" + "/" + from_date + "/" + to_date + "/" + type + "/" + employee_others;

    viewModal(url);

}else if(from_date!=="" && to_date!==""){

    var url = "{{ url('hbl-full-report-second-view') }}" + "/" + from_date + "/" + to_date + "/" + type + "/" + employee_others;
    viewModal(url);
}

})










</script>


