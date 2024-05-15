

    <div>
        <div class="col-12 d-flex justify-content-center">

           

            <div class="col-lg-12 col-sm-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Pending</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="add-employee-others-forms">Employee/Others</a> --}}
                        </div>
                    </div>
                    <div class="card-body">

<div class="row p-2">
    <div class="col">
        @csrf
        <input type="date" id="from_date_pending" name="from_date_pending" class="form-control"
            onchange="checkPending(this)">
    </div>
    <div class="col">
        <input type="date" id="to_date_pending" name="to_date_pending" class="form-control"
            onchange="checkPending(this)">
    </div>


    <div class="col">
        <select class="form-control toselect-tag-employee" style="width:100%"  name="pending_employee_id" id="pending_employee_id" onchange="checkPending(this)">
        </select>
    </div>


    <div class="col">
        <select class="form-control toselect-pending" onchange="checkPending(this)" name="pending_status" id="pending_status">
            <option value="">Select Status</option>
            <option>Pending</option>
            <option>Paid</option>
        </select>
    </div>

    <div>
        <input type="button" value="Reset" class="btn  btn-secondary" onclick="reset()">
        <input type="button" value="PDF" class="btn btn-danger" id="get_pending_pdf">
        <input type="button" value="View" class="btn btn-primary" id="get_pending_view">
    </div>
</div>

<div class="table-responsive">
    <div class="mb-3 d-flex justify-content-end">
        <input type="text" class="form-control w-25" id="search_value" name="search_value" placeholder="Type here to search........">
    </div>
    <table class="table table-bordered datatable_pending_full_list" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Date</th>
                <th>Employee</th>
                <th>Branch</th>
                <th>Amount</th>
                <th>Status</th>
                {{-- <th>Paid_Date</th> --}}
                <th>Acc_Name</th>
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

    var pending_table = $('.datatable_pending_full_list').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        "language": {
            "infoFiltered": ""
        },

        ajax: {
            url: "{{ url('get-list-of-pending') }}",
            data: function(d) {
                d.from_date_pending =  $("#from_date_pending").val();
                d.to_date_pending = $("#to_date_pending").val();
                d.search_value =  $("#search_value").val();
                d.pending_status =  $("#pending_status").val();
                d.pending_employee_id =  $("#pending_employee_id").val();
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
                data: 'location',
                name: 'location'
            },
            {
                data: 'amount',
                name: 'amount'
            },
            {
                data: 'status',
                name: 'status'
            },
            // {
            //     data: 'paid_date',
            //     name: 'paid_date'
            // },
            {
                data: 'account_name',
                name: 'account_name'
            },
            // {
            //     data: 'action',
            //     name: 'action',
            //     orderable: false,
            //     searchable: false
            // },
        ],

        success: function(data) {

        }
    });

    $("#search_value").on('keyup', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                pending_table.draw();
            }
        });



    function checkPending(e) {
        var from_date = $("#from_date_pending")[0].value;
        var to_date = $("#to_date_pending")[0].value;
        var employee = $("#pending_employee_id")[0].value;
        if (from_date !== "" && to_date !== "") {
            pending_table.draw();
        }
    }


    function reset() {
        var from_date = $("#from_date").val("");
        var to_date = $("#to_date").val("");
        var type = $("#pending_status").val("");
        var employee = $("#pending_employee_id").val("");
        var search_value = $("#search_value").val("");
        pending_table.draw();
    }


    $(document).on("click", ".pay_now_pending", function() {

        var data = $(this).data("id").split(",");
        // data[0] and data[1] we split array through data-id
        var url = "{{ url('pay-now') }}" + "/" + data[0] + "/" + data[1] + "/" + data[2] + "/" + data[3];
        payNowModalBody(url);
        $(".paynow-close")[0].click();
    })


    $(document).on("click", "#get_pending_view", function() {
    
        var from_date = $("#from_date_pending")[0].value;
        var to_date = $("#to_date_pending")[0].value;
        var pending_employee_id = $("#pending_employee_id")[0].value;
        var pending_status =  $("#pending_status")[0].value;
    
        var url = "{{ url('get-pending-list-view') }}" + "/" + from_date + "/" + to_date + "/" + pending_employee_id + "/" + pending_status;
        viewModal(url);
    
    })



    
// A $( document ).ready() block.
// $( document ).ready(function() {
//     $(".toselect-pending").select2();
// });



$(document).on("click", "#get_pending_pdf", function() {

var from_date_pending = $("#from_date_pending")[0].value;
var to_date_pending = $("#to_date_pending")[0].value;
var pending_employee_id = $("#pending_employee_id")[0].value;
var pending_status =  $("#pending_status")[0].value;
var search_value = $("#search_value")[0].value;

if(from_date_pending!=="" && to_date_pending!=="")

{
$.ajax({
headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
},
    url:"{{ url('get-pending-pdf') }}",
    type:"get",
    data:{from_date_pending:from_date_pending,to_date_pending:to_date_pending,pending_employee_id:pending_employee_id, pending_status: pending_status, search_value: search_value},
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
        link.download = 'pending_paid_detail_list.pdf';
        document.body.appendChild(link);
        link.click();
    }
})

}

})






    var parent = $("#pending_employee_id")[0];

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "{{ url('get-employee-for-pending') }}",
        type: "POST",
        success: function(data) {

            parent.innerHTML="";
            var create_first_child = document.createElement("option");
            create_first_child.value="";
            create_first_child.innerText="Select Employee";
            parent.appendChild(create_first_child);
            $.each(data[0], function(key, value) {
                var create_option = document.createElement("option");
                create_option.value = value["id"];
                create_option.innerText = value["employee_name"] + "-" + value["employee_post"];
                parent.appendChild(create_option);
            });

        }
    })


    // $("#pending_employee_id").select2();



    
</script>




